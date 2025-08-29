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

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4><strong>Customers Lists</strong></h4>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            Add Customer
                        </button>
                    </div>

                    {{-- Search --}}
                    <div class="mb-3">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search customers...">
                    </div>

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
                                {{-- Rows will be loaded via AJAX --}}
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <nav>
                        <ul class="pagination" id="paginationContainer"></ul>
                    </nav>

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

                    {{-- ADD ORDER MODAL --}}
                    <div class="modal fade" id="addOrderModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <form id="addOrderForm" method="POST" class="modal-content">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Add New Order</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Customer Name</label>
                                        <input type="text" name="customer_name" class="form-control" required readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Contact Number</label>
                                        <input type="text" name="contact_number" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control" rows="2" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Service Type</label>
                                        <select name="service_type" class="form-select" required>
                                            <option value="">-- Select --</option>
                                            <option value="Delivery">Delivery</option>
                                            <option value="Pick-up">Pick-up</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Order Date</label>
                                        <input type="text" name="order_date" class="form-control" readonly>
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

    {{-- Scripts --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const messageBox = document.getElementById("ajaxMessage");
            const tableBody = document.getElementById("customersTable");
            const searchInput = document.getElementById("searchInput");
            const paginationContainer = document.getElementById("paginationContainer");
            const addCustomerForm = document.getElementById("addCustomerForm");
            const addModal = document.getElementById("addModal");

            let currentPage = 1;
            let currentSearch = '';

            // Function to fetch customers with search & pagination
            function fetchCustomers(search = '', page = 1) {
                currentSearch = search;
                currentPage = page;

                fetch(`/customers?search=${encodeURIComponent(search)}&page=${page}`, {
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                })
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = '';
                    if (data.customers.length === 0) {
                        tableBody.innerHTML = `<tr><td colspan="5" class="text-center">No customers found</td></tr>`;
                        paginationContainer.innerHTML = '';
                        return;
                    }

                    data.customers.forEach(customer => {
                        tableBody.insertAdjacentHTML('beforeend', `
                            <tr id="customerRow${customer.id}">
                                <td>${customer.name}</td>
                                <td>${customer.username}</td>
                                <td>${customer.address ?? '-'}</td>
                                <td>${customer.contact_number ?? '-'}</td>
                                <td>
                                    <button class="btn btn-sm btn-success addOrderBtn" 
                                        data-bs-toggle="modal" data-bs-target="#addOrderModal"
                                        data-id="${customer.id}"
                                        data-name="${customer.name}"
                                        data-contact="${customer.contact_number ?? ''}"
                                        data-address="${customer.address ?? ''}">
                                        Add Order
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteCustomerBtn" data-id="${customer.id}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        `);
                    });

                    // Pagination
                    renderPagination(data.pagination.current_page, data.pagination.last_page);
                })
                .catch(err => console.error(err));
            }

            function renderPagination(current, last) {
                paginationContainer.innerHTML = '';
                for (let i = 1; i <= last; i++) {
                    const activeClass = i === current ? 'active' : '';
                    paginationContainer.insertAdjacentHTML('beforeend', `
                        <li class="page-item ${activeClass}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `);
                }
            }

            // Pagination click
            paginationContainer.addEventListener('click', function(e) {
                e.preventDefault();
                if (e.target.tagName === 'A') {
                    const page = parseInt(e.target.dataset.page);
                    if (!isNaN(page)) fetchCustomers(currentSearch, page);
                }
            });

            // Search input
            searchInput.addEventListener('input', function() {
                fetchCustomers(this.value, 1);
            });

            // Initial load
            fetchCustomers();

            // Delete customer
            document.addEventListener("click", function (e) {
                if (e.target.classList.contains("deleteCustomerBtn")) {
                    const customerId = e.target.dataset.id;
                    if (!confirm("Are you sure you want to delete this customer?")) return;

                    fetch(`/customers/${customerId}`, {
                        method: "DELETE",
                        headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
                    })
                    .then(res => res.json())
                    .then(data => {
                        messageBox.classList.remove("d-none", "alert-success", "alert-danger");
                        if (data.success) {
                            messageBox.classList.add("alert-success");
                            messageBox.innerText = data.message;
                            fetchCustomers(currentSearch, currentPage);
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

            // Add customer via AJAX
            addCustomerForm.addEventListener("submit", function(e) {
                e.preventDefault();
                const formData = new FormData(addCustomerForm);
                fetch(addCustomerForm.action, {
                    method: "POST",
                    headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    messageBox.classList.remove("d-none", "alert-success", "alert-danger");
                    if (data.success) {
                        messageBox.classList.add("alert-success");
                        messageBox.innerText = data.message;
                        fetchCustomers(currentSearch, currentPage);
                        addCustomerForm.reset();
                        const modalInstance = bootstrap.Modal.getInstance(addModal);
                        if (modalInstance) modalInstance.hide();
                    } else {
                        messageBox.classList.add("alert-danger");
                        messageBox.innerText = data.message;
                    }
                })
                .catch(err => console.error(err));
            });

            // Add order modal pre-fill
            document.addEventListener("click", function (e) {
                if (e.target.classList.contains("addOrderBtn")) {
                    const customerId = e.target.dataset.id;
                    const customerName = e.target.dataset.name;
                    const customerContact = e.target.dataset.contact;
                    const customerAddress = e.target.dataset.address;
                    const addOrderForm = document.getElementById("addOrderForm");

                    addOrderForm.action = `/orders/${customerId}`;
                    addOrderForm.querySelector('input[name="customer_name"]').value = customerName;
                    addOrderForm.querySelector('input[name="contact_number"]').value = customerContact;
                    addOrderForm.querySelector('textarea[name="address"]').value = customerAddress;

                    const orderDateInput = addOrderForm.querySelector('input[name="order_date"]');
                    const manilaTime = new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" });
                    const manilaDate = new Date(manilaTime);
                    const yyyy = manilaDate.getFullYear();
                    const mm = String(manilaDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(manilaDate.getDate()).padStart(2, '0');
                    orderDateInput.value = `${yyyy}-${mm}-${dd}`;
                }
            });
        });
    </script>
</x-app-layout>
