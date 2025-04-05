<?php

use App\Http\Controllers\Api\Schedule;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::get('/profile', [UserController::class, 'profile']);
Route::get('/schedule', [Schedule::class, 'getSchedule']);
Route::middleware('auth:api')->group(function () {
    Route::put('/update-profile', [UserController::class, 'updateProfile']);
});
