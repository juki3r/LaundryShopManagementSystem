<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Orders Management') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        {{-- Search Input --}}
        <div id="ajaxMessageContainer" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>

        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Orders..." value="{{ $search ?? '' }}">
            </div>
        </div>
        
        {{-- Orders Table --}}
        <div id="ordersTableContainer">
            @include('orders.partials.orders-table', ['orders' => $orders])
        </div>
    </div>

    {{-- jQuery & Bootstrap --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {

        // ===============================
        // LIVE SEARCH + PAGINATION
        // ===============================
        function fetchOrders(query = '', page = 1) {
            $.ajax({
                url: "{{ route('orders.index') }}",
                type: 'GET',
                data: { search: query, page: page },
                success: function(data) {
                    $('#ordersTableContainer').html(data);
                }
            });
        }

        // Live search
        $('#searchInput').on('keyup', function() {
            let query = $(this).val();
            fetchOrders(query);
        });

        // Pagination links
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];
            let query = $('#searchInput').val();
            fetchOrders(query, page);
        });

        // ===============================
        // MODAL EDIT FUNCTIONALITY
        // ===============================

        // Auto-calculate total
        $(document).on('input', '.weight-input', function() {
            const orderId = $(this).data('order-id');
            const weight = parseFloat($(this).val()) || 0;
            let total = 0;
            if(weight <= 6) total = 130;
            else total = 130 + (weight - 6) * 20;
            $('#total' + orderId).val(total.toFixed(2));
        });

        function showMessage(message, type = 'success') {
            // type: success / danger
            const msgId = 'msg' + Date.now();
            const html = `
                <div id="${msgId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
            $('#ajaxMessageContainer').append(html);

            // Auto remove after 4 seconds
            setTimeout(() => {
                $('#' + msgId).alert('close');
            }, 4000);
        }

        $(document).on('submit', '.edit-order-form', function(e){
            e.preventDefault();
            const orderId = $(this).data('order-id');
            const weight = parseFloat($(this).find('.weight-input').val()) || 0;
            const total = parseFloat($(this).find('.total-input').val()) || 0;
            const amount_status = $(this).find('.amount_status-input').val();
            const laundry_status = $(this).find('.laundry_status-input').val();

            $.ajax({
                url: '/orders/' + orderId,
                method: 'PUT',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    weight: weight,
                    total: total,
                    amount_status: amount_status,
                    laundry_status: laundry_status
                },
                success: function(res){
                    // Update table row without reload
                    const row = $('#orderRow' + orderId);
                    row.find('.weight').text(weight);
                    row.find('.total').text(total);
                    row.find('.amount_status').text(amount_status);
                    row.find('.laundry_status').text(laundry_status);

                    // Close modal
                    $('#editOrderModal' + orderId).modal('hide');

                    // Show success message
                    showMessage('Order updated successfully!', 'success');
                },
                error: function(err){
                    // Show error message
                    showMessage('Update failed. Please try again.', 'danger');
                }
            });
        });


    });
    </script>
</x-app-layout>
