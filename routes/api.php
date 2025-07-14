<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MuseumController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CotizacionGrupalController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\UserController;
use App\Models\Discount;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

});
Route::apiResource('rooms', RoomController::class);
// Route::apiResource('posts', PostController::class);
Route::apiResource('museums', MuseumController::class);
Route::apiResource('categories',CategoryController::class);
Route::apiResource('discounts',DiscountController::class);
Route::apiResource('users', UserController::class);
Route::get('/cotizaciones/{unique_id}', [CotizacionGrupalController::class,'show']);
Route::post('/cotizaciones', [CotizacionGrupalController::class,'store']);

