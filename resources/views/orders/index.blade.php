<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Orders Management') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        {{-- Success Message --}}
        <div id="ajaxMessageContainer" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>

        {{-- Search Input --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Orders..." value="{{ $search ?? '' }}">
            </div>
        </div>

        {{-- Orders Table --}}
        <div id="ordersTableContainer">
            @include('orders.partials.orders-table', ['orders' => $orders])
        </div>

        {{-- Modals --}}
        @foreach($orders as $order)
        <div class="modal fade" id="editOrderModal{{ $order->id }}" tabindex="-1" aria-labelledby="editOrderModalLabel{{ $order->id }}" aria-hidden="true">
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

    </div>

    {{-- jQuery & Bootstrap --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {

        // Auto-calculate total
        // $(document).on('input', '.weight-input', function() {
        //     const orderId = $(this).data('order-id');
        //     const weight = parseFloat($(this).val()) || 0;
        //     const total = weight <= 6 ? 130 : 130 + (weight - 6) * 20;
        //     $('#total' + orderId).val(total.toFixed(2));
        // });
        // Auto-calculate total and update table live
        $(document).on('input', '.weight-input', function() {
            const orderId = $(this).data('order-id');
            const weight = parseFloat($(this).val()) || 0;
            const total = weight <= 6 ? 130 : 130 + (weight - 6) * 20;

            // Update modal total
            $('#total' + orderId).val(total.toFixed(2));

            // Update table row total live
            const row = $('#orderRow' + orderId);
            row.find('.weight').text(weight);
            row.find('.total').text(total.toFixed(2));
        });


        // Show message
        function showMessage(message, type = 'success') {
            const msgId = 'msg' + Date.now();
            const html = `
                <div id="${msgId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
            $('#ajaxMessageContainer').append(html);
            setTimeout(() => { $('#' + msgId).alert('close'); }, 4000);
        }

        // AJAX update
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
                    // Update row in table without reload
                    const row = $('#orderRow' + orderId);
                    row.find('.weight').text(weight);
                    row.find('.total').text(total);
                    row.find('.amount_status').text(amount_status);
                    row.find('.laundry_status').text(laundry_status);

                    // Close modal
                    const modalEl = document.getElementById('editOrderModal' + orderId);
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl); // <- ensures instance exists
                    modal.hide();

                    // Show success message
                    showMessage('Order updated successfully!', 'success');
                },
                error: function(err){
                    showMessage('Update failed. Please try again.', 'danger');
                }
            });
        });

        // Optional: Live search AJAX (if needed)
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
