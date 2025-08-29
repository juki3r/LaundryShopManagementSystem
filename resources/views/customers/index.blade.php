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

                    {{-- Message container for AJAX --}}
                    <div id="ajaxMessage" class="alert d-none"></div>

                    <h4 class="mb-4 d-flex justify-content-between align-items-center">
                        <strong>Customers lists</strong>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            Add Customer
                        </button>
                    </h4>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Address</th>
                                    <th>Contact</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="customersTable">
                                @foreach ($customers as $customer)
                                    <tr id="customerRow{{ $customer->id }}">
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->username }}</td>
                                        <td>{{ $customer->address ?? '-' }}</td>
                                        <td>{{ $customer->contact_number ?? '-' }}</td>
                                        <td>
                                            <button 
                                                class="btn btn-sm btn-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#addOrderModal{{ $customer->id }}">
                                                Add Order
                                            </button>
                                            <button 
                                                class="btn btn-sm btn-danger deleteCustomerBtn" 
                                                data-id="{{ $customer->id }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- ADD CUSTOMER MODAL --}}
                    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="addCustomerForm" action="{{ route('register.customer') }}" method="POST" class="modal-content">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Customer</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Add Order Modal -->
                    <div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addOrderModalLabel">Add New Order</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('orders.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        
                                        <div class="mb-3">
                                            <label for="customer_name" class="form-label">Customer Name</label>
                                            <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="contact_number" class="form-label">Contact Number</label>
                                            <input type="text" name="contact_number" id="contact_number" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea name="address" id="address" class="form-control" rows="2" required></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="service_type" class="form-label">Service Type</label>
                                            <select name="service_type" id="service_type" class="form-select" required>
                                                <option value="">-- Select --</option>
                                                <option value="Delivery">Delivery</option>
                                                <option value="Pick-up">Pick-up</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="order_date" class="form-label">Order Date</label>
                                            <input type="date" name="order_date" id="order_date" class="form-control" required>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Add Order</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    {{-- Ajax Script --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById("addCustomerForm");
            const messageBox = document.getElementById("ajaxMessage");
            const tableBody = document.getElementById("customersTable");
            const modalEl = document.getElementById("addModal");

            // Delete customer
            document.addEventListener("click", function (e) {
                if (e.target.classList.contains("deleteCustomerBtn")) {
                    let customerId = e.target.getAttribute("data-id");
                    if (!confirm("Are you sure you want to delete this customer?")) return;

                    fetch(`/customers/${customerId}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        messageBox.classList.remove("d-none", "alert-success", "alert-danger");

                        if (data.success) {
                            messageBox.classList.add("alert-success");
                            messageBox.innerText = data.message;

                            // Remove row from table
                            let row = document.getElementById(`customerRow${customerId}`);
                            if (row) row.remove();
                        } else {
                            messageBox.classList.add("alert-danger");
                            messageBox.innerText = data.message;
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        messageBox.classList.remove("d-none");
                        messageBox.classList.add("alert-danger");
                        messageBox.innerText = "Server error. Please try again.";
                    });
                }
            });

            // Add customer
            form.addEventListener("submit", function (e) {
                e.preventDefault();

                let formData = new FormData(form);

                fetch(form.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    messageBox.classList.remove("d-none", "alert-success", "alert-danger");

                    if (data.success) {
                        messageBox.classList.add("alert-success");
                        messageBox.innerText = data.message;

                        //  Append full row
                        tableBody.insertAdjacentHTML("beforeend", `
                            <tr id="customerRow${data.customer.id}">
                                <td>${data.customer.name}</td>
                                <td>${data.customer.username}</td>
                                <td>${data.customer.address ?? '-'}</td>
                                <td>${data.customer.contact_number ?? '-'}</td>
                                <td>
                                    <button 
                                        class="btn btn-sm btn-success" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#addOrderModal{{ $customer->id }}">
                                        Add Order
                                    </button>
                                    <button 
                                        class="btn btn-sm btn-danger deleteCustomerBtn" 
                                        data-id="${data.customer.id}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        `);

                        form.reset();
                    } else {
                        messageBox.classList.add("alert-danger");
                        messageBox.innerText = data.message;
                    }

                    //Close modal
                    let modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) modalInstance.hide();
                })
                .catch(err => {
                    console.error(err);
                    messageBox.classList.remove("d-none");
                    messageBox.classList.add("alert-danger");
                    messageBox.innerText = "Server error. Please try again.";

                    let modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) modalInstance.hide();
                });
            });
        });
    </script>

</x-app-layout>
