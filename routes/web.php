<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student\BrowseProblemsController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;

// halaman publik
Route::get('/', [HomeController::class, 'index'])->name('home');

// authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::get('/register/student', [RegisterController::class, 'showStudentForm'])->name('register.student');
    Route::get('/register/institution', [RegisterController::class, 'showInstitutionForm'])->name('register.institution');
    Route::post('/register/student', [RegisterController::class, 'registerStudent'])->name('register.student.submit');
    Route::post('/register/institution', [RegisterController::class, 'registerInstitution'])->name('register.institution.submit');
    
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// student routes
Route::middleware(['auth', 'user.type:student'])->prefix('student')->name('student.')->group(function () {
    
    // dashboard
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // browse problems
    Route::get('/browse-problems', [BrowseProblemsController::class, 'index'])->name('browse-problems');
    Route::get('/problems/{id}', [BrowseProblemsController::class, 'show'])->name('problems.show');
    
    // TODO: application routes
    // Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    // Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('applications.show');
    // Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
    // Route::patch('/applications/{id}', [ApplicationController::class, 'update'])->name('applications.update');
    
    // TODO: project routes
    // Route::get('/projects', [MyProjectsController::class, 'index'])->name('projects.index');
    // Route::get('/projects/{id}', [MyProjectsController::class, 'show'])->name('projects.show');
    
    // TODO: portfolio routes
    // Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
    // Route::get('/portfolio/public/{username}', [PortfolioController::class, 'public'])->name('portfolio.public');
    
    // TODO: knowledge repository routes
    // Route::get('/knowledge', [KnowledgeRepositoryController::class, 'index'])->name('knowledge.index');
    // Route::get('/knowledge/{id}', [KnowledgeRepositoryController::class, 'show'])->name('knowledge.show');
    
    // profile routes
    // Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    // Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// TODO: institution routes
// Route::middleware(['auth', 'user.type:institution'])->prefix('institution')->name('institution.')->group(function () {
//     Route::get('/dashboard', [InstitutionDashboardController::class, 'index'])->name('dashboard');
//     // ... institution routes lainnya
// });

// API routes untuk AJAX calls
Route::prefix('api')->middleware('throttle:60,1')->group(function () {
    Route::get('/regencies/{provinceId}', [BrowseProblemsController::class, 'getRegencies']);
});