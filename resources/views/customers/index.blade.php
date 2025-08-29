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

                    {{-- ✅ Message container for AJAX --}}
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
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                </tr>
                            </thead>
                            <tbody id="customersTable">
                                @foreach ($customers as $index => $customer)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->username }}</td>
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

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            let formData = new FormData(form);

            fetch(form.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json" // tell Laravel to return JSON
                },
                body: formData
            })
            .then(async (res) => {
                if (!res.ok) {
                    let error = await res.json();
                    throw error;
                }
                return res.json();
            })
            .then(data => {
                messageBox.classList.remove("d-none", "alert-success", "alert-danger");

                if (data.success) {
                    messageBox.classList.add("alert-success");
                    messageBox.innerText = data.message;

                    // Add new row
                    tableBody.insertAdjacentHTML("beforeend", `
                        <tr>
                            <td>${data.customer.id}</td>
                            <td>${data.customer.name}</td>
                            <td>${data.customer.username}</td>
                        </tr>
                    `);

                    form.reset();
                    bootstrap.Modal.getInstance(document.getElementById("addModal")).hide();
                } else {
                    messageBox.classList.add("alert-danger");
                    messageBox.innerText = data.message ?? "Something went wrong.";
                }
            })
            .catch(err => {
                console.error(err);
                messageBox.classList.remove("d-none");
                messageBox.classList.add("alert-danger");

                if (err.errors) {
                    // Show validation errors
                    let errors = Object.values(err.errors).flat().join("\n");
                    messageBox.innerText = errors;
                } else {
                    messageBox.innerText = "Server error. Please try again.";
                }
            });
        });
    });
    </script>

</x-app-layout>
