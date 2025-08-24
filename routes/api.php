<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\OrderController;

Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'API working']);
});


Route::post('login', [AuthenticatedSessionController::class, 'storeapi']);
Route::post('register', [RegisteredUserController::class, 'registerapi']);

Route::middleware('auth:sanctum')->get('/orders', [OrderController::class, 'indexApi']);
Route::middleware('auth:sanctum')->post('/save-expo-token', [RegisteredUserController::class, 'saveExpoToken']);
Route::middleware('auth:sanctum')->post('/orders', [OrderController::class, 'storeApi']);


Route::middleware('auth:sanctum')->group(function () {});
