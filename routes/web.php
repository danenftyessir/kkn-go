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
    
    // email verification routes
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');
});

/*
|--------------------------------------------------------------------------
| public routes
|--------------------------------------------------------------------------
*/

// public portfolio
Route::get('/portfolio/{slug}', [PortfolioController::class, 'publicView'])->name('portfolio.public');

// public student profile
Route::get('/student/profile/{username}', [StudentProfileController::class, 'publicProfile'])->name('student.profile.public');

// public institution profile
Route::get('/institution/profile/{id}', [InstitutionProfileController::class, 'publicProfile'])->name('institution.profile.public');

/*
|--------------------------------------------------------------------------
| student routes (memerlukan auth + verified email)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'user.type:student', 'verified'])->prefix('student')->name('student.')->group(function () {
    
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
        Route::post('/{problemId}/toggle', [WishlistController::class, 'toggle'])->name('toggle');
        Route::get('/{problemId}/check', [WishlistController::class, 'check'])->name('check');
        Route::patch('/{problemId}/notes', [WishlistController::class, 'updateNotes'])->name('notes');
    });
    
    // projects (my projects)
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [MyProjectsController::class, 'index'])->name('index');
        Route::get('/{id}', [MyProjectsController::class, 'show'])->name('show');
        Route::post('/{id}/report', [MyProjectsController::class, 'storeReport'])->name('report.store');
        Route::post('/{id}/final-report', [MyProjectsController::class, 'storeFinalReport'])->name('final-report.store');
        Route::patch('/{id}/milestone/{milestoneId}', [MyProjectsController::class, 'updateMilestone'])->name('milestone.update');
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
    
    // profile routes
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [StudentProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [StudentProfileController::class, 'updatePassword'])->name('profile.password.update');
});

/*
|--------------------------------------------------------------------------
| institution routes (memerlukan auth + verified email)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'user.type:institution', 'verified'])->prefix('institution')->name('institution.')->group(function () {
    
    // dashboard
    Route::get('/dashboard', [InstitutionDashboardController::class, 'index'])->name('dashboard');
    
    // profile routes
    Route::get('/profile', [InstitutionProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [InstitutionProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [InstitutionProfileController::class, 'update'])->name('profile.update');
    
    // TODO: problems/projects management
    // TODO: applications review
    // TODO: project management
});

/*
|--------------------------------------------------------------------------
| public API routes (untuk AJAX calls tanpa auth)
|--------------------------------------------------------------------------
*/

Route::prefix('api')->name('api.public.')->group(function () {
    // endpoint untuk get regencies berdasarkan province (digunakan di form registrasi)
    Route::get('/regencies/{provinceId}', [BrowseProblemsController::class, 'getRegencies'])
         ->name('regencies');
});

/*
|--------------------------------------------------------------------------
| admin routes (TODO)
|--------------------------------------------------------------------------
*/

// Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
//     Route::resource('users', UserController::class);
//     Route::resource('verifications', VerificationController::class);
// });

/*
|--------------------------------------------------------------------------
| dev routes (development only)
|--------------------------------------------------------------------------
*/

if (app()->environment('local')) {
    Route::get('/dev/login', function () {
        return view('dev.login');
    })->name('dev.login');
}