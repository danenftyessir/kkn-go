<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Institution\ProfileController as InstitutionProfileController;

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
    Route::post('/register/student', [RegisterController::class, 'registerStudent'])->name('register.student.store');
    Route::get('/register/institution', [RegisterController::class, 'showInstitutionRegisterForm'])->name('register.institution');
    Route::post('/register/institution', [RegisterController::class, 'registerInstitution'])->name('register.institution.store');
    
    // forgot password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    // reset password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// email verification routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');
});

// logout route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// student routes
Route::prefix('student')->name('student.')->middleware(['auth', 'user.type:student'])->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard.index');
    })->name('dashboard');
    
    // profile routes
    Route::get('/profile', [StudentProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [StudentProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [StudentProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/privacy', [StudentProfileController::class, 'updatePrivacy'])->name('profile.privacy');
    Route::delete('/profile/photo', [StudentProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    
    // TODO: tambahkan route lainnya di fase berikutnya
});

// institution routes
Route::prefix('institution')->name('institution.')->middleware(['auth', 'user.type:institution'])->group(function () {
    Route::get('/dashboard', function () {
        return view('institution.dashboard.index');
    })->name('dashboard');
    
    // profile routes
    Route::get('/profile', [InstitutionProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [InstitutionProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [InstitutionProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [InstitutionProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile/logo', [InstitutionProfileController::class, 'deleteLogo'])->name('profile.logo.delete');
    Route::post('/profile/verification-document', [InstitutionProfileController::class, 'uploadVerificationDocument'])->name('profile.verification.upload');
    
    // TODO: tambahkan route lainnya di fase berikutnya
});

// public profile routes (accessible without auth)
Route::get('/portfolio/{username}', [StudentProfileController::class, 'show'])->name('student.profile.public');
Route::get('/institution/{username}', [InstitutionProfileController::class, 'show'])->name('institution.profile.public');

// admin routes (untuk fase selanjutnya)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'user.type:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Admin Dashboard - Coming Soon';
    })->name('dashboard');
});

// api route untuk check verification status (untuk auto-refresh)
Route::middleware('auth')->get('/api/check-verification', function () {
    return response()->json([
        'verified' => auth()->user()->email_verified_at !== null
    ]);
});