<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\BrowseProblemsController;
use App\Http\Controllers\Student\ApplicationController;
use App\Http\Controllers\Student\MyProjectsController;
use App\Http\Controllers\Student\PortfolioController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\WishlistController;
use App\Http\Controllers\Student\KnowledgeRepositoryController;
use App\Http\Controllers\Institution\DashboardController as InstitutionDashboardController;
use App\Http\Controllers\Institution\ProblemController;
use App\Http\Controllers\Institution\ApplicationReviewController;
use App\Http\Controllers\Institution\ProjectManagementController;
use App\Http\Controllers\Institution\ReviewController;
use App\Http\Controllers\Institution\ProfileController as InstitutionProfileController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| web routes
|--------------------------------------------------------------------------
*/

// halaman home
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| development routes (hanya untuk development)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/dev/login/{userId}', function($userId) {
        Auth::loginUsingId($userId);
        $user = Auth::user();
        
        if ($user->user_type === 'student') {
            return redirect()->route('student.dashboard');
        } else {
            return redirect()->route('institution.dashboard');
        }
    })->name('dev.login');
}

/*
|--------------------------------------------------------------------------
| authentication routes
|--------------------------------------------------------------------------
*/

// guest routes
Route::middleware('guest')->group(function () {
    // login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::get('/register/student', [RegisterController::class, 'showStudentForm'])->name('register.student');
    Route::post('/register/student', [RegisterController::class, 'registerStudent'])->name('register.student.submit');
    Route::get('/register/institution', [RegisterController::class, 'showInstitutionForm'])->name('register.institution');
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
    
    // notifikasi (untuk semua user)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| student routes (memerlukan auth + verified email)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'user.type:student', 'verified'])->prefix('student')->name('student.')->group(function () {
    
    // dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // browse problems (marketplace)
    Route::prefix('browse-problems')->name('browse-problems.')->group(function () {
        Route::get('/', [BrowseProblemsController::class, 'index'])->name('index');
        Route::get('/{id}', [BrowseProblemsController::class, 'show'])->name('show');
    });
    
    // applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('index');
        Route::get('/create/{problemId}', [ApplicationController::class, 'create'])->name('create');
        Route::post('/', [ApplicationController::class, 'store'])->name('store');
        Route::get('/{id}', [ApplicationController::class, 'show'])->name('show');
        Route::delete('/{id}', [ApplicationController::class, 'withdraw'])->name('withdraw');
    });
    
    // my projects
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [MyProjectsController::class, 'index'])->name('index');
        Route::get('/{id}', [MyProjectsController::class, 'show'])->name('show');
        Route::post('/{id}/upload-report', [MyProjectsController::class, 'uploadReport'])->name('upload-report');
        Route::post('/{id}/submit-final', [MyProjectsController::class, 'submitFinal'])->name('submit-final');
    });
    
    // portfolio (public profile)
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
    
    // profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [StudentProfileController::class, 'index'])->name('index');
        Route::put('/', [StudentProfileController::class, 'update'])->name('update');
        Route::put('/password', [StudentProfileController::class, 'updatePassword'])->name('password');
    });
    
    // wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/{problemId}', [WishlistController::class, 'toggle'])->name('toggle');
    });
    
    // knowledge repository
    Route::prefix('knowledge-repository')->name('knowledge-repository.')->group(function () {
        Route::get('/', [KnowledgeRepositoryController::class, 'index'])->name('index');
        Route::get('/{id}', [KnowledgeRepositoryController::class, 'show'])->name('show');
        Route::get('/{id}/download', [KnowledgeRepositoryController::class, 'download'])->name('download');
    });
});

/*
|--------------------------------------------------------------------------
| institution routes (memerlukan auth + verified email)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'user.type:institution', 'verified'])->prefix('institution')->name('institution.')->group(function () {
    
    // dashboard
    Route::get('/dashboard', [InstitutionDashboardController::class, 'index'])->name('dashboard');
    
    // problem management (CRUD masalah/proyek)
    Route::resource('problems', ProblemController::class);
    
    // application review
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [ApplicationReviewController::class, 'index'])->name('index');
        Route::get('/{id}', [ApplicationReviewController::class, 'show'])->name('show');
        Route::get('/{id}/review', [ApplicationReviewController::class, 'review'])->name('review');
        Route::post('/{id}/accept', [ApplicationReviewController::class, 'accept'])->name('accept');
        Route::post('/{id}/reject', [ApplicationReviewController::class, 'reject'])->name('reject');
    });
    
    // project management
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectManagementController::class, 'index'])->name('index');
        Route::get('/{id}', [ProjectManagementController::class, 'show'])->name('show');
        Route::post('/{id}/verify-report', [ProjectManagementController::class, 'verifyReport'])->name('verify-report');
        Route::post('/{id}/complete', [ProjectManagementController::class, 'complete'])->name('complete');
    });
    
    // student reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/create/{projectId}', [ReviewController::class, 'create'])->name('create');
        Route::post('/', [ReviewController::class, 'store'])->name('store');
    });
    
    // profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [InstitutionProfileController::class, 'index'])->name('index');
        Route::put('/', [InstitutionProfileController::class, 'update'])->name('update');
        Route::put('/password', [InstitutionProfileController::class, 'updatePassword'])->name('password');
    });
});

/*
|--------------------------------------------------------------------------
| public routes (tidak perlu auth)
|--------------------------------------------------------------------------
*/

// public portfolio
Route::get('/portfolio/{username}', [PortfolioController::class, 'public'])->name('portfolio.public');

// public institution profile
Route::get('/institution/{username}', [InstitutionProfileController::class, 'public'])->name('institution.public');