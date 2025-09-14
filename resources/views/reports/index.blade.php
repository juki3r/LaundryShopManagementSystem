<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📊 Transaction Report
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">

                <!-- Tabs -->
                <div class="mb-3">
                    <a href="{{ route('dashboard.report', ['period'=>'today']) }}" class="btn btn-sm {{ $period=='today' ? 'btn-primary' : 'btn-outline-primary' }}">Today</a>
                    <a href="{{ route('dashboard.report', ['period'=>'weekly']) }}" class="btn btn-sm {{ $period=='weekly' ? 'btn-primary' : 'btn-outline-primary' }}">This Week</a>
                    <a href="{{ route('dashboard.report', ['period'=>'monthly']) }}" class="btn btn-sm {{ $period=='monthly' ? 'btn-primary' : 'btn-outline-primary' }}">This Month</a>
                </div>

                <!-- Date Range -->
                <p class="mb-2"><strong>Showing:</strong> {{ $startDate->format('F d, Y') }} to {{ $endDate->format('F d, Y') }}</p>

                <!-- Total Income -->
                <div class="alert alert-info">
                    <strong>Total Income:</strong> ₱{{ number_format($totalIncome, 2) }}
                </div>

                <!-- Orders Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
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
</x-app-layout>
