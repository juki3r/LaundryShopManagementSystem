<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller
{
    public function showCustomers()
    {
        $customers = User::where('role', '!=', 'admin')->paginate(10);
        return view('customers.index', compact('customers'));
    }
}
