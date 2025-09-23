<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware('api')->get('/ping', function () {
    return response()->json(['message' => 'API working']);
});


Route::post('login', [AuthenticatedSessionController::class, 'storeapi']);
Route::post('register', [RegisteredUserController::class, 'registerapi']);

Route::middleware('auth:sanctum')->get('/orders', [OrderController::class, 'indexApi']);
Route::middleware('auth:sanctum')->get('/orders/history', [OrderController::class, 'indexApiHistory']);
Route::middleware('auth:sanctum')->post('/save-expo-token', [RegisteredUserController::class, 'saveExpoToken']);
Route::middleware('auth:sanctum')->post('/orders', [OrderController::class, 'storeApi']);
Route::middleware('auth:sanctum')->get('/rider/orders', [OrderController::class, 'riderOrders']);
Route::middleware('auth:sanctum')->put('/rider/orders/{order}/deliver', [OrderController::class, 'markDelivered']);
Route::middleware('auth:sanctum')->post('/feedback', [FeedbackController::class, 'store']);
Route::middleware('auth:sanctum')->get('/feedbacks', [FeedbackController::class, 'myFeedback']);
Route::middleware('auth:sanctum')->get('/profile', [OrderController::class, 'profile']);





Route::middleware('auth:sanctum')->group(function () {});
