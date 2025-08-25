<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class OrderController extends Controller
{
    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'   => 'required|string|max:255',
            'contact_number'  => 'required|string|max:20',
            'address'         => 'required|string|max:500',
            'service_type'    => 'required|in:Delivery,Pick-up',
            'weight'          => 'required|numeric|min:0',
            'total'           => 'required|numeric|min:0',
            'order_date'      => 'required|date',
        ]);

        auth()->user()->orders()->create([
            'customer_name'   => $request->customer_name,
            'contact_number'  => $request->contact_number,
            'address'         => $request->address,
            'service_type'    => $request->service_type,
            'weight'          => $request->weight,
            'laundry_status'  => 'Waiting', // default
            'claimed'         => 'No',
            'delivered'       => 'No',
            'total'           => $request->total,
            'amount_status'   => 'Pending',
            'order_date'      => $request->order_date,
        ]);


        return redirect()->route('orders.create')
            ->with('success', 'Order created successfully!');
    }

    public function storeApi(Request $request)
    {
        // Validate input
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address'        => 'required|string|max:500',
            'service_type'   => 'required|in:Delivery,Pick-up',
            // 'weight'         => 'required|numeric|min:0',
            // 'total'          => 'required|numeric|min:0',
            'order_date'     => 'required|date',
        ]);

        // Create order for the authenticated user
        $order = Auth::user()->orders()->create([
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
            'order_date'     => $request->order_date,
        ]);

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Inquire placed successfully!, Please check your dashboard for an update. Thanks',
            'order'   => $order,
        ], 201);
    }


    public function index()
    {
        if (auth()->user()->role === 'admin') {
            // Admin sees all orders, paginated
            $orders = \App\Models\Order::latest()->paginate(10);
        } else {
            // Customer sees own orders, paginated
            $orders = auth()->user()->orders()->latest()->paginate(10);
        }

        return view('orders.index', compact('orders'));
    }


    public function approve(Order $order)
    {
        $order->update([
            'laundry_status' => 'Approved',
        ]);

        return redirect()->route('orders.index')->with('success', 'Order approved successfully.');
    }

    public function deny(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')->with('error', 'Order deleted.');
    }




    //API
    public function indexApi(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            // Admin sees all orders with laundry_status = Waiting
            $orders = Order::where('laundry_status', 'Waiting')->latest()->get();
        } else {
            // Customer sees their own orders with laundry_status = Waiting
            $orders = $user->orders()->where('laundry_status', 'Waiting')->latest()->get();
        }

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }
}
