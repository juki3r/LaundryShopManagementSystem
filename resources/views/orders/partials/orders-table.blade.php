@if(Auth::user()->role === 'admin')
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-hover" id="ordersTable">
            <thead class="table-light">
                <tr>
                    <th>Customer</th>
                    <th>Contact #</th>
                    <th>Address</th>
                    <th>Weight</th>
                    <th>Total amount</th>
                    <th>Payment</th>
                    <th>Date of order</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr id="orderRow{{ $order->id }}">
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->contact_number }}</td>
                    <td>{{ $order->address }}</td>
                    <td class="weight">{{ $order->weight }}</td>
                    <td class="total">{{ $order->total }}</td>
                    <td class="amount_status">{{ $order->amount_status }}</td>
                    <td class="total">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y h:i A') }}</td>
                    <td>{{ $order->service_type }}</td>
                    <td class="laundry_status">
                        @php
                            $statusClass = match($order->laundry_status) {
                                'Waiting' => 'bg-warning text-dark',
                                'Processing' => 'bg-primary text-light',
                                'Completed' => 'bg-success text-light',
                                default => 'bg-secondary text-light',
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $order->laundry_status }}</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editOrderModal{{ $order->id }}">
                            Edit
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">No orders found.</td>
                </tr>
                @endforelse

            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>



@else
{{-- Non-admin table --}}
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Order ID</th>
                    <th>Service</th>
                    <th>Weight</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->service_type }}</td>
                    <td>{{ $order->weight }}</td>
                    <td>{{ $order->total }}</td>
                    <td>{{ $order->laundry_status }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endif
