<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            Orders Management
        </h2>
    </x-slot>

    <div class="container mt-4">

        {{-- AJAX & Session Messages --}}
        <div id="ajaxMessageContainer" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Top Bar --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search Orders..." value="{{ $search ?? '' }}">
                </div>
            </div>
        </div>

        @if(Auth::user()->role === 'admin')
        {{-- Admin Table --}}
        <div class="card shadow-sm rounded-3">
            <div class="card-body">
                <div id="ordersTableContainer">
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
                                        'Pending' => 'table-warning',
                                        'Paid' => 'table-success',
                                        default => '',
                                    };
                                @endphp
                                <tr id="orderRow{{ $order->id }}" class="{{ $rowClass }}">
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->contact_number }}</td>
                                    <td>{{ $order->address }}</td>
                                    <td class="weight">{{ $order->weight }}</td>
                                    <td class="total">{{ $order->total }}</td>
                                    <td class="amount_status">{{ $order->amount_status }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y h:i A') }}</td>
                                    <td>{{ $order->service_type }}</td>
                                    <td class="laundry_status">{{ $order->laundry_status }}</td>
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
                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Order Modals --}}
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
                                <input type="number" min="1" class="form-control weight-input" data-order-id="{{ $order->id }}" value="{{ $order->weight }}">
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
        <div class="card shadow-sm rounded-3">
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center">
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
                <div class="d-flex justify-content-center mt-3">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- jQuery & Bootstrap --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script>
        $(document).ready(function() {

            // Auto-calc for weight/total
            $(document).on('input', '.weight-input', function() {
                const orderId = $(this).data('order-id');
                const weight = parseFloat($(this).val()) || 0;
                const total = weight <= 6 ? 130 : 130 + (weight - 6) * 20;
                $('#total' + orderId).val(total.toFixed(2));
                const row = $('#orderRow' + orderId);
                row.find('.weight').text(weight);
                row.find('.total').text(total.toFixed(2));
            });

            // Show message + reload
            function showMessage(message, type = 'success') {
                const msgId = 'msg' + Date.now();
                const html = `
                    <div id="${msgId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
                $('#ajaxMessageContainer').append(html);
                setTimeout(() => { $('#' + msgId).alert('close'); }, 4000);
                setTimeout(() => { location.reload(); }, 500);
            }

            // Edit order AJAX
            $(document).on('submit', '.edit-order-form', function(e){
                e.preventDefault();
                const form = $(this);
                const orderId = form.data('order-id');
                const weight = parseFloat(form.find('.weight-input').val()) || 0;
                const total = parseFloat(form.find('.total-input').val()) || 0;
                const amount_status = form.find('.amount_status-input').val();
                const laundry_status = form.find('.laundry_status-input').val();

                $.ajax({
                    url: '/orders/' + orderId,
                    method: 'PUT',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        weight,
                        total,
                        amount_status,
                        laundry_status
                    },
                    success: function(res){
                        const modalEl = document.getElementById('editOrderModal' + orderId);
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) { modal.hide(); $('.modal-backdrop').remove(); }
                        showMessage('Order updated successfully!', 'success');
                    },
                    error: function(err){
                        showMessage('Update failed. Please try again.', 'danger');
                    }
                });
            });

            // Live search
            $('#searchInput').on('keyup', function() {
                const query = $(this).val();
                $.ajax({
                    url: "{{ route('orders.index') }}",
                    data: { search: query },
                    success: function(data) {
                        $('#ordersTableContainer').html(data);
                    }
                });
            });

        });
    </script>
</x-app-layout>
