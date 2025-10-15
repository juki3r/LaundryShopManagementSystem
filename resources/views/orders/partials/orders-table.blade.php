@if(Auth::user()->role === 'admin')
<div class="card shadow-sm rounded-3 mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Customer Name</th>
                        <th>Contact #</th>
                        <th>Address</th>
                        <th>Weight (kg)</th>
                        <th>Total (PHP)</th>
                        <th>Payment status</th>
                        <th>Date of Order</th>
                        <th>Service type</th>
                        <th>Laundry Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $rowClass = match($order->amount_status) {
                                'Pending' => 'table-warning',
                                'Paid' => 'table-success',
                                default => '',
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
                            <td class="laundry_status text-center">
                                @if($order->laundry_status == 'Processing' || $order->laundry_status == 'Waiting')
                                    Processing
                                @else
                                    {{ $order->service_type == 'Pickup' ? 'Ready for Pick Up' : 'Ready for Delivery' }}
                                @endif
                            </td>

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

{{-- Edit Modals --}}
@foreach($orders as $order)
<div class="modal fade" id="editOrderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content edit-order-form p-3 rounded-3" data-order-id="{{ $order->id }}">
            @csrf
            @method('PUT')
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Order #{{ $order->id }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Weight (kg)</label>
                    <input type="number" min="6" class="form-control weight-input" data-order-id="{{ $order->id }}" value="{{ $order->weight }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Total (PHP)</label>
                    <input type="text" class="form-control total-input" id="total{{ $order->id }}" value="{{ $order->total }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Amount Status</label>
                    <select class="form-select amount_status-input">
                        <option value="Pending" {{ $order->amount_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Paid" {{ $order->amount_status === 'Paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Laundry Status</label>
                    <select class="form-select laundry_status-input">
                        <option value="Waiting" {{ $order->laundry_status === 'Waiting' ? 'selected' : '' }}>Waiting</option>
                        <option value="Processing" {{ $order->laundry_status === 'Processing' ? 'selected' : '' }}>Processing</option>
                        <option value="Completed" {{ $order->laundry_status === 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Claimed</label>
                    <select class="form-select claimed-input">
                        <option value="No" {{ $order->calimed === 'No' ? 'selected' : '' }}>No</option>
                        <option value="Yes" {{ $order->calimed === 'Yes' ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Assign Rider</label>
                    <select class="form-select rider_id-input">
                        <option value="">None</option>
                        @foreach($riders as $rider)
                            <option value="{{ $rider->name }}" {{ $order->rider == $rider->name ? 'selected' : '' }}>
                                {{ $rider->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
            </div>
        </form>
    </div>
</div>
@endforeach

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
