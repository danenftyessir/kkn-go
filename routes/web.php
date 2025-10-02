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
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Institution\DashboardController as InstitutionDashboardController;
use App\Http\Controllers\Institution\ProfileController as InstitutionProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Auth Routes
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
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'user.type:student'])->prefix('student')->name('student.')->group(function () {
    
    // dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // browse problems
    Route::get('/browse-problems', [BrowseProblemsController::class, 'index'])->name('browse-problems');
    Route::get('/problems/{id}', [BrowseProblemsController::class, 'show'])->name('problems.show');
    
    // ajax endpoints untuk filter
    Route::get('/api/regencies/{provinceId}', [BrowseProblemsController::class, 'getRegencies'])->name('api.regencies');
    
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
    
    // profile routes
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [StudentProfileController::class, 'update'])->name('profile.update');
    
    // TODO: my projects routes
    // Route::get('/projects', [MyProjectsController::class, 'index'])->name('projects.index');
    // Route::get('/projects/{id}', [MyProjectsController::class, 'show'])->name('projects.show');
    
    // TODO: portfolio routes
    // Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
    // Route::get('/portfolio/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');
    
    // TODO: knowledge repository routes
    // Route::get('/knowledge', [KnowledgeRepositoryController::class, 'index'])->name('knowledge.index');
    // Route::get('/knowledge/{id}', [KnowledgeRepositoryController::class, 'show'])->name('knowledge.show');
});

// public student profile (tanpa auth)
Route::get('/student/profile/{username}', [StudentProfileController::class, 'publicProfile'])->name('student.profile.public');

/*
|--------------------------------------------------------------------------
| Institution Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'user.type:institution'])->prefix('institution')->name('institution.')->group(function () {
    
    // dashboard
    Route::get('/dashboard', [InstitutionDashboardController::class, 'index'])->name('dashboard');
    
    // profile routes
    Route::get('/profile', [InstitutionProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [InstitutionProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [InstitutionProfileController::class, 'update'])->name('profile.update');
    
    // TODO: problems/projects management
    // Route::resource('problems', ProblemController::class);
    
    // TODO: applications review
    // Route::get('/applications', [ApplicationReviewController::class, 'index'])->name('applications.index');
    // Route::get('/applications/{id}', [ApplicationReviewController::class, 'show'])->name('applications.show');
    // Route::patch('/applications/{id}/review', [ApplicationReviewController::class, 'review'])->name('applications.review');
    
    // TODO: project management
    // Route::get('/projects', [ProjectManagementController::class, 'index'])->name('projects.index');
    // Route::get('/projects/{id}', [ProjectManagementController::class, 'show'])->name('projects.show');
});

// public institution profile (tanpa auth)
Route::get('/institution/profile/{id}', [InstitutionProfileController::class, 'publicProfile'])->name('institution.profile.public');

/*
|--------------------------------------------------------------------------
| Admin Routes (TODO)
|--------------------------------------------------------------------------
*/

// Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
//     Route::resource('users', UserController::class);
//     Route::resource('verifications', VerificationController::class);
// });

/*
|--------------------------------------------------------------------------
| Dev Routes (Development Only)
|--------------------------------------------------------------------------
*/

if (app()->environment('local')) {
    Route::get('/dev/login', function () {
        return view('dev.login');
    })->name('dev.login');
}