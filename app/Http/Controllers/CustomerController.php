<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function showCustomers()
    {
        $customers = User::where('role', '!=', 'admin')->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function registercustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required',
            // 'expo_token' => 'nullable|string', // <-- add this
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            // 'expo_token' => $request->expo_token, // <-- save token here
        ]);
        $customers = User::where('role', '!=', 'admin')->paginate(10);
        if (!$user) {
            return view('customers.index', compact('customers'))->with("error", "Customer added failed!");
        }


        return view('customers.index', compact('customers'))->with("message", "Customer added successfully!");
    }
}
