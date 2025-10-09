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
use App\Http\Controllers\Student\MyProjectsController;
use App\Http\Controllers\Student\PortfolioController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\WishlistController;
use App\Http\Controllers\Student\KnowledgeRepositoryController;
use App\Http\Controllers\Institution\DashboardController as InstitutionDashboardController;
use App\Http\Controllers\Institution\ProblemController;
use App\Http\Controllers\Institution\ApplicationReviewController;
use App\Http\Controllers\Institution\ProjectManagementController;
use App\Http\Controllers\Institution\ProfileController as InstitutionProfileController;
use App\Http\Controllers\Institution\ReviewController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes - KKN-GO Platform
|--------------------------------------------------------------------------
|
| file ini berisi semua web routes untuk aplikasi KKN-GO
| routes dikelompokkan berdasarkan user type dan authentication requirement
|
*/

/*
|--------------------------------------------------------------------------
| DEBUG ROUTES - HAPUS SETELAH MASALAH TERATASI
|--------------------------------------------------------------------------
*/

// route untuk debug session (HANYA UNTUK DEVELOPMENT)
if (config('app.debug')) {
    Route::get('/debug/session', function() {
        $sessionId = session()->getId();
        $authCheck = Auth::check();
        $userId = Auth::id();
        $user = Auth::user();
        
        $sessionData = [];
        if (config('session.driver') === 'database') {
            $sessionData = DB::table('sessions')
                ->where('id', $sessionId)
                ->first();
        }
        
        return response()->json([
            'session_id' => $sessionId,
            'auth_check' => $authCheck,
            'user_id' => $userId,
            'user' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
            ] : null,
            'session_data' => $sessionData,
            'session_config' => [
                'driver' => config('session.driver'),
                'lifetime' => config('session.lifetime'),
                'cookie' => config('session.cookie'),
                'domain' => config('session.domain'),
                'secure' => config('session.secure'),
                'same_site' => config('session.same_site'),
                'http_only' => config('session.http_only'),
            ],
            'all_sessions_count' => config('session.driver') === 'database' 
                ? DB::table('sessions')->count() 
                : 'N/A',
        ]);
    });
    
    // route untuk test auth
    Route::get('/debug/auth', function() {
        return response()->json([
            'auth_check' => Auth::check(),
            'user_id' => Auth::id(),
            'guard' => config('auth.defaults.guard'),
            'session_driver' => config('session.driver'),
        ]);
    });
}

/*
|--------------------------------------------------------------------------
| Public Routes (Tidak Perlu Login)
|--------------------------------------------------------------------------
*/

// halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// public student portfolio (bisa diakses tanpa login)
Route::get('/portfolio/{username}', [PortfolioController::class, 'publicView'])->name('portfolio.public');

// public student profile (bisa diakses tanpa login)
Route::get('/student/{username}', [StudentProfileController::class, 'publicProfile'])->name('student.profile.public');

// public institution profile (bisa diakses tanpa login)
Route::get('/institution/{id}', [InstitutionProfileController::class, 'showPublic'])->name('institution.public');

/*
|--------------------------------------------------------------------------
| Guest Routes (Hanya untuk yang Belum Login)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    
    // login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // register - halaman pilihan
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    
    // register student
    Route::get('/register/student', [RegisterController::class, 'showStudentForm'])->name('register.student');
    Route::post('/register/student', [RegisterController::class, 'registerStudent'])->name('register.student.submit');
    
    // register institution
    Route::get('/register/institution', [RegisterController::class, 'showInstitutionForm'])->name('register.institution');
    Route::post('/register/institution', [RegisterController::class, 'registerInstitution'])->name('register.institution.submit');
    
    // forgot password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    // reset password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Perlu Login)
|--------------------------------------------------------------------------
*/

