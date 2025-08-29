<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function showCustomers(Request $request)
    {
        $query = User::where('role', '!=', 'admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('name')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'customers' => $customers->items(),
                'pagination' => [
                    'current_page' => $customers->currentPage(),
                    'last_page' => $customers->lastPage(),
                ]
            ]);
        }

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

    public function delete($id)
    {
        try {
            $customer = User::where('role', 'customer')->findOrFail($id);
            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer.'
            ], 500);
        }
    }
}
