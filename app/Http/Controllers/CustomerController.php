<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function showCustomers()
    {
        $customers = User::all(); // or User::get();
        return view('customers.index', compact('customers'));
    }
}
