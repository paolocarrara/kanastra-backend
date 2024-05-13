<?php

use App\Http\Controllers\FilesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/files/upload', [FilesController::class, 'upload']);
Route::get('/files/test', [FilesController::class, 'test']);
// Route::post('/files/process', [FilesController::class, 'process']);
