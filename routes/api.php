<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// TODO: tambahkan API routes lainnya sesuai kebutuhan