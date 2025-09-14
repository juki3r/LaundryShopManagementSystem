<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Welcome, <strong>{{ Auth::user()->name }}</strong>
        </h2>
    </x-slot>

    @if(Auth::user()->role === 'admin')
        <div class="py-5">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                    @foreach(['today' => 'Today', 'month' => 'This Month', 'year' => 'This Year'] as $key => $label)
                        <h4 class="mb-3 font-semibold text-gray-700">{{ $label }}</h4>
                        <div class="row mb-4">
                            <div class="col-md-3 mb-3">
                                <div class="card text-center">
                                    <div class="card-header"><strong>Total Profit</strong></div>
                                    <div class="card-body">₱{{ number_format($data[$key]['profit'], 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card text-center">
                                    <div class="card-header"><strong>Total Claimed Laundry</strong></div>
                                    <div class="card-body">{{ $data[$key]['claimed'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card text-center">
                                    <div class="card-header"><strong>Total Orders</strong></div>
                                    <div class="card-body">{{ $data[$key]['orders'] }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card text-center">
                                    <div class="card-header"><strong>Total Delivered Orders</strong></div>
                                    <div class="card-body">{{ $data[$key]['delivered'] }}</div>
                                </div>
                            </div>
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
