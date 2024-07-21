<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\OutputController;
use App\Http\Controllers\LegerController;
use App\Http\Controllers\RefferenceController;

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

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Manage Users
    Route::prefix('/users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/create', [UserController::class, 'store']);
        Route::patch('/update/{id}', [UserController::class, 'update']);
        Route::delete('/delete/{id}', [UserController::class, 'destroy']);
        Route::get('/{id}', [UserController::class, 'show']);
    });

    // Auth
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'getDashboardData']);

    // Input Aset
    Route::get('data/list-ruas', [RefferenceController::class, 'getRuasList']);
    Route::get('data/tipe-aset', [RefferenceController::class, 'getTipeAsetList']);
    Route::post('data/input-ruas', [InputController::class, 'inputRuas']);
    Route::post('data/input-aset', [InputController::class, 'inputAset']);

    // Leger
    Route::post('leger/generate', [LegerController::class, 'generate']);
    Route::post('leger/get-data/{kode_leger}', [LegerController::class, 'getData']);
});

// Output Aset
Route::get('data/aset/{type}', [OutputController::class, 'getAset']);

Route::post('/login', [AuthController::class, 'login']);
