<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Orders Management') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        {{-- Search Input --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Orders..." value="{{ $search ?? '' }}">
            </div>
        </div>

        {{-- Orders Table (Partial) --}}
        <div id="ordersTableContainer">
            @include('orders.partials.orders-table', ['orders' => $orders])
        </div>
    </div>

    {{-- AJAX + Modal JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {

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

            // Delegated events for dynamic modals

            // Auto-calculate total
            $(document).on('input', '.weight-input', function(){
                const orderId = $(this).data('order-id');
                const weight = parseFloat($(this).val()) || 0;
                let total = weight <= 6 ? 130 : 130 + (weight - 6) * 20;
                $('#total' + orderId).val(total.toFixed(2));
            });

            // AJAX submit
            $(document).on('submit', '.edit-order-form', function(e){
                e.preventDefault();
                const $form = $(this);
                const orderId = $form.data('order-id');
                const weight = parseFloat($form.find('.weight-input').val()) || 0;
                const total = parseFloat($form.find('.total-input').val()) || 0;
                const amount_status = $form.find('.amount_status-input').val();
                const laundry_status = $form.find('.laundry_status-input').val();

                $.ajax({
                    url: '/orders/' + orderId,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        weight: weight,
                        total: total,
                        amount_status: amount_status,
                        laundry_status: laundry_status
                    },
                    success: function(res){
                        const row = $('#orderRow' + orderId);
                        row.find('.weight').text(weight);
                        row.find('.total').text(total);
                        row.find('.amount_status').text(amount_status);
                        row.find('.laundry_status').text(laundry_status);

                        $('#editOrderModal' + orderId).modal('hide');
                    },
                    error: function(err){
                        alert('Update failed. Please try again.');
                    }
                });
            });

        });
    </script>
</x-app-layout>
