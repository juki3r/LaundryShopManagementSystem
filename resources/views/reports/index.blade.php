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

                <!-- Filter Form -->
                <form method="GET" action="{{ route('reports.index') }}" class="row g-2 mb-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">From</label>
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To</label>
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Service Type</label>
                        <select name="service_type" class="form-select">
                            <option value="all" {{ ($serviceType ?? 'all') == 'all' ? 'selected' : '' }}>All Services</option>
                            @foreach($serviceTypes as $type)
                                <option value="{{ $type }}" {{ ($serviceType ?? '') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
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
                    @if($serviceType && $serviceType !== 'all')
                        • <strong>Service:</strong> {{ ucfirst($serviceType) }}
                    @endif
                </p>

                <!-- Printable Area -->
                <div id="print-area" style="overflow-y:auto; border:1px solid #ddd; border-radius:8px; padding:1rem;">
                    <!-- Header for Print -->
                    <div class="text-center mb-3 d-print-block">
                        <h3 class="fw-bold mb-0">PONG LAUNDRY SERVICES</h3>
                        <small>{{ $label }} Report ({{ $startDate->format('F d, Y') }} - {{ $endDate->format('F d, Y') }})</small>
                        @if($serviceType && $serviceType !== 'all')
                            <div><strong>Service Type:</strong> {{ ucfirst($serviceType) }}</div>
                        @endif
                        <hr>
                    </div>

                    <!-- Orders Table -->
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
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->setTimezone('Asia/Manila')->format('F d, Y · h:i A') }}</td>
                                    <td>{{ $order->customer_name ?? 'Anonymous' }}</td>
                                    <td>{{ $order->address }}</td>
                                    <td>{{ ucfirst($order->service_type) }}</td>
                                    <td>{{ number_format($order->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No orders found in this period.</td>
                                </tr>
                            @endforelse
                        </tbody>

                        @if($orders->count() > 0)
                            <tfoot>
                                <tr class="table-secondary">
                                    <th colspan="4" class="text-end">TOTAL INCOME:</th>
                                    <th>₱{{ number_format($totalIncome, 2) }}</th>
                                </tr>
                            </tfoot>
                        @endif
                    </table>

                    <!-- Footer Note -->
                    <div class="text-center mt-3 d-print-block">
                        <small>Generated on {{ now('Asia/Manila')->format('F d, Y · h:i A') }}</small>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Print Styling -->
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
                padding: 1rem;
            }
            button, form, .btn, .mb-3.d-flex {
                display: none !important;
            }
        }
    </style>
</x-app-layout>
