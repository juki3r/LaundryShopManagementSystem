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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
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
                'message' => 'Failed to add customer! ' . $e->getMessage()
            ], 500);
        }
    }
}