// logout (harus authenticated)
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// email verification
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->name('verification.resend');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.user.type:student'])->prefix('student')->name('student.')->group(function () {
    
    // dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // browse problems dengan dua cara akses
    // cara 1: /student/problems/* (URL pendek)
    Route::prefix('problems')->name('problems.')->group(function () {
        Route::get('/', [BrowseProblemsController::class, 'index'])->name('index');
        Route::get('/{id}', [BrowseProblemsController::class, 'show'])->name('show');
    });
    
    // cara 2: /student/browse-problems/* (URL deskriptif)
    Route::prefix('browse-problems')->name('browse-problems.')->group(function () {
        Route::get('/', [BrowseProblemsController::class, 'index'])->name('index');
        Route::get('/{id}', [BrowseProblemsController::class, 'show'])->name('detail');
    });
    
    // applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('index');
        Route::get('/{id}', [ApplicationController::class, 'show'])->name('show');
        Route::post('/', [ApplicationController::class, 'store'])->name('store');
        Route::patch('/{id}/withdraw', [ApplicationController::class, 'withdraw'])->name('withdraw');
    });
    
    // my projects
    Route::prefix('my-projects')->name('my-projects.')->group(function () {
        Route::get('/', [MyProjectsController::class, 'index'])->name('index');
        Route::get('/{id}', [MyProjectsController::class, 'show'])->name('show');
        Route::post('/{id}/upload-report', [MyProjectsController::class, 'uploadReport'])->name('upload-report');
        Route::post('/{id}/submit-final', [MyProjectsController::class, 'submitFinal'])->name('submit-final');
    });
    
    // portfolio
    Route::prefix('portfolio')->name('portfolio.')->group(function () {
        Route::get('/', [PortfolioController::class, 'index'])->name('index');
        Route::get('/edit', [PortfolioController::class, 'edit'])->name('edit');
        Route::patch('/', [PortfolioController::class, 'update'])->name('update');
    });
    
    // profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [StudentProfileController::class, 'index'])->name('index');
        Route::get('/edit', [StudentProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [StudentProfileController::class, 'update'])->name('update');
        Route::patch('/password', [StudentProfileController::class, 'updatePassword'])->name('update-password');
    });
    
    // wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/{problem}', [WishlistController::class, 'toggle'])->name('toggle');
    });
    
    // knowledge repository
    Route::prefix('repository')->name('repository.')->group(function () {
        Route::get('/', [KnowledgeRepositoryController::class, 'index'])->name('index');
        Route::get('/{id}', [KnowledgeRepositoryController::class, 'show'])->name('show');
        Route::post('/{id}/download', [KnowledgeRepositoryController::class, 'download'])->name('download');
        Route::post('/{id}/bookmark', [KnowledgeRepositoryController::class, 'bookmark'])->name('bookmark');
    });
    
    // notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::patch('/read-all', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    });
    
});

/*
|--------------------------------------------------------------------------
| Institution Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.user.type:institution'])->prefix('institution')->name('institution.')->group(function () {
    
    // dashboard
    Route::get('/dashboard', [InstitutionDashboardController::class, 'index'])->name('dashboard');
    
    // problems management
    Route::prefix('problems')->name('problems.')->group(function () {
        Route::get('/', [ProblemController::class, 'index'])->name('index');
        Route::get('/create', [ProblemController::class, 'create'])->name('create');
        Route::post('/', [ProblemController::class, 'store'])->name('store');
        Route::get('/{id}', [ProblemController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProblemController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [ProblemController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProblemController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/status', [ProblemController::class, 'updateStatus'])->name('update-status');
    });
    
    // applications review
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [ApplicationReviewController::class, 'index'])->name('index');
        Route::get('/{id}', [ApplicationReviewController::class, 'show'])->name('show');
        Route::patch('/{id}/review', [ApplicationReviewController::class, 'review'])->name('review');
    });
    
    // project management
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectManagementController::class, 'index'])->name('index');
        Route::get('/{id}', [ProjectManagementController::class, 'show'])->name('show');
        Route::post('/{id}/feedback', [ProjectManagementController::class, 'giveFeedback'])->name('feedback');
        Route::patch('/{id}/status', [ProjectManagementController::class, 'updateStatus'])->name('update-status');
    });
    
    // profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [InstitutionProfileController::class, 'index'])->name('index');
        Route::get('/edit', [InstitutionProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [InstitutionProfileController::class, 'update'])->name('update');
        Route::patch('/password', [InstitutionProfileController::class, 'updatePassword'])->name('update-password');
    });
    
    // reviews untuk mahasiswa
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::post('/{project}', [ReviewController::class, 'store'])->name('store');
        Route::patch('/{review}', [ReviewController::class, 'update'])->name('update');
    });
    
    // notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::patch('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::patch('/read-all', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    });
    
});
