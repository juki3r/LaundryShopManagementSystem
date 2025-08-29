@if($orders->count())
<table class="table table-bordered table-hover" id="ordersTable">
    <thead class="table-light">
        <tr>
            <th>Customer</th>
            <th>Contact #</th>
            <th>Address</th>
            <th>Weight</th>
            <th>Total amount</th>
            <th>Payment</th>
            <th>Service</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr id="orderRow{{ $order->id }}">
            <td>{{ $order->customer_name }}</td>
            <td>{{ $order->contact_number }}</td>
            <td>{{ $order->address }}</td>
            <td class="weight">{{ $order->weight }}</td>
            <td class="total">{{ $order->total }}</td>
            <td class="amount_status">{{ $order->amount_status }}</td>
            <td>{{ $order->service_type }}</td>
            <td class="laundry_status">{{ $order->laundry_status }}</td>
            <td>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editOrderModal{{ $order->id }}">
                    Edit
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>

@else
<p class="text-center">No orders found.</p>
@endif
