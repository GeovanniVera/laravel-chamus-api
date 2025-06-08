<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MuseumController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RoomController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::apiResource('rooms', RoomController::class);
Route::apiResource('posts', PostController::class);
Route::apiResource('museums', MuseumController::class);
