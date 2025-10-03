<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\BrowseProblemsController;
use App\Http\Controllers\Student\ApplicationController;
use App\Http\Controllers\Student\WishlistController;
use App\Http\Controllers\Student\MyProjectsController;
use App\Http\Controllers\Student\PortfolioController;
use App\Http\Controllers\Student\KnowledgeRepositoryController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Institution\DashboardController as InstitutionDashboardController;
use App\Http\Controllers\Institution\ProfileController as InstitutionProfileController;

/*
|--------------------------------------------------------------------------
| path: routes/web.php
| web routes untuk aplikasi KKN-GO
|--------------------------------------------------------------------------
*/

// homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| auth routes
|--------------------------------------------------------------------------
*/

// guest only routes
Route::middleware('guest')->group(function () {
    // login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // register
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::get('/register/student', [RegisterController::class, 'showStudentRegisterForm'])->name('register.student');
    Route::get('/register/institution', [RegisterController::class, 'showInstitutionRegisterForm'])->name('register.institution');
    Route::post('/register/student', [RegisterController::class, 'registerStudent'])->name('register.student.submit');
    Route::post('/register/institution', [RegisterController::class, 'registerInstitution'])->name('register.institution.submit');
    
    // password reset
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// authenticated routes
Route::middleware('auth')->group(function () {
    // logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // email verification
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');
});

/*
|--------------------------------------------------------------------------
| public portfolio routes (dapat diakses tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/portfolio/{slug}', [PortfolioController::class, 'publicView'])->name('portfolio.public');

/*
|--------------------------------------------------------------------------
| student routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user.type:student'])->prefix('student')->name('student.')->group(function () {
    
    // dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // browse problems
    Route::get('/browse-problems', [BrowseProblemsController::class, 'index'])->name('browse-problems');
    Route::get('/problems/{id}', [BrowseProblemsController::class, 'show'])->name('problems.show');
    
    // applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('index');
        Route::get('/{id}', [ApplicationController::class, 'show'])->name('show');
        Route::get('/create/{problemId}', [ApplicationController::class, 'create'])->name('create');
        Route::post('/store/{problemId}', [ApplicationController::class, 'store'])->name('store');
        Route::delete('/{id}/withdraw', [ApplicationController::class, 'withdraw'])->name('withdraw');
    });
    
    // wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/toggle/{problemId}', [WishlistController::class, 'toggle'])->name('toggle');
        Route::delete('/{id}', [WishlistController::class, 'destroy'])->name('destroy');
    });
    
    // projects
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [MyProjectsController::class, 'index'])->name('index');
        Route::get('/{id}', [MyProjectsController::class, 'show'])->name('show');
        
        // milestones
        Route::post('/milestones/{milestoneId}/update', [MyProjectsController::class, 'updateMilestone'])->name('milestones.update');
        
        // reports
        Route::get('/{projectId}/reports/create', [MyProjectsController::class, 'createReport'])->name('create-report');
        Route::post('/{projectId}/reports/store', [MyProjectsController::class, 'storeReport'])->name('store-report');
        Route::get('/reports/{reportId}/download', [MyProjectsController::class, 'downloadReport'])->name('download-report');
        
        // final report
        Route::get('/{projectId}/final-report/create', [MyProjectsController::class, 'createFinalReport'])->name('create-final-report');
        Route::post('/{projectId}/final-report/store', [MyProjectsController::class, 'storeFinalReport'])->name('store-final-report');
    });
    
    // portfolio
    Route::prefix('portfolio')->name('portfolio.')->group(function () {
        Route::get('/', [PortfolioController::class, 'index'])->name('index');
        Route::get('/share-link', [PortfolioController::class, 'getShareLink'])->name('share-link');
        Route::post('/projects/{projectId}/toggle-visibility', [PortfolioController::class, 'toggleProjectVisibility'])->name('toggle-visibility');
        Route::get('/download-pdf', [PortfolioController::class, 'downloadPDF'])->name('download-pdf');
    });
    
    // knowledge repository
    Route::prefix('repository')->name('repository.')->group(function () {
        Route::get('/', [KnowledgeRepositoryController::class, 'index'])->name('index');
        Route::get('/{id}', [KnowledgeRepositoryController::class, 'show'])->name('show');
        Route::get('/{id}/download', [KnowledgeRepositoryController::class, 'download'])->name('download');
        Route::get('/{id}/citation', [KnowledgeRepositoryController::class, 'getCitation'])->name('citation');
        Route::post('/{id}/bookmark', [KnowledgeRepositoryController::class, 'bookmark'])->name('bookmark');
        Route::post('/{id}/report', [KnowledgeRepositoryController::class, 'report'])->name('report');
    });
    
    // profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [StudentProfileController::class, 'index'])->name('index');
        Route::get('/edit', [StudentProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [StudentProfileController::class, 'update'])->name('update');
        Route::get('/public/{id}', [StudentProfileController::class, 'publicView'])->name('public');
    });
});

/*
|--------------------------------------------------------------------------
| institution routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user.type:institution'])->prefix('institution')->name('institution.')->group(function () {
    
    // dashboard
    Route::get('/dashboard', [InstitutionDashboardController::class, 'index'])->name('dashboard');
    
    // profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [InstitutionProfileController::class, 'index'])->name('index');
        Route::get('/edit', [InstitutionProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [InstitutionProfileController::class, 'update'])->name('update');
        Route::get('/public/{id}', [InstitutionProfileController::class, 'publicView'])->name('public');
    });
    
    // TODO: tambahkan routes untuk mengelola problems, mereview aplikasi, dll
});

/*
|--------------------------------------------------------------------------
| admin routes (coming soon)
|--------------------------------------------------------------------------
*/
// TODO: tambahkan admin routes untuk mengelola users, approve documents, dll