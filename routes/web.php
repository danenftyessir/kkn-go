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
use App\Http\Controllers\AboutController;
use App\Http\Controllers\Auth\ValidationController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Web Routes - KKN-GO Platform
|--------------------------------------------------------------------------
|
| file ini berisi semua routing untuk aplikasi KKN-GO
| 
*/

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// about us page
Route::get('/about', [AboutController::class, 'index'])->name('about'); 

// contact page
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// public student profile/portfolio (dapat diakses tanpa login)
Route::get('/profile/{username}', [StudentProfileController::class, 'publicView'])->name('profile.public');

// redirect portfolio ke profile untuk backward compatibility
Route::get('/portfolio/{username}', function($username) {
    return redirect()->route('profile.public', $username);
});

/*
|--------------------------------------------------------------------------
| Development Routes (hanya untuk development)
|--------------------------------------------------------------------------
*/

if (config('app.env') === 'local' || config('app.env') === 'development') {
    Route::get('/dev/login', function () {
        return view('dev.login');
    })->name('dev.login');
}

/*
|--------------------------------------------------------------------------
| Guest Routes (hanya bisa diakses jika belum login)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    
    // authentication pages
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::get('/register/student', [RegisterController::class, 'showStudentForm'])->name('register.student');
    Route::post('/register/student', [RegisterController::class, 'registerStudent'])->name('register.student.submit');
    Route::get('/register/institution', [RegisterController::class, 'showInstitutionForm'])->name('register.institution');
    Route::post('/register/institution', [RegisterController::class, 'registerInstitution'])->name('register.institution.submit');
    Route::post('/validation/student/step', [ValidationController::class, 'validateStudentStep'])->name('validation.student.step');
    
    // forgot password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    // reset password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (perlu login)
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
    
    // browse
    Route::prefix('browse-problems')->name('browse-problems.')->group(function () {
        Route::get('/', [BrowseProblemsController::class, 'index'])->name('index');
        Route::get('/{id}', [BrowseProblemsController::class, 'show'])->name('show'); 
    });

    // browse problems alternatif dengan URL /student/problems (untuk backward compatibility)
    Route::prefix('problems')->name('problems.')->group(function () {
        Route::get('/', [BrowseProblemsController::class, 'index'])->name('index');
        Route::get('/{id}', [BrowseProblemsController::class, 'show'])->name('show');
        Route::get('/api/get-regencies', [BrowseProblemsController::class, 'getRegencies'])->name('get-regencies');
    });
    
    // applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('index');
        Route::get('/create/{problemId}', [ApplicationController::class, 'create'])->name('create');
        Route::post('/', [ApplicationController::class, 'store'])->name('store');
        Route::get('/{id}', [ApplicationController::class, 'show'])->name('show');
        Route::delete('/{id}', [ApplicationController::class, 'destroy'])->name('withdraw');
        Route::get('/{id}/download-proposal', [ApplicationController::class, 'downloadProposal'])->name('download-proposal');
    });
    
    // projects
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [MyProjectsController::class, 'index'])->name('index');
        Route::get('/{id}', [MyProjectsController::class, 'show'])->name('show');
        Route::get('/{id}/report/create', [MyProjectsController::class, 'createReport'])->name('create-report');
        Route::post('/{id}/report', [MyProjectsController::class, 'storeReport'])->name('store-report');
        Route::get('/{id}/final-report/create', [MyProjectsController::class, 'createFinalReport'])->name('create-final-report');
        Route::post('/{id}/final-report', [MyProjectsController::class, 'storeFinalReport'])->name('store-final-report');
    });
    
    // profile (gabungan dengan portfolio) - private routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [StudentProfileController::class, 'index'])->name('index');
        Route::get('/edit', [StudentProfileController::class, 'edit'])->name('edit');
        Route::put('/', [StudentProfileController::class, 'update'])->name('update');
        Route::put('/password', [StudentProfileController::class, 'updatePassword'])->name('password.update');
        Route::post('/project/{projectId}/toggle-visibility', [StudentProfileController::class, 'toggleProjectVisibility'])->name('project.toggle-visibility');
        Route::get('/share-link', [StudentProfileController::class, 'getShareLink'])->name('share-link');
    });
    
    // redirect portfolio ke profile untuk backward compatibility
    Route::get('/portfolio', function() {
        return redirect()->route('student.profile.index');
    });
    
    // wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/{problemId}', [WishlistController::class, 'toggle'])->name('toggle');
        Route::delete('/{wishlistId}/remove', [WishlistController::class, 'remove'])->name('remove');
    });
    
    // knowledge repository
    Route::prefix('repository')->name('repository.')->group(function () {
        Route::get('/', [KnowledgeRepositoryController::class, 'index'])->name('index');
        Route::get('/{id}', [KnowledgeRepositoryController::class, 'show'])->name('show');
        Route::get('/{id}/download', [KnowledgeRepositoryController::class, 'download'])->name('download');
        Route::get('/api/get-regencies', [KnowledgeRepositoryController::class, 'getRegencies'])->name('get-regencies');    
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
        Route::put('/{id}', [ProblemController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProblemController::class, 'destroy'])->name('destroy');
    });
    
    // applications review
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [ApplicationReviewController::class, 'index'])->name('index');
        Route::get('/{id}', [ApplicationReviewController::class, 'show'])->name('show');
        Route::get('/{id}/review', [ApplicationReviewController::class, 'review'])->name('review');
        Route::post('/{id}/accept', [ApplicationReviewController::class, 'accept'])->name('accept');
        Route::post('/{id}/reject', [ApplicationReviewController::class, 'reject'])->name('reject');
    });
    
    // projects management
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectManagementController::class, 'index'])->name('index');
        Route::get('/{id}', [ProjectManagementController::class, 'show'])->name('show');
        Route::get('/{id}/manage', [ProjectManagementController::class, 'manage'])->name('manage');
        Route::post('/{id}/milestone', [ProjectManagementController::class, 'addMilestone'])->name('add-milestone');
        Route::put('/{id}/milestone/{milestoneId}', [ProjectManagementController::class, 'updateMilestone'])->name('update-milestone');
        Route::post('/{id}/status', [ProjectManagementController::class, 'updateStatus'])->name('update-status');    
        Route::post('/{id}/reports/{reportId}/approve', [ProjectManagementController::class, 'approveReport'])->name('approve-report');
        Route::post('/{id}/reports/{reportId}/reject', [ProjectManagementController::class, 'rejectReport'])->name('reject-report');
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
        Route::put('/password', [InstitutionProfileController::class, 'updatePassword'])->name('password.update');
    });


/*
|--------------------------------------------------------------------------
| API Routes untuk Dynamic Dropdown
|--------------------------------------------------------------------------
*/

// API untuk mendapatkan regencies berdasarkan province
// digunakan di form create/edit problem untuk dynamic dropdown
Route::get('/api/regencies/{provinceId}', [ProblemController::class, 'getRegencies'])->name('api.regencies');

});

/*
|--------------------------------------------------------------------------
| Notifications Routes (Student & Institution)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    // halaman index notifikasi (dapat diakses langsung)
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    
    // endpoint untuk dropdown (hanya via ajax)
    Route::get('/latest', [NotificationController::class, 'getLatest'])->name('latest');
    
    // mark as read (support ajax dan form submit)
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    
    // mark all as read (support ajax dan form submit)
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    
    // hapus notifikasi
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    
    // hapus semua notifikasi yang sudah dibaca
    Route::delete('/read', [NotificationController::class, 'destroyRead'])->name('destroy-read');
});

/*
|--------------------------------------------------------------------------
| Public Institution Profile
|--------------------------------------------------------------------------
*/

Route::get('/institution/{id}', [InstitutionProfileController::class, 'showPublic'])
    ->where('id', '[0-9]+')
    ->name('institution.public');