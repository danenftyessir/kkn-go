<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student\BrowseProblemsController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Institution\DashboardController as InstitutionDashboardController;
use App\Http\Controllers\Institution\ProfileController as InstitutionProfileController;

// halaman publik
Route::get('/', [HomeController::class, 'index'])->name('home');

// authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::get('/register/student', [RegisterController::class, 'showStudentRegisterForm'])->name('register.student');
    Route::get('/register/institution', [RegisterController::class, 'showInstitutionRegisterForm'])->name('register.institution');
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
    
    // profile routes
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [StudentProfileController::class, 'update'])->name('profile.update');
    
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
    
    // TODO: knowledge repository routes
    // Route::get('/knowledge', [KnowledgeRepositoryController::class, 'index'])->name('knowledge.index');
    // Route::get('/knowledge/{id}', [KnowledgeRepositoryController::class, 'show'])->name('knowledge.show');
});

// public profile routes (tanpa auth)
Route::get('/student/profile/{username}', [StudentProfileController::class, 'publicProfile'])->name('student.profile.public');

// institution routes
Route::middleware(['auth', 'user.type:institution'])->prefix('institution')->name('institution.')->group(function () {
    
    // dashboard
    Route::get('/dashboard', [InstitutionDashboardController::class, 'index'])->name('dashboard');
    
    // profile routes
    Route::get('/profile', [InstitutionProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [InstitutionProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [InstitutionProfileController::class, 'update'])->name('profile.update');
    
    // TODO: problem management routes
    // Route::get('/problems', [InstitutionProblemController::class, 'index'])->name('problems.index');
    // Route::get('/problems/create', [InstitutionProblemController::class, 'create'])->name('problems.create');
    // Route::post('/problems', [InstitutionProblemController::class, 'store'])->name('problems.store');
    // Route::get('/problems/{id}/edit', [InstitutionProblemController::class, 'edit'])->name('problems.edit');
    // Route::patch('/problems/{id}', [InstitutionProblemController::class, 'update'])->name('problems.update');
    // Route::delete('/problems/{id}', [InstitutionProblemController::class, 'destroy'])->name('problems.destroy');
    
    // TODO: application review routes
    // Route::get('/applications', [InstitutionApplicationController::class, 'index'])->name('applications.index');
    // Route::get('/applications/{id}', [InstitutionApplicationController::class, 'show'])->name('applications.show');
    // Route::patch('/applications/{id}/accept', [InstitutionApplicationController::class, 'accept'])->name('applications.accept');
    // Route::patch('/applications/{id}/reject', [InstitutionApplicationController::class, 'reject'])->name('applications.reject');
});

// public institution profile (tanpa auth)
Route::get('/institution/profile/{username}', [InstitutionProfileController::class, 'publicProfile'])->name('institution.profile.public');

// API routes untuk AJAX calls
Route::prefix('api')->middleware('throttle:60,1')->group(function () {
    Route::get('/regencies/{provinceId}', [BrowseProblemsController::class, 'getRegencies']);
});