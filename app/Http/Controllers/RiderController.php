<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RiderController extends Controller
{
    // public function showRiders(Request $request)
    // {
    //     $query = User::where('role', '=', 'rider');

    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('name', 'like', "%{$search}%")
    //                 ->orWhere('username', 'like', "%{$search}%")
    //                 // address & contact_number are in orders, so we filter via relation
    //                 ->orWhereHas('orders', function ($q2) use ($search) {
    //                     $q2->where('address', 'like', "%{$search}%")
    //                         ->orWhere('contact_number', 'like', "%{$search}%");
    //                 });
    //         });
    //     }

    //     // Load latest order for each user
    //     $riders = $query->with(['orders' => function ($q) {
    //         $q->latest()->limit(1);
    //     }])->orderBy('name')->paginate(10);

    //     // Transform data to include latest order info
    //     $ridersTransformed = $riders->map(function ($user) {
    //         $latestOrder = $user->orders->first();
    //         return [
    //             'id' => $user->id,
    //             'name' => $user->name,
    //             'username' => $user->username,
    //             'address' => $latestOrder->address ?? '',
    //             'contact_number' => $latestOrder->contact_number ?? '',
    //         ];
    //     });

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'riders' => $ridersTransformed,
    //             'pagination' => [
    //                 'current_page' => $riders->currentPage(),
    //                 'last_page' => $riders->lastPage(),
    //             ]
    //         ]);
    //     }

    //     return view('riders.index', [
    //         'riders' => $ridersTransformed,
    //         'pagination' => $riders
    //     ]);
    // }

    // public function showRiders(Request $request)
    // {
    //     $query = User::where('role', 'rider');

    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('name', 'like', "%{$search}%")
    //                 ->orWhere('username', 'like', "%{$search}%")
    //                 ->orWhereHas('orders', function ($q2) use ($search) {
    //                     $q2->where('address', 'like', "%{$search}%")
    //                         ->orWhere('contact_number', 'like', "%{$search}%");
    //                 });
    //         });
    //     }

    //     // Load orders relation
    //     $riders = $query->with(['orders' => function ($q) {
    //         $q->latest()->limit(1); // latest order for display
    //     }])->orderBy('name')->paginate(5);

    //     // Transform data to include latest order info + commission
    //     $ridersTransformed = $riders->map(function ($user) {
    //         $latestOrder = $user->orders->first();

    //         // Count delivered orders for this rider
    //         $deliveredCount = $user->orders()->where('delivered', 'Yes')->count();

    //         return [
    //             'id' => $user->id,
    //             'name' => $user->name,
    //             'username' => $user->username,
    //             'address' => $latestOrder->address ?? '',
    //             'contact_number' => $latestOrder->contact_number ?? '',
    //             'commission' => $deliveredCount * 0.3, // 0.3 per delivered order
    //             'delivered_count' => $deliveredCount,
    //         ];
    //     });

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'riders' => $ridersTransformed,
    //             'pagination' => [
    //                 'current_page' => $riders->currentPage(),
    //                 'last_page' => $riders->lastPage(),
    //             ]
    //         ]);
    //     }

    //     return view('riders.index', [
    //         'riders' => $ridersTransformed,
    //         'pagination' => $riders
    //     ]);
    // }


    public function showRiders(Request $request)
    {
        $query = User::where('role', 'rider');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhereHas('orders', function ($q2) use ($search) {
                        $q2->where('address', 'like', "%{$search}%")
                            ->orWhere('contact_number', 'like', "%{$search}%");
                    });
            });
        }

        $riders = $query
            ->with(['orders' => function ($q) {
                $q->latest()->limit(1); // latest order
            }])
            ->withCount(['orders as delivered_count' => function ($q) {
                $q->where('delivered', 'Yes');
            }])
            ->orderBy('name')
            ->paginate(5);

        $ridersTransformed = $riders->map(function ($user) {
            $latestOrder = $user->orders->first();

            return [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'address' => $latestOrder->address ?? '',
                'contact_number' => $latestOrder->contact_number ?? '',
                'commission' => $user->delivered_count * 30,
                'delivered_count' => $user->delivered_count,
            ];
        });

        if (request()->ajax()) {
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




    public function register(Request $request)
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
