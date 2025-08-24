<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Orders Management') }}
        </h2>
    </x-slot>

@if(Auth::user()->role === 'admin')
    <div class="p-6 bg-white shadow sm:rounded-lg">
        <table class="table-auto w-full border">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2">Customer</th>
                    <th class="px-4 py-2">Service</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td class="px-4 py-2">{{ $order->customer_name }}</td>
                        <td class="px-4 py-2">{{ $order->service_type }}</td>
                        <td class="px-4 py-2">{{ $order->laundry_status }}</td>
                        <td class="px-4 py-2">
                            <form action="{{ route('orders.approve', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>

                            <form action="{{ route('orders.deny', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm">Deny</button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else

    <div class="p-6 bg-white shadow sm:rounded-lg">
        <table class="table-auto w-full border">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2">Order ID</th>
                    <th class="px-4 py-2">Service</th>
                    <th class="px-4 py-2">Weight</th>
                    <th class="px-4 py-2">Total amount</th>
                    <th class="px-4 py-2">Approve/Deny</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td class="px-4 py-2">{{ $order->id }}</td>
                        <td class="px-4 py-2">{{ $order->service_type }}</td>
                        <td class="px-4 py-2">{{ $order->weight }}</td>
                        <td class="px-4 py-2">{{ $order->total }}</td>
                        <td class="px-4 py-2">{{ $order->laundry_status }}</td>
                   
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endif

</x-app-layout>
