<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {

    $today = Carbon::today();
    $startOfMonth = Carbon::now()->startOfMonth();
    $startOfYear = Carbon::now()->startOfYear();

    $metrics = ['today' => $today, 'month' => $startOfMonth, 'year' => $startOfYear];

    $data = [];

    foreach ($metrics as $key => $startDate) {
        $data[$key] = [
            'profit' => DB::table('orders')->where('created_at', '>=', $startDate)->sum('total'),
            'claimed' => DB::table('orders')->where('created_at', '>=', $startDate)->where('claimed', 'Yes')->count(),
            'orders' => DB::table('orders')->where('created_at', '>=', $startDate)->count(),
            'delivered' => DB::table('orders')->where('created_at', '>=', $startDate)->where('delivered', 'Yes')->count(),
        ];
    }

    return view('dashboard', ['data' => $data]);
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

    //Reports
    Route::get('/reports', [ReportsController::class, 'report'])->name('reports.index');
    Route::get('/reports/receipt/{order}', [ReportsController::class, 'receipt'])->name('reports.receipt');




    Route::get('/feedbacks', [FeedbackController::class, 'Feedbacks'])->name('feedbacks.index');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//Blast notification
// Can use as broadcast to all prefer admin
Route::get('/notify-blast', [NotificationController::class, 'sendBlast']);
//This is for 1 notification
Route::get('/send-to-one/{id}', [NotificationController::class, 'sendToOne']);

Route::get('/test-fcm', function () {
    $service = new \App\Services\FirebaseService;
    return $service->sendNotification(
        '<PUT_A_REAL_FCM_TOKEN_HERE>',
        'Test Title',
        'Hello from Laravel!'
    );
});


require __DIR__ . '/auth.php';
