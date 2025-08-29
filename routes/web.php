<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::middleware('auth')->group(function () {
    Route::resource('orders', OrderController::class);
});

Route::middleware(['auth'])->group(function () {

    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');

    // Customers Control
    Route::get('/customers', [CustomerController::class, 'showCustomers'])->name('show.customers');
    Route::post('registercustomer', [CustomerController::class, 'registercustomer'])->name('register.customer');
    Route::delete('/customers/{id}', [CustomerController::class, 'delete'])->name('delete.customer');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
