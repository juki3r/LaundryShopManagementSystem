<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Transaction Report
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">

                <!-- Tabs -->
                <div class="mb-3 d-flex gap-2">
                    <a href="{{ route('reports.index', ['period'=>'today']) }}" class="btn btn-sm {{ $period=='today' ? 'btn-primary' : 'btn-outline-primary' }}">Today</a>
                    <a href="{{ route('reports.index', ['period'=>'weekly']) }}" class="btn btn-sm {{ $period=='weekly' ? 'btn-primary' : 'btn-outline-primary' }}">This Week</a>
                    <a href="{{ route('reports.index', ['period'=>'monthly']) }}" class="btn btn-sm {{ $period=='monthly' ? 'btn-primary' : 'btn-outline-primary' }}">This Month</a>
                </div>

                <!-- Custom Date Filter -->
                <form method="GET" action="{{ route('reports.index') }}" class="row g-2 mb-3">
                    <div class="col-md-3">
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">Filter</button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-secondary w-100" onclick="window.print()">🖨 Print</button>
                    </div>
                </form>

                <!-- Date Range Label -->
                <p class="mb-2">
                    <strong>Showing:</strong>
                    {{ $label }} ({{ $startDate->format('F d, Y') }} to {{ $endDate->format('F d, Y') }})
                </p>

                <!-- Total Income -->
                <div class="alert alert-info">
                    <strong>Total Income:</strong> ₱{{ number_format($totalIncome, 2) }}
                </div>

                <!-- Orders Table -->
                <div id="print-area" style="height:300px; overflow-y:auto; border:1px solid #ddd; border-radius:8px; padding:0.5rem;">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Date & Time</th>
                                <th>Customer Name</th>
                                <th>Address</th>
                                <th>Service Type</th>
                                <th>Price (₱)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('F d, Y · h:i A') }}</td>
                                    <td>{{ $order->customer_name ?? 'Anonymous' }}</td>
                                    <td>{{ $order->address }}</td>
                                    <td>{{ $order->service_type }}</td>
                                    <td>{{ number_format($order->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No orders found in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</x-app-layout>
