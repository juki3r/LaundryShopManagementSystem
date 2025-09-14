<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\RiderController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');





Route::middleware('auth')->group(function () {
    Route::resource('orders', OrderController::class);
});

Route::middleware(['auth'])->group(function () {

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::post('/orders/{customer_id}', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');


    // Customers Control
    Route::get('/customers', [CustomerController::class, 'showCustomers'])->name('show.customers');
    Route::post('registercustomer', [CustomerController::class, 'registercustomer'])->name('register.customer');
    Route::delete('/customers/{id}', [CustomerController::class, 'delete'])->name('delete.customer');

    // Riders Control
    Route::get('/riders', [RiderController::class, 'showRiders'])->name('show.riders');
    Route::post('/riders/register', [RiderController::class, 'register'])->name('register.rider');
    Route::delete('/riders/{id}', [RiderController::class, 'delete'])->name('delete.rider');


    Route::get('/reports', [OrderController::class, 'showreports'])->name('reports.index');



    Route::get('/feedbacks', [FeedbackController::class, 'Feedbacks'])->name('feedbacks.index');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
