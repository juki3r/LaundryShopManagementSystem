<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;


class OrderController extends Controller
{
    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address'        => 'required|string|max:500',
            'service_type'   => 'required|in:Delivery,Pickup',
            'order_date'     => 'required|date',
        ]);

        // Check if there is already a pending order for this customer
        $pendingOrder = Order::where('user_id', $id)
            ->where('amount_status', 'Pending')
            ->first();

        if ($pendingOrder) {
            return redirect()->route('orders.index')
                ->with('error', 'This customer still has a pending payment.');
        }

        // Convert order_date to PHT
        $orderDate = Carbon::now('Asia/Manila');

        // Create order
        $order = Order::create([
            'user_id'        => $id,
            'customer_name'  => $request->customer_name,
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
            'service_type'   => $request->service_type,
            'weight'         => 0,
            'laundry_status' => 'Waiting',
            'claimed'        => 'No',
            'delivered'      => 'No',
            'total'          => 0,
            'amount_status'  => 'Pending',
            'order_date'     => $orderDate,
        ]);


        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully!');
    }


    // public function storeApi(Request $request)
    // {
    //     // Validate input
    //     $request->validate([
    //         'customer_name'  => 'required|string|max:255',
    //         'contact_number' => 'required|string|max:20',
    //         'address'        => 'required|string|max:500',
    //         'service_type'   => 'required|in:Delivery,Pick-up',
    //         'order_date'     => 'required|date',
    //     ]);

    //     // Convert order_date to PHT
    //     $orderDate = Carbon::parse($request->order_date)
    //         ->setTimezone('Asia/Manila');

    //     // Create order for the authenticated user
    //     $order = Auth::user()->orders()->create([
    //         'customer_name'  => $request->customer_name,
    //         'contact_number' => $request->contact_number,
    //         'address'        => $request->address,
    //         'service_type'   => $request->service_type,
    //         'weight'         => 0,
    //         'laundry_status' => 'Waiting',
    //         'claimed'        => 'No',
    //         'delivered'      => 'No',
    //         'total'          => 0,
    //         'amount_status'  => 'Pending',
    //         'order_date'     => $orderDate,
    //     ]);

    //     // Return JSON response
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Inquire placed successfully!, Please check your dashboard for an update. Thanks',
    //         'order'   => $order,
    //     ], 201);
    // }

    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address'        => 'required|string|max:500',
            'service_type'   => 'required|in:Delivery,Pickup',
            'order_date'     => 'required|date',
        ]);

        $user = Auth::user();

        // Check if there is already a pending order for this user
        $pendingOrder = $user->orders()
            ->where('amount_status', 'Pending')
            ->first();

        if ($pendingOrder) {
            return response()->json([
                'success' => false,
                'message' => 'You still have a pending payment. Please settle it before creating a new order.',
            ], 422); // Unprocessable Entity
        }

        // Convert order_date to PHT
        $orderDate = Carbon::parse($request->order_date)
            ->setTimezone('Asia/Manila');

        // Create order
        // $order = $user->orders()->create([
        //     'customer_name'  => $request->customer_name,
        //     'contact_number' => $request->contact_number,
        //     'address'        => $request->address,
        //     'service_type'   => $request->service_type,
        //     'weight'         => 0,
        //     'laundry_status' => 'Waiting',
        //     'claimed'        => 'No',
        //     'delivered'      => 'No',
        //     'total'          => 0,
        //     'amount_status'  => 'Pending',
        //     'order_date'     => $orderDate,
        // ]);
        try {
            $order = $user->orders()->create([
                'user_id' => $user->id,
                'customer_name'  => $request->customer_name,
                'contact_number' => $request->contact_number,
                'address'        => $request->address,
                'service_type'   => $request->service_type,
                'weight'         => 0,
                'laundry_status' => 'Waiting',
                'claimed'        => 'No',
                'delivered'      => 'No',
                'total'          => 0,
                'amount_status'  => 'Pending',
                'order_date'     => $orderDate,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'user' => $user,
            'orderDate' => $orderDate,
            'validate' => $validated,
            // 'message' => 'Inquire placed successfully! Please check your dashboard for an update. Thanks',
            // 'order'   => $order,
        ], 201);
    }



    // public function index(Request $request)
    // {
    //     $search = $request->input('search');

    //     if (auth()->user()->role === 'admin') {
    //         $orders = \App\Models\Order::query()
    //             ->when($search, function ($query, $search) {
    //                 $query->where('customer_name', 'like', "%{$search}%")
    //                     ->orWhere('service_type', 'like', "%{$search}%")
    //                     ->orWhere('laundry_status', 'like', "%{$search}%");
    //             })
    //             ->latest()
    //             ->paginate(5)
    //             ->withQueryString();
    //     } else {
    //         $orders = auth()->user()->orders()
    //             ->when($search, function ($query, $search) {
    //                 $query->where('service_type', 'like', "%{$search}%")
    //                     ->orWhere('laundry_status', 'like', "%{$search}%");
    //             })
    //             ->latest()
    //             ->paginate(10)
    //             ->withQueryString();
    //     }
    //     // fetch all riders
    //     $riders = \App\Models\User::where('role', 'rider')->get();

    //     if ($request->ajax()) {
    //         return view('orders.partials.orders-table', compact('orders', 'riders'))->render();
    //     }

    //     return view('orders.index', compact('orders', 'search', 'riders'));
    // }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $amountStatus = $request->input('amount_status');

        if (auth()->user()->role === 'admin') {
            $orders = \App\Models\Order::query()
                ->where('delivered', 'No')
                ->when($search, function ($query, $search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('service_type', 'like', "%{$search}%");
                })
                ->when($amountStatus, function ($query, $amountStatus) {
                    $query->where('amount_status', $amountStatus);
                })
                ->latest()
                ->paginate(5)
                ->withQueryString();
        } else {
            $orders = auth()->user()->orders()
                ->where('delivered', 'No')
                ->when($search, function ($query, $search) {
                    $query->where('service_type', 'like', "%{$search}%")
                        ->orWhere('laundry_status', 'like', "%{$search}%");
                })
                ->when($amountStatus, function ($query, $amountStatus) {
                    $query->where('amount_status', $amountStatus);
                })
                ->latest()
                ->paginate(10)
                ->withQueryString();
        }

        $riders = \App\Models\User::where('role', 'rider')->get();

        if ($request->ajax()) {
            return view('orders.partials.orders-table', compact('orders', 'riders'))->render();
        }

        return view('orders.index', compact('orders', 'search', 'riders', 'amountStatus'));
    }








    //API
    public function indexApi(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            // Admin sees all orders with laundry_status = Waiting
            $orders = Order::where('laundry_status', 'Waiting')->latest()->get();
        } else if ($user->role === 'customer') {
            // Fetch only this user's delivered orders
            $orders = Order::where('user_id', $user->id)
                ->where('delivered', 'No')
                ->latest()
                ->get();
        }

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }

    //API
    public function indexApiHistory(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'customer') {
            // Fetch only this user's delivered orders
            $orders = Order::where('user_id', $user->id)
                ->where('delivered', 'Yes')
                ->latest()
                ->get();
        } else {
            $orders = collect(); // return empty if not customer
        }

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'weight' => 'required|numeric|min:1',
            'total' => 'required|numeric|min:0',
            'amount_status' => 'required|in:Pending,Paid',
            'laundry_status' => 'required|in:Waiting,Processing,Completed',
            'claimed'   => 'required',
        ]);

        $order->update([
            'weight' => $request->weight,
            'total' => $request->total,
            'amount_status' => $request->amount_status,
            'laundry_status' => $request->laundry_status,
            'claimed' => $request->claimed,
            'rider' => $request->rider,
            'claimed' => $request->claimed,

        ]);

        // Return JSON for AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully!',
                'order' => $order
            ]);
        }

        // Fallback for traditional requests
        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
    }

    public function riderOrders()
    {
        $user = auth()->user();

        // only riders can fetch this
        if ($user->role !== 'rider') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $orders = \App\Models\Order::where('rider', $user->name)
            ->where('delivered', 'No') // only active orders
            ->where('Laundry_status', 'Completed') // only active orders
            ->with('user') // if you want customer details
            ->latest()
            ->get();

        $orders_completed = \App\Models\Order::where('rider', $user->name)
            ->where('delivered', 'Yes') // only active orders
            ->where('Laundry_status', 'Completed') // only active orders
            ->with('user') // if you want customer details
            ->latest()
            ->get();

        return response()->json([
            'count' => $orders->count(),
            'orders' => $orders,
            'count_completed' => $orders_completed->count(),
            'orders_completed' => $orders_completed
        ]);
    }



    public function markDelivered(Request $request, \App\Models\Order $order)
    {
        $user = $request->user();

        // Ensure only the assigned rider can mark as delivered
        if ($order->rider !== $user->name) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->delivered = 'Yes';
        $order->delivery_date = now('Asia/Manila'); // set Manila time
        $order->amount_status = 'Paid';
        $order->claimed = 'Yes';
        $order->save();

        return response()->json([
            'message' => 'Order marked as delivered.',
            'order' => $order,
        ]);
    }



    // CustomerController.php
    // public function storeorder(Request $request)
    // {
    //     $request->validate([
    //         'customer_name' => 'required|string|max:255',
    //         'contact_number' => 'required|string|max:11',
    //         'address' => 'required|string|max:500',
    //         'service_type' => 'required|string',
    //         'weight' => 'required|numeric|min:1',
    //         'amount_status' => 'required|in:Pending,Paid',
    //         'laundry_status' => 'required|in:Waiting,Processing,Completed',
    //     ]);

    //     Order::create([
    //         'customer_name' => $request->customer_name,
    //         'contact_number' => $request->contact_number,
    //         'address' => $request->address,
    //         'service_type' => $request->service_type,
    //         'weight' => $request->weight,
    //         'total' => $request->weight <= 6 ? 130 : 130 + ($request->weight - 6) * 20,
    //         'amount_status' => $request->amount_status,
    //         'laundry_status' => $request->laundry_status,
    //         'order_date' => now(),
    //     ]);

    //     return response()->json(['success' => true]);
    // }


    public function showreports()
    {
        return "Reports";
    }

    //API
    public function profile(Request $request)
    {
        $user = $request->user()->load('orders');

        return response()->json($user);
    }
}
