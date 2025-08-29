@if(Auth::user()->role === 'admin')
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
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
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->contact_number }}</td>
                            <td>{{ $order->address }}</td>
                            <td>{{ $order->weight }}</td>
                            <td>{{ $order->total }}</td>
                            <td>{{ $order->amount_status }}</td>
                            <td>{{ $order->service_type }}</td>
                            <td>{{ $order->laundry_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No orders found.</td>
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
