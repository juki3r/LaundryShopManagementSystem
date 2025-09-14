<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Reports
        </h2>
    </x-slot>

    @if(Auth::user()->role === 'admin')
        <div class="py-5">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    <div class="mb-4 text-end">
                        <button onclick="window.print()" class="btn btn-primary">Print Report</button>
                    </div>

                    @foreach(['today' => 'Today', 'month' => 'This Month', 'year' => 'This Year'] as $key => $label)
                        <h4 class="mb-2 font-semibold text-gray-700">{{ $label }}</h4>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Metric</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Total Profit</td>
                                        <td>₱{{ number_format($data[$key]['profit'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Claimed Laundry</td>
                                        <td>{{ $data[$key]['claimed'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Orders</td>
                                        <td>{{ $data[$key]['orders'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Delivered Orders</td>
                                        <td>{{ $data[$key]['delivered'] }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    @else
        <div class="py-5">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Hello Client, You must download and install our mobile app. Thanks!
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
