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
    
    // browse problems - DIPERBAIKI: tambahkan route tanpa suffix
    Route::get('/problems', [BrowseProblemsController::class, 'index'])->name('problems.index');
    Route::get('/problems/{id}', [BrowseProblemsController::class, 'show'])->name('problems.show');
    
    // alias untuk konsistensi nama route
    Route::get('/browse-problems', [BrowseProblemsController::class, 'index'])->name('browse-problems.index');
    Route::get('/browse-problems/{id}', [BrowseProblemsController::class, 'show'])->name('browse-problems.show');
    
    // TAMBAHAN: route alias tanpa suffix untuk backward compatibility
    Route::get('/browse-problems-redirect', function() {
        return redirect()->route('student.browse-problems.index');
    })->name('browse-problems');

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
        Route::get('/{projectId}/reports/create', [MyProjectsController::class, 'createReport'])->name('reports.create');
        Route::post('/{projectId}/reports', [MyProjectsController::class, 'storeReport'])->name('reports.store');
        Route::get('/{projectId}/final-report/create', [MyProjectsController::class, 'createFinalReport'])->name('final-report.create');
        Route::post('/{projectId}/final-report', [MyProjectsController::class, 'storeFinalReport'])->name('final-report.store');
    });
    
    // portfolio
    Route::prefix('portfolio')->name('portfolio.')->group(function () {
        Route::get('/', [PortfolioController::class, 'index'])->name('index');
        Route::get('/public/{username}', [PortfolioController::class, 'public'])->name('public');
    });
    
    // profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [StudentProfileController::class, 'index'])->name('index');
        Route::get('/edit', [StudentProfileController::class, 'edit'])->name('edit');
        Route::put('/', [StudentProfileController::class, 'update'])->name('update');
        Route::patch('/', [StudentProfileController::class, 'update'])->name('update.patch'); // alias untuk PATCH
        Route::put('/password', [StudentProfileController::class, 'updatePassword'])->name('password.update');
        Route::patch('/password', [StudentProfileController::class, 'updatePassword'])->name('password.update.patch'); // alias untuk PATCH
        Route::get('/public/{username}', [StudentProfileController::class, 'public'])->name('public');
    });
    
    // knowledge repository
    Route::prefix('repository')->name('repository.')->group(function () {
        Route::get('/', [KnowledgeRepositoryController::class, 'index'])->name('index');
        Route::get('/{id}', [KnowledgeRepositoryController::class, 'show'])->name('show');
        Route::get('/{id}/download', [KnowledgeRepositoryController::class, 'download'])->name('download');
        Route::post('/{id}/bookmark', [KnowledgeRepositoryController::class, 'toggleBookmark'])->name('bookmark');
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
    
    // problem management
    Route::prefix('problems')->name('problems.')->group(function () {
        Route::get('/', [ProblemController::class, 'index'])->name('index');
        Route::get('/create', [ProblemController::class, 'create'])->name('create');
        Route::post('/', [ProblemController::class, 'store'])->name('store');
        Route::get('/{id}', [ProblemController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProblemController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProblemController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProblemController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/publish', [ProblemController::class, 'publish'])->name('publish');
        Route::post('/{id}/close', [ProblemController::class, 'close'])->name('close');
    });
    
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
        Route::get('/{id}/manage', [ProjectManagementController::class, 'manage'])->name('manage');
        Route::post('/{id}/complete', [ProjectManagementController::class, 'complete'])->name('complete');
    });
    
    // reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/create/{projectId}', [ReviewController::class, 'create'])->name('create');
        Route::post('/', [ReviewController::class, 'store'])->name('store');
        Route::get('/{id}', [ReviewController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ReviewController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ReviewController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReviewController::class, 'destroy'])->name('destroy');
    });
    
    // profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [InstitutionProfileController::class, 'index'])->name('index');
        Route::get('/edit', [InstitutionProfileController::class, 'edit'])->name('edit');
        Route::put('/', [InstitutionProfileController::class, 'update'])->name('update');
        Route::put('/password', [InstitutionProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('/public/{slug}', [InstitutionProfileController::class, 'public'])->name('public');
    });
});

/*
|--------------------------------------------------------------------------
| api routes untuk ajax requests
|--------------------------------------------------------------------------
*/

Route::prefix('api')->name('api.')->group(function () {
    // public api (tidak perlu auth)
    Route::get('/provinces', function() {
        return response()->json(\App\Models\Province::orderBy('name')->get());
    })->name('provinces');
    
    Route::get('/regencies/{provinceId}', function($provinceId) {
        return response()->json(\App\Models\Regency::where('province_id', $provinceId)->orderBy('name')->get());
    })->name('regencies');
    
    Route::get('/universities', function() {
        return response()->json(\App\Models\University::orderBy('name')->get());
    })->name('universities');
    
    // authenticated api
    Route::middleware('auth')->group(function () {
        Route::post('/problems/{id}/view', function($id) {
            $problem = \App\Models\Problem::findOrFail($id);
            $problem->increment('views_count');
            return response()->json(['success' => true]);
        })->name('problems.view');
    });
});

// TAMBAHKAN DI AKHIR FILE routes/web.php UNTUK DEBUG
// HAPUS SETELAH MASALAH SELESAI!

if (app()->environment('local')) {
    // route untuk test password hash
    Route::get('/test-password', function() {
        $testPassword = 'password123';
        $hashed = Hash::make($testPassword);
        
        return response()->json([
            'original_password' => $testPassword,
            'hashed' => $hashed,
            'check_result' => Hash::check($testPassword, $hashed),
            'bcrypt_rounds' => config('hashing.bcrypt.rounds', 10),
        ]);
    });

    // route untuk test user pertama
    Route::get('/test-user', function() {
        $student = \App\Models\Student::with('user')->first();
        
        if (!$student) {
            return response()->json(['error' => 'Tidak ada student. Jalankan seeder dulu!']);
        }

        $user = $student->user;
        $testPassword = 'password123';
        
        return response()->json([
            'user_info' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'user_type' => $user->user_type,
                'is_active' => $user->is_active,
                'email_verified_at' => $user->email_verified_at,
            ],
            'password_hash' => $user->password,
            'password_check_result' => Hash::check($testPassword, $user->password),
            'test_credentials' => [
                'email' => $user->email,
                'username' => $user->username,
                'password' => $testPassword,
            ],
        ]);
    });

    // route untuk test auth attempt langsung
    Route::get('/test-auth/{username}', function($username) {
        $user = \App\Models\User::where('username', $username)->first();
        
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan']);
        }

        $testPassword = 'password123';
        
        // test dengan email
        $emailAttempt = Auth::attempt([
            'email' => $user->email,
            'password' => $testPassword,
        ]);

        // test dengan username
        $usernameAttempt = Auth::attempt([
            'username' => $user->username,
            'password' => $testPassword,
        ]);

        return response()->json([
            'user' => [
                'email' => $user->email,
                'username' => $user->username,
                'is_active' => $user->is_active,
                'email_verified' => $user->email_verified_at ? 'yes' : 'no',
            ],
            'password_check' => Hash::check($testPassword, $user->password),
            'auth_attempt_email' => $emailAttempt,
            'auth_attempt_username' => $usernameAttempt,
            'currently_authed' => Auth::check(),
            'authed_user_id' => Auth::id(),
        ]);
    });

    // route untuk list semua user
    Route::get('/list-users', function() {
        $students = \App\Models\User::where('user_type', 'student')->limit(5)->get();
        $institutions = \App\Models\User::where('user_type', 'institution')->limit(5)->get();
        
        return response()->json([
            'students' => $students->map(fn($u) => [
                'email' => $u->email,
                'username' => $u->username,
                'is_active' => $u->is_active,
                'verified' => $u->email_verified_at ? 'yes' : 'no',
            ]),
            'institutions' => $institutions->map(fn($u) => [
                'email' => $u->email,
                'username' => $u->username,
                'is_active' => $u->is_active,
                'verified' => $u->email_verified_at ? 'yes' : 'no',
            ]),
            'total_users' => \App\Models\User::count(),
        ]);
    });
}