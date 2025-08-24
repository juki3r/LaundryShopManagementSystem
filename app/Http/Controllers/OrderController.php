<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;


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


    public function index()
    {
        if (auth()->user()->role === 'admin') {
            $orders = \App\Models\Order::latest()->get(); // admin sees all
        } else {
            $orders = auth()->user()->orders()->latest()->get(); // customer sees own
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
            $orders = Order::latest()->get(); // admin sees all
        } else {
            $orders = $user->orders()->latest()->get(); // customer sees own
        }

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }
}
