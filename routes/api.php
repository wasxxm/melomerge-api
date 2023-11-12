<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\JamSessionController;
use App\Http\Controllers\RoleController;
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
     // get all roles
     Route::get('/roles', [RoleController::class, 'index']);
     // get all roles and instruments
     Route::get('/roles-and-instruments', [CommonController::class, 'roles_and_instruments']);

     // logout
     Route::get('/logout', [AuthController::class, 'logout']);

     // jam sessions
     Route::get('/jam-sessions', [JamSessionController::class, 'index']);
     Route::post('/jam-sessions', [JamSessionController::class, 'store']);
     Route::post('/jam-sessions/{jam_session}/join', [JamSessionController::class, 'join']);
     Route::post('/jam-sessions/{jam_session}/leave', [JamSessionController::class, 'leave']);
     Route::middleware(['checkJamSessionOwner'])->group(function () {
         Route::get('/jam-sessions/{jam_session}', [JamSessionController::class, 'show']);
         Route::put('/jam-sessions/{jam_session}', [JamSessionController::class, 'update']);
         Route::delete('/jam-sessions/{jam_session}', [JamSessionController::class, 'destroy']);
     });
 });
