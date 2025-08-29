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
            'password' => 'required|string|min:6',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('customers.index')
                ->with('message', 'Customer added successfully!');
        } catch (\Exception $e) {
            return redirect()->route('customers.index')
                ->with('error', 'Failed to add customer! ' . $e->getMessage());
        }
    }
}
