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
use App\Http\Controllers\Institution\ProblemController;
use App\Http\Controllers\Institution\ApplicationReviewController;
use App\Http\Controllers\Institution\ProjectManagementController;
use App\Http\Controllers\Institution\ReviewController;
use App\Http\Controllers\NotificationController;

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
| student routes (memerlukan auth + verified email)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'user.type:student', 'verified'])->prefix('student')->name('student.')->group(function () {
    
    // dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // browse problems
    Route::get('/problems', [BrowseProblemsController::class, 'index'])->name('browse-problems.index');
    Route::get('/problems/{id}', [BrowseProblemsController::class, 'show'])->name('browse-problems.show');

    // alias untuk backward compatibility dan konsistensi
    Route::get('/browse-problems', [BrowseProblemsController::class, 'index'])->name('problems.index');
    Route::get('/browse-problems/{id}', [BrowseProblemsController::class, 'show'])->name('problems.show');

    // wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/toggle/{problemId}', [WishlistController::class, 'toggle'])->name('toggle');
        Route::delete('/{id}', [WishlistController::class, 'destroy'])->name('destroy');
    });
    
    // applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('index');
        Route::get('/create/{problemId}', [ApplicationController::class, 'create'])->name('create');
        Route::post('/', [ApplicationController::class, 'store'])->name('store');
        Route::get('/{id}', [ApplicationController::class, 'show'])->name('show');
        Route::delete('/{id}/withdraw', [ApplicationController::class, 'withdraw'])->name('withdraw');
        Route::delete('/{id}', [ApplicationController::class, 'destroy'])->name('destroy');
    });
    
    // my projects
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [MyProjectsController::class, 'index'])->name('index');
        Route::get('/{id}', [MyProjectsController::class, 'show'])->name('show');
        Route::post('/{id}/reports', [MyProjectsController::class, 'storeReport'])->name('reports.store');
        Route::post('/{id}/final-report', [MyProjectsController::class, 'storeFinalReport'])->name('final-report.store');
        Route::patch('/{id}/milestones/{milestoneId}', [MyProjectsController::class, 'updateMilestone'])->name('milestones.update');
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
        Route::post('/{id}/bookmark', [KnowledgeRepositoryController::class, 'toggleBookmark'])->name('bookmark');
        Route::get('/{id}/download', [KnowledgeRepositoryController::class, 'download'])->name('download');
        Route::get('/{id}/citation', [KnowledgeRepositoryController::class, 'getCitation'])->name('citation');
    });
    
    // profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [StudentProfileController::class, 'index'])->name('index');
        Route::get('/edit', [StudentProfileController::class, 'edit'])->name('edit');
        Route::put('/', [StudentProfileController::class, 'update'])->name('update');
        Route::put('/password', [StudentProfileController::class, 'updatePassword'])->name('update-password');
    });
});

/*
|--------------------------------------------------------------------------
| public portfolio routes
|--------------------------------------------------------------------------
*/
Route::get('/portfolio/{username}', [PortfolioController::class, 'public'])->name('portfolio.public');

/*
|--------------------------------------------------------------------------
| institution routes (memerlukan auth + verified email)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'user.type:institution', 'verified'])->prefix('institution')->name('institution.')->group(function () {
    
    // dashboard
    Route::get('/dashboard', [InstitutionDashboardController::class, 'index'])->name('dashboard');
    
    // problems management (CRUD)
    Route::prefix('problems')->name('problems.')->group(function () {
        Route::get('/', [ProblemController::class, 'index'])->name('index');
        Route::get('/create', [ProblemController::class, 'create'])->name('create');
        Route::post('/', [ProblemController::class, 'store'])->name('store');
        Route::get('/{id}', [ProblemController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProblemController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProblemController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProblemController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-status', [ProblemController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/regencies/{provinceId}', [ProblemController::class, 'getRegencies'])->name('regencies');
    });
    
    // applications review
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [ApplicationReviewController::class, 'index'])->name('index');
        Route::get('/{id}', [ApplicationReviewController::class, 'show'])->name('show');
        Route::get('/{id}/review', [ApplicationReviewController::class, 'review'])->name('review');
        Route::post('/{id}/accept', [ApplicationReviewController::class, 'accept'])->name('accept');
        Route::post('/{id}/reject', [ApplicationReviewController::class, 'reject'])->name('reject');
        Route::post('/{id}/cancel', [ApplicationReviewController::class, 'cancel'])->name('cancel');
        Route::post('/bulk-action', [ApplicationReviewController::class, 'bulkAction'])->name('bulk-action');
    });
    
    // project management
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectManagementController::class, 'index'])->name('index');
        Route::get('/{id}', [ProjectManagementController::class, 'show'])->name('show');
        Route::get('/{id}/manage', [ProjectManagementController::class, 'manage'])->name('manage');
        Route::post('/{id}/milestones', [ProjectManagementController::class, 'createMilestone'])->name('milestones.store');
        Route::put('/{id}/milestones/{milestoneId}', [ProjectManagementController::class, 'updateMilestone'])->name('milestones.update');
        Route::post('/{id}/complete', [ProjectManagementController::class, 'completeProject'])->name('complete');
    });
    
    // reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/create/{projectId}', [ReviewController::class, 'create'])->name('create');
        Route::post('/', [ReviewController::class, 'store'])->name('store');
        Route::get('/{id}', [ReviewController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ReviewController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ReviewController::class, 'update'])->name('update');
    });
    
    // profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [InstitutionProfileController::class, 'index'])->name('index');
        Route::get('/edit', [InstitutionProfileController::class, 'edit'])->name('edit');
        Route::put('/', [InstitutionProfileController::class, 'update'])->name('update');
        Route::put('/password', [InstitutionProfileController::class, 'updatePassword'])->name('update-password');
    });
});

/*
|--------------------------------------------------------------------------
| notification routes (untuk semua authenticated users)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
});