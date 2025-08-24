<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Order') }}
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 text-green-600">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('orders.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label>Customer Name</label>
                    <input type="text" name="customer_name"
                        value="{{ old('customer_name', Auth::user()->name) }}"
                        class="w-full border p-2" required readonly>
                </div>

                <div>
                    <label>Contact Number</label>
                    <input type="text" name="contact_number" class="w-full border p-2" required>
                </div>

                <div>
                    <label>Address</label>
                    <textarea name="address" class="w-full border p-2" required></textarea>
                </div>

                <div>
                    <label>Service Type</label>
                    <select name="service_type" class="w-full border p-2" required>
                        <option value="Delivery">Delivery</option>
                        <option value="Pick-up">Pick-up</option>
                    </select>
                </div>

                <div>
                    <label>Weight (kg)</label>
                    <input type="number" name="weight" class="w-full border p-2" required>
                </div>


                <div>
                    <label>Total</label>
                    <input type="number" name="total" class="w-full border p-2" required>
                </div>

          

                <div>
                    <label>Order Date</label>
                    <input type="datetime-local" name="order_date" class="w-full border p-2" required>
                </div>

                <button type="submit" class="px-4 py-2 bg-blue-600  rounded">
                    Save Order
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
