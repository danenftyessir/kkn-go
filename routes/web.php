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

/*
|--------------------------------------------------------------------------
| DEBUG ROUTES - TEMPORARY (HAPUS SETELAH FIX!)
|--------------------------------------------------------------------------
*/

Route::get('/debug-clear-cache', function () {
    try {
        // hapus file cache manual
        $cacheFiles = [
            base_path('bootstrap/cache/routes-v7.php'),
            base_path('bootstrap/cache/routes.php'),
            base_path('bootstrap/cache/config.php'),
        ];
        
        $deleted = [];
        foreach ($cacheFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
                $deleted[] = basename($file);
            }
        }
        
        // clear via artisan
        \Artisan::call('route:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        
        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Cache cleared successfully!',
            'deleted_files' => $deleted,
            'routes_cached' => app()->routesAreCached() ? 'YES' : 'NO',
            'config_cached' => app()->configurationIsCached() ? 'YES' : 'NO',
            'timestamp' => now()->toDateTimeString(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'ERROR',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

Route::get('/debug-info', function () {
    $user = auth()->user();
    return response()->json([
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'php_version' => phpversion(),
        'laravel_version' => app()->version(),
        'routes_cached' => app()->routesAreCached() ? 'YES' : 'NO',
        'config_cached' => app()->configurationIsCached() ? 'YES' : 'NO',
        'user_authenticated' => auth()->check() ? 'YES' : 'NO',
        'user_id' => $user?->id,
        'user_type' => $user?->user_type,
        'user_email' => $user?->email,
        'has_student_record' => $user && $user->user_type === 'student' ? ($user->student ? 'YES' : 'NO') : 'N/A',
        'has_institution_record' => $user && $user->user_type === 'institution' ? ($user->institution ? 'YES' : 'NO') : 'N/A',
        'cache_files' => [
            'routes' => file_exists(base_path('bootstrap/cache/routes.php')) ? 'EXISTS' : 'NOT FOUND',
            'routes-v7' => file_exists(base_path('bootstrap/cache/routes-v7.php')) ? 'EXISTS' : 'NOT FOUND',
            'config' => file_exists(base_path('bootstrap/cache/config.php')) ? 'EXISTS' : 'NOT FOUND',
        ],
        'session_driver' => config('session.driver'),
        'database_connection' => config('database.default'),
    ]);
});

Route::get('/debug-routes', function () {
    $routes = collect(\Route::getRoutes())->map(function ($route) {
        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName(),
        ];
    })->toArray();
    
    return response()->json([
        'total_routes' => count($routes),
        'routes' => $routes,
    ]);
});

Route::get('/debug-db', function () {
    try {
        \DB::connection()->getPdo();
        
        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Database connected',
            'connection' => config('database.default'),
            'users_count' => \DB::table('users')->count(),
            'students_count' => \DB::table('students')->count(),
            'institutions_count' => \DB::table('institutions')->count(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'ERROR',
            'message' => 'Database connection failed',
            'error' => $e->getMessage(),
        ], 500);
    }
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/portfolio/{username}', [PortfolioController::class, 'publicView'])->name('portfolio.public');
Route::get('/student/{username}', [StudentProfileController::class, 'publicProfile'])->name('student.profile.public');
Route::get('/institution/{id}', [InstitutionProfileController::class, 'showPublic'])->name('institution.public');

/*
|--------------------------------------------------------------------------
| GUEST ROUTES (belum login)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::get('/register/student', [RegisterController::class, 'showStudentForm'])->name('register.student');
    Route::post('/register/student', [RegisterController::class, 'registerStudent'])->name('register.student.submit');
    Route::get('/register/institution', [RegisterController::class, 'showInstitutionForm'])->name('register.institution');
    Route::post('/register/institution', [RegisterController::class, 'registerInstitution'])->name('register.institution.submit');
    
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])->name('verification.resend');
});

/*
|--------------------------------------------------------------------------
| STUDENT ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.user.type:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        
        // browse problems (dua versi URL)
        Route::get('/problems', [BrowseProblemsController::class, 'index'])->name('problems.index');
        Route::get('/problems/{id}', [BrowseProblemsController::class, 'show'])->name('problems.show');
        
        Route::get('/browse-problems', [BrowseProblemsController::class, 'index'])->name('browse-problems.index');
        Route::get('/browse-problems/{id}', [BrowseProblemsController::class, 'show'])->name('browse-problems.detail');
        
        // applications
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/create/{problemId}', [ApplicationController::class, 'create'])->name('applications.create');
        Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
        Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::delete('/applications/{id}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
        
        // projects
        Route::get('/projects', [MyProjectsController::class, 'index'])->name('projects.index');
        Route::get('/projects/{id}', [MyProjectsController::class, 'show'])->name('projects.show');
        Route::get('/projects/{id}/report/create', [MyProjectsController::class, 'createReport'])->name('projects.create-report');
        Route::post('/projects/{id}/report', [MyProjectsController::class, 'storeReport'])->name('projects.store-report');
        Route::get('/projects/{id}/final-report/create', [MyProjectsController::class, 'createFinalReport'])->name('projects.create-final-report');
        Route::post('/projects/{id}/final-report', [MyProjectsController::class, 'storeFinalReport'])->name('projects.store-final-report');
        
        // portfolio
        Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
        
        // profile
        Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [StudentProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [StudentProfileController::class, 'updatePassword'])->name('profile.password.update');
        
        // wishlist
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/{problemId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
        
        // repository
        Route::get('/repository', [KnowledgeRepositoryController::class, 'index'])->name('repository.index');
        Route::get('/repository/{id}', [KnowledgeRepositoryController::class, 'show'])->name('repository.show');
        Route::get('/repository/{id}/download', [KnowledgeRepositoryController::class, 'download'])->name('repository.download');
    });

/*
|--------------------------------------------------------------------------
| INSTITUTION ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.user.type:institution'])
    ->prefix('institution')
    ->name('institution.')
    ->group(function () {
        Route::get('/dashboard', [InstitutionDashboardController::class, 'index'])->name('dashboard');
        
        // problems
        Route::get('/problems', [ProblemController::class, 'index'])->name('problems.index');
        Route::get('/problems/create', [ProblemController::class, 'create'])->name('problems.create');
        Route::post('/problems', [ProblemController::class, 'store'])->name('problems.store');
        Route::get('/problems/{id}', [ProblemController::class, 'show'])->name('problems.show');
        Route::get('/problems/{id}/edit', [ProblemController::class, 'edit'])->name('problems.edit');
        Route::put('/problems/{id}', [ProblemController::class, 'update'])->name('problems.update');
        Route::delete('/problems/{id}', [ProblemController::class, 'destroy'])->name('problems.destroy');
        
        // applications
        Route::get('/applications', [ApplicationReviewController::class, 'index'])->name('applications.index');
        Route::get('/applications/{id}', [ApplicationReviewController::class, 'show'])->name('applications.show');
        Route::get('/applications/{id}/review', [ApplicationReviewController::class, 'review'])->name('applications.review');
        Route::post('/applications/{id}/accept', [ApplicationReviewController::class, 'accept'])->name('applications.accept');
        Route::post('/applications/{id}/reject', [ApplicationReviewController::class, 'reject'])->name('applications.reject');
        
        // projects
        Route::get('/projects', [ProjectManagementController::class, 'index'])->name('projects.index');
        Route::get('/projects/{id}', [ProjectManagementController::class, 'show'])->name('projects.show');
        Route::get('/projects/{id}/manage', [ProjectManagementController::class, 'manage'])->name('projects.manage');
        Route::post('/projects/{id}/milestone', [ProjectManagementController::class, 'addMilestone'])->name('projects.add-milestone');
        Route::put('/projects/{id}/milestone/{milestoneId}', [ProjectManagementController::class, 'updateMilestone'])->name('projects.update-milestone');
        
        // reviews
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/create/{projectId}', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::get('/reviews/{id}', [ReviewController::class, 'show'])->name('reviews.show');
        Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
        
        // profile
        Route::get('/profile', [InstitutionProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [InstitutionProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [InstitutionProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [InstitutionProfileController::class, 'updatePassword'])->name('profile.password.update');
    });

/*
|--------------------------------------------------------------------------
| NOTIFICATION ROUTES (shared)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('notifications')
    ->name('notifications.')
    ->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });