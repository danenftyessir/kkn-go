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
    
    // browse problems dengan URL /student/browse-problems
    Route::prefix('browse-problems')->name('browse-problems.')->group(function () {
        Route::get('/', [BrowseProblemsController::class, 'index'])->name('index');
        Route::get('/{id}', [BrowseProblemsController::class, 'show'])->name('detail');
    });
    
    // browse problems alternatif dengan URL /student/problems (untuk backward compatibility)
    Route::prefix('problems')->name('problems.')->group(function () {
        Route::get('/', [BrowseProblemsController::class, 'index'])->name('index');
        Route::get('/{id}', [BrowseProblemsController::class, 'show'])->name('show');
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
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::get('/latest', [NotificationController::class, 'getLatest'])->name('latest');
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('read-all');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
});

/*
|--------------------------------------------------------------------------
| Public Institution Profile
|--------------------------------------------------------------------------
*/

Route::get('/institution/{id}', [InstitutionProfileController::class, 'showPublic'])
    ->where('id', '[0-9]+')
    ->name('institution.public');

/**
 * DEBUGGING HELPER SCRIPT
 * 
 * Taruh file ini di: routes/web.php (temporary route untuk debugging)
 * Akses via: /institution/problems/{id}/debug-form
 * 
 * Script ini akan:
 * 1. Cek data problem dari database
 * 2. Cek validasi rules
 * 3. Cek fillable di model
 * 4. Simulate form submission
 */

