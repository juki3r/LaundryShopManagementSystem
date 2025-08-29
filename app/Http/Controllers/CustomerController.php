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
        // Simple checks
        if (User::where('username', $request->username)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Username already taken!'
            ], 400);
        }

        if (strlen($request->password) < 8) {
            return response()->json([
                'success' => false,
                'message' => 'Password must be at least 8 characters!'
            ], 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'customer',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer added successfully!',
                'customer' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add customer!'
            ], 500);
        }
    }
}
