<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Customers Management') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h4 class="mb-4">Users List</h4>
          
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        Add customer
                    </button>
     


                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Address</th>
                                     <th scope="col">Phone number</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $index => $customer)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->username }}</td>
                                        <td>{{ $customer->address }}</td>
                                        <td>{{ $customer->phone_number }}</td>
                                        {{-- <td>
                                             <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editOrderModal{{ $order->id }}">
                                            Add customer
                                            </button>
                                        </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- ADD ORDER MODAL --}}
                    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form class="modal-content edit-order-form">
                                @csrf
                                @method('POST')
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Customer</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    
                                    hello
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
