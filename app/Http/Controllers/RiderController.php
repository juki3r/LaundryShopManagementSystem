<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    public function showRiders(Request $request)
    {
        $query = User::where('role', '=', 'rider');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    // address & contact_number are in orders, so we filter via relation
                    ->orWhereHas('orders', function ($q2) use ($search) {
                        $q2->where('address', 'like', "%{$search}%")
                            ->orWhere('contact_number', 'like', "%{$search}%");
                    });
            });
        }

        // Load latest order for each user
        $riders = $query->with(['orders' => function ($q) {
            $q->latest()->limit(1);
        }])->orderBy('name')->paginate(10);

        // Transform data to include latest order info
        $ridersTransformed = $riders->map(function ($user) {
            $latestOrder = $user->orders->first();
            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'address' => $latestOrder->address ?? '',
                'contact_number' => $latestOrder->contact_number ?? '',
            ];
        });

        if ($request->ajax()) {
            return response()->json([
                'riders' => $ridersTransformed,
                'pagination' => [
                    'current_page' => $riders->currentPage(),
                    'last_page' => $riders->lastPage(),
                ]
            ]);
        }

        return view('riders.index', [
            'riders' => $ridersTransformed,
            'pagination' => $riders
        ]);
    }


    public function registerrider(Request $request)
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
                'role' => 'rider',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rider added successfully!',
                'rider' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add rider!'
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $rider = User::where('role', 'rider')->findOrFail($id);
            $rider->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rider deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Rider.'
            ], 500);
        }
    }
}
