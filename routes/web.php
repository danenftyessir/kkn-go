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

// tambahkan di routes/web.php setelah authenticated routes

// notification routes (untuk semua user yang login)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/latest', [NotificationController::class, 'getLatest'])->name('notifications.latest');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications/destroy-read', [NotificationController::class, 'destroyRead'])->name('notifications.destroy-read');
});

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
        Route::post('/{id}/status', [ProjectManagementController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/milestones', [ProjectManagementController::class, 'addMilestone'])->name('add-milestone');
        Route::put('/{id}/milestones/{milestoneId}', [ProjectManagementController::class, 'updateMilestone'])->name('update-milestone');
        Route::delete('/{id}/milestones/{milestoneId}', [ProjectManagementController::class, 'deleteMilestone'])->name('delete-milestone');
        Route::post('/{id}/reports/{reportId}/approve', [ProjectManagementController::class, 'approveReport'])->name('approve-report');
        Route::post('/{id}/reports/{reportId}/reject', [ProjectManagementController::class, 'rejectReport'])->name('reject-report');
        Route::post('/{id}/review', [ProjectManagementController::class, 'submitReview'])->name('submit-review');
    });
    
    // reviews management
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/create/{projectId}', [ReviewController::class, 'create'])->name('create');
        Route::post('/{projectId}', [ReviewController::class, 'store'])->name('store');
        Route::get('/{id}', [ReviewController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ReviewController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ReviewController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReviewController::class, 'destroy'])->name('destroy');
    });
    
        // profile routes
    Route::get('/profile', [InstitutionProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [InstitutionProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [InstitutionProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [InstitutionProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/profile/regencies/{provinceId}', [InstitutionProfileController::class, 'getRegencies'])->name('profile.regencies');
});

/*
|--------------------------------------------------------------------------
| public API routes (untuk AJAX calls tanpa auth)
|--------------------------------------------------------------------------
*/

Route::prefix('api/public')->name('api.public.')->group(function () {
    // endpoint untuk get regencies berdasarkan province (digunakan di form registrasi)
    Route::get('/regencies/{provinceId}', [BrowseProblemsController::class, 'getRegencies'])
         ->name('regencies');

    // validation routes
    Route::post('/validate-registration/student', [\App\Http\Controllers\Auth\ValidationController::class, 'validateStudentStep'])
         ->name('validate.student.step');
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