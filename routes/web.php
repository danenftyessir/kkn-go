<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| web routes
|--------------------------------------------------------------------------
*/

// home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// authentication routes
Route::middleware('guest')->group(function () {
    // login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // register
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::get('/register/student', [RegisterController::class, 'showStudentRegisterForm'])->name('register.student');
    Route::post('/register/student', [RegisterController::class, 'registerStudent']);
    Route::get('/register/institution', [RegisterController::class, 'showInstitutionRegisterForm'])->name('register.institution');
    Route::post('/register/institution', [RegisterController::class, 'registerInstitution']);
    
    // forgot password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    // reset password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// student routes
Route::prefix('student')->name('student.')->middleware(['auth', 'user.type:student'])->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard.index');
    })->name('dashboard');
    
    // TODO: tambahkan route lainnya di fase berikutnya
});

// institution routes
Route::prefix('institution')->name('institution.')->middleware(['auth', 'user.type:institution'])->group(function () {
    Route::get('/dashboard', function () {
        return view('institution.dashboard.index');
    })->name('dashboard');
    
    // TODO: tambahkan route lainnya di fase berikutnya
});

// admin routes (untuk fase selanjutnya)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'user.type:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Admin Dashboard - Coming Soon';
    })->name('dashboard');
});