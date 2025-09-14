@if(Auth::user()->role === 'admin')
{{-- Admin Table --}}
<div class="card shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Customer</th>
                        <th>Contact #</th>
                        <th>Address</th>
                        <th>Weight (kg)</th>
                        <th>Total (PHP)</th>
                        <th>Payment</th>
                        <th>Date of Order</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $rowClass = match($order->amount_status) {
                                'Pending' => 'table-warning',   // Yellow
                                'Paid' => 'table-success',      // Green
                                default => '',                   // Default
                            };
                        @endphp
                        <tr id="orderRow{{ $order->id }}" class="{{ $rowClass }}">
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->contact_number }}</td>
                            <td>{{ $order->address }}</td>
                            <td class="weight text-center">{{ $order->weight }}</td>
                            <td class="total text-center">{{ $order->total }}</td>
                            <td class="amount_status text-center">{{ $order->amount_status }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y h:i A') }}</td>
                            <td class="text-center">{{ $order->service_type }}</td>
                            <td class="laundry_status text-center">{{ $order->laundry_status }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editOrderModal{{ $order->id }}">
                                    <i class="bi bi-pencil-square"></i> Edit
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
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@else
{{-- Non-admin Table --}}
<div class="card shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Order ID</th>
                        <th>Service</th>
                        <th>Weight (kg)</th>
                        <th>Total Amount (PHP)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="text-center">{{ $order->id }}</td>
                            <td class="text-center">{{ $order->service_type }}</td>
                            <td class="text-center">{{ $order->weight }}</td>
                            <td class="text-center">{{ $order->total }}</td>
                            <td class="text-center">{{ $order->laundry_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endif
