<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Orders Management') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        {{-- Success Message --}}
        <div id="ajaxMessageContainer" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>

        {{-- Top Bar --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Orders..." value="{{ $search ?? '' }}">
            </div>
            <div class="col-md-4">
                {{-- 🚀 Walk-in Order Button --}}
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#walkInOrderModal">
                    + Walk-in Order
                </button>
            </div>
        </div>

        {{-- Orders Table --}}
        <div id="ordersTableContainer">
            @include('orders.partials.orders-table', ['orders' => $orders])
        </div>

        {{-- Edit Order Modals --}}
        @foreach($orders as $order)
        <div class="modal fade" id="editOrderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content edit-order-form" data-order-id="{{ $order->id }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Order #{{ $order->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" min="1" class="form-control weight-input" data-order-id="{{ $order->id }}" value="{{ $order->weight }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total (PHP)</label>
                            <input type="text" class="form-control total-input" id="total{{ $order->id }}" value="{{ $order->total }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount Status</label>
                            <select class="form-select amount_status-input">
                                <option value="Pending" {{ $order->amount_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Paid" {{ $order->amount_status === 'Paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Laundry Status</label>
                            <select class="form-select laundry_status-input">
                                <option value="Waiting" {{ $order->laundry_status === 'Waiting' ? 'selected' : '' }}>Waiting</option>
                                <option value="Processing" {{ $order->laundry_status === 'Processing' ? 'selected' : '' }}>Processing</option>
                                <option value="Completed" {{ $order->laundry_status === 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach

        {{-- 🚀 Walk-In Order Modal --}}
        <div class="modal fade" id="walkInOrderModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="walkInOrderForm" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Walk-in Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Service Type</label>
                            <select name="service_type" class="form-select" required>
                                <option value="Wash">Wash</option>
                                <option value="Wash & Fold">Wash & Fold</option>
                                <option value="Dry Clean">Dry Clean</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" name="weight" min="1" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Amount Status</label>
                            <select name="amount_status" class="form-select" required>
                                <option value="Pending">Pending</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Laundry Status</label>
                            <select name="laundry_status" class="form-select" required>
                                <option value="Waiting">Waiting</option>
                                <option value="Processing">Processing</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- jQuery & Bootstrap --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {

        // Auto-calc for edit modal
        $(document).on('input', '.weight-input', function() {
            const orderId = $(this).data('order-id');
            const weight = parseFloat($(this).val()) || 0;
            const total = weight <= 6 ? 130 : 130 + (weight - 6) * 20;
            $('#total' + orderId).val(total.toFixed(2));
            const row = $('#orderRow' + orderId);
            row.find('.weight').text(weight);
            row.find('.total').text(total.toFixed(2));
        });

        // Message + reload
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

        //  Walk-in order AJAX
        $('#walkInOrderForm').on('submit', function(e){
            e.preventDefault();
            const form = $(this);
            $.ajax({
                url: "{{ route('orders.walkin.store') }}",
                method: 'POST',
                data: form.serialize(),
                success: function(res){
                    const modalEl = document.getElementById('walkInOrderModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) { modal.hide(); $('.modal-backdrop').remove(); }
                    showMessage(res.message);
                },
                error: function(err){
                    showMessage('Failed to create walk-in order.', 'danger');
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
