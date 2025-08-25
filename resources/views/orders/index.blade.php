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

    {{-- AJAX Script --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Live search
            $('#searchInput').on('keyup', function() {
                let query = $(this).val();
                fetchOrders(query);
            });

            // Handle pagination links click
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
        });
    </script>
</x-app-layout>