// tambahkan route ini SEMENTARA di routes/web.php untuk debugging
Route::get('/institution/problems/{id}/debug-form', function($id) {
    $problem = \App\Models\Problem::with('images')->findOrFail($id);
    
    // 1. CEK DATA PROBLEM
    echo "<h2>1. DATA PROBLEM DARI DATABASE</h2>";
    echo "<pre>";
    echo "ID: " . $problem->id . "\n";
    echo "Title: " . $problem->title . "\n";
    echo "Status: " . $problem->status . "\n";
    echo "Province ID: " . $problem->province_id . "\n";
    echo "Regency ID: " . $problem->regency_id . "\n";
    echo "Village: " . ($problem->village ?? 'NULL') . "\n";
    echo "Detailed Location: " . ($problem->detailed_location ?? 'NULL') . "\n";
    echo "Background: " . ($problem->background ?? 'NULL') . "\n";
    echo "Objectives: " . ($problem->objectives ?? 'NULL') . "\n";
    echo "Scope: " . ($problem->scope ?? 'NULL') . "\n";
    echo "Images Count: " . $problem->images->count() . "\n";
    echo "</pre>";
    
    // 2. CEK FILLABLE MODEL
    echo "<h2>2. FILLABLE FIELDS DI MODEL</h2>";
    echo "<pre>";
    print_r($problem->getFillable());
    echo "</pre>";
    
    // 3. CEK SDG CATEGORIES
    echo "<h2>3. SDG CATEGORIES</h2>";
    echo "<pre>";
    $sdg = is_array($problem->sdg_categories) ? $problem->sdg_categories : json_decode($problem->sdg_categories, true);
    print_r($sdg);
    echo "</pre>";
    
    // 4. CEK REQUIRED SKILLS
    echo "<h2>4. REQUIRED SKILLS</h2>";
    echo "<pre>";
    $skills = is_array($problem->required_skills) ? $problem->required_skills : json_decode($problem->required_skills, true);
    print_r($skills);
    echo "</pre>";
    
    // 5. SIMULATE FORM DATA
    echo "<h2>5. SIMULATE FORM DATA (Copy ini untuk test Postman)</h2>";
    $formData = [
        'title' => $problem->title . ' (EDITED)',
        'description' => $problem->description,
        'background' => $problem->background,
        'objectives' => $problem->objectives,
        'scope' => $problem->scope,
        'province_id' => $problem->province_id,
        'regency_id' => $problem->regency_id,
        'village' => $problem->village,
        'detailed_location' => $problem->detailed_location,
        'latitude' => $problem->latitude,
        'longitude' => $problem->longitude,
        'sdg_categories' => is_array($problem->sdg_categories) ? $problem->sdg_categories : json_decode($problem->sdg_categories, true),
        'required_students' => $problem->required_students,
        'required_skills' => is_array($problem->required_skills) ? $problem->required_skills : json_decode($problem->required_skills, true),
        'required_majors' => is_array($problem->required_majors) ? $problem->required_majors : json_decode($problem->required_majors, true),
        'start_date' => $problem->start_date->format('Y-m-d'),
        'end_date' => $problem->end_date->format('Y-m-d'),
        'application_deadline' => $problem->application_deadline->format('Y-m-d'),
        'duration_months' => $problem->duration_months,
        'difficulty_level' => $problem->difficulty_level,
        'status' => $problem->status,
        'expected_outcomes' => $problem->expected_outcomes,
        'deliverables' => is_array($problem->deliverables) ? $problem->deliverables : json_decode($problem->deliverables, true),
        'facilities_provided' => is_array($problem->facilities_provided) ? $problem->facilities_provided : json_decode($problem->facilities_provided, true),
    ];
    echo "<textarea style='width:100%; height:300px;'>";
    echo json_encode($formData, JSON_PRETTY_PRINT);
    echo "</textarea>";
    
    // 6. TEST VALIDATION
    echo "<h2>6. TEST VALIDATION</h2>";
    $validator = \Illuminate\Support\Facades\Validator::make($formData, [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'background' => 'nullable|string',
        'objectives' => 'nullable|string',
        'scope' => 'nullable|string',
        'province_id' => 'required|integer|exists:provinces,id',
        'regency_id' => 'required|integer|exists:regencies,id',
        'village' => 'nullable|string|max:255',
        'detailed_location' => 'nullable|string',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        'sdg_categories' => 'required|array|min:1',
        'sdg_categories.*' => 'integer|between:1,17',
        'required_students' => 'required|integer|min:1',
        'required_skills' => 'required|array|min:1',
        'required_majors' => 'nullable|array',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'application_deadline' => 'required|date|before:start_date',
        'duration_months' => 'required|integer|min:1',
        'difficulty_level' => 'required|in:beginner,intermediate,advanced',
        'status' => 'required|in:draft,open,in_progress,completed,closed',
        'expected_outcomes' => 'nullable|string',
        'deliverables' => 'nullable|array',
        'facilities_provided' => 'nullable|array',
    ]);
    
    if ($validator->fails()) {
        echo "<pre style='color:red;'>";
        echo "❌ VALIDATION FAILED:\n";
        print_r($validator->errors()->toArray());
        echo "</pre>";
    } else {
        echo "<pre style='color:green;'>";
        echo "✅ VALIDATION PASSED!";
        echo "</pre>";
    }
    
    // 7. CHECK MISSING FIELDS
    echo "<h2>7. CHECK MISSING FIELDS IN FORM</h2>";
    $modelFillable = $problem->getFillable();
    $formFields = array_keys($formData);
    $missingInForm = array_diff($modelFillable, $formFields);
    
    if (count($missingInForm) > 0) {
        echo "<pre style='color:orange;'>";
        echo "⚠️ Fields in Model but NOT in Form:\n";
        print_r($missingInForm);
        echo "\nNote: Ini mungkin OK jika field auto-generated (created_at, institution_id, dll)";
        echo "</pre>";
    } else {
        echo "<pre style='color:green;'>";
        echo "✅ All necessary fields present!";
        echo "</pre>";
    }
    
    // 8. LOG TEST
    echo "<h2>8. RECENT LOGS (Last 50 lines from laravel.log)</h2>";
    $logPath = storage_path('logs/laravel.log');
    if (file_exists($logPath)) {
        $lines = file($logPath);
        $lastLines = array_slice($lines, -50);
        echo "<textarea style='width:100%; height:300px;'>";
        echo implode('', $lastLines);
        echo "</textarea>";
    } else {
        echo "<p style='color:red;'>Log file not found</p>";
    }
    
})->middleware(['auth', 'check.user.type:institution']);