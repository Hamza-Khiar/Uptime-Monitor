<?php

use App\Http\Controllers\MonitorController;
use App\Http\Controllers\UserController;
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

Route::get('/validated_user/{id}', [UserController::class, 'validateUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [MonitorController::class, 'index']);
    Route::post('/newMonitor',[MonitorController::class,'newMonitor']);
});
