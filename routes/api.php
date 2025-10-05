<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\BrowseProblemsController;
use App\Http\Controllers\Auth\ValidationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| route untuk API endpoints
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// public api untuk get regencies berdasarkan province
Route::get('/regencies/{provinceId}', [BrowseProblemsController::class, 'getRegencies'])
     ->middleware('throttle:60,1')
     ->name('api.public.regencies');

// public api untuk validasi step registration (tanpa auth)
Route::post('/validate/student/step', [ValidationController::class, 'validateStudentStep'])
     ->middleware('throttle:60,1')
     ->name('api.public.validate.student.step');

Route::post('/validate/institution/step', [ValidationController::class, 'validateInstitutionStep'])
     ->middleware('throttle:60,1')
     ->name('api.public.validate.institution.step');