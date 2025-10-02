<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\BrowseProblemsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| route untuk API endpoints
| TODO: akan diisi di fase-fase berikutnya
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/regencies/{provinceId}', [BrowseProblemsController::class, 'getRegencies'])
     ->middleware('throttle:60,1')
     ->name('api.public.regencies');
     
// TODO: tambahkan API routes lainnya sesuai kebutuhan