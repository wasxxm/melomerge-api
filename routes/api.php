<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\JamSessionController;
use Illuminate\Http\Request;
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

/**
 * Public routes
 */
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/jam-sessions/public', [JamSessionController::class, 'index']);

/**
 * Protected routes
 */
 Route::group(['middleware' => ['auth:sanctum']], function () {

     // get all genres
     Route::get('/genres', [GenreController::class, 'index']);

     // logout
     Route::get('/logout', [AuthController::class, 'logout']);

     Route::get('/jam-sessions', [JamSessionController::class, 'index']);
     Route::post('/jam-sessions', [JamSessionController::class, 'store']);
     Route::get('/jam-sessions/{id}', [JamSessionController::class, 'show']);
     Route::put('/jam-sessions/{id}', [JamSessionController::class, 'update']);
     Route::delete('/jam-sessions/{id}', [JamSessionController::class, 'destroy']);
 });
