<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Orders Management') }}
        </h2>
    </x-slot>

    <div class="container mt-4">

        {{-- Search --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Search Orders...">
            </div>
        </div>

        @if(Auth::user()->role === 'admin')
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-hover" id="ordersTable">
                        <thead class="table-light">
                            <tr>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->service_type }}</td>
                                    <td>{{ $order->laundry_status }}</td>
                                    <td>
                                        <form action="{{ route('orders.approve', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </form>

                                        <form action="{{ route('orders.deny', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm">Deny</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @else
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-hover" id="ordersTable">
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
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->service_type }}</td>
                                    <td>{{ $order->weight }}</td>
                                    <td>{{ $order->total }}</td>
                                    <td>{{ $order->laundry_status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center">
                        {{ $orders->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Live Search Script --}}
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#ordersTable tbody tr');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>
</x-app-layout>
