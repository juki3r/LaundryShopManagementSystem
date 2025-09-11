<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            Customers Management
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container-fluid">
            <div class="card shadow-sm rounded-3">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-people-fill me-2"></i>Customers List</h4>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bi bi-plus-circle"></i> Add Customer
                    </button>
                </div>
                <div class="card-body">
                    
                    {{-- AJAX Message --}}
                    <div id="ajaxMessage" class="alert d-none rounded-3"></div>

                    {{-- Search --}}
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Search customers by name, username, address or contact...">
                        </div>
                    </div>

                    {{-- Customers Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-bordered border-secondary">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Address</th>
                                    <th>Contact</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="customersTable" class="text-center">
                                {{-- Rows will be loaded via AJAX --}}
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <nav class="mt-3">
                        <ul class="pagination justify-content-center" id="paginationContainer"></ul>
                    </nav>

                </div>
            </div>
        </div>
    </div>

    {{-- Add Customer Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="addCustomerForm" action="{{ route('register.customer') }}" method="POST" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Add Customer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control form-control-lg" placeholder="John Doe" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control form-control-lg" placeholder="johndoe123" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-circle"></i> Add</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Add Order Modal --}}
    <div class="modal fade" id="addOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="addOrderForm" method="POST" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-cart-plus-fill me-2"></i>Add New Order</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control form-control-lg" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control form-control-lg" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="address" class="form-control form-control-lg" rows="2" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Service Type</label>
                            <select name="service_type" class="form-select form-select-lg" required>
                                <option value="">-- Select --</option>
                                <option value="Delivery">Delivery</option>
                                <option value="Pick-up">Pick-up</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Order Date</label>
                            <input type="text" name="order_date" class="form-control form-control-lg" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-check-circle"></i> Add Order</button>
                </div>
            </form>
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
                                <td>${customer.address ?? ''}</td>
                                <td>${customer.contact_number ?? ''}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success addOrderBtn me-1" 
                                        data-bs-toggle="modal" data-bs-target="#addOrderModal"
                                        data-id="${customer.id}"
                                        data-name="${customer.name}"
                                        data-contact="${customer.contact_number ?? ''}"
                                        data-address="${customer.address ?? ''}">
                                        <i class="bi bi-cart-plus-fill"></i> Add Order
                                    </button>
                                    <button class="btn btn-sm btn-danger deleteCustomerBtn" data-id="${customer.id}">
                                        <i class="bi bi-trash-fill"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        `);
                    });

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

            paginationContainer.addEventListener('click', function(e) {
                e.preventDefault();
                if (e.target.tagName === 'A') {
                    const page = parseInt(e.target.dataset.page);
                    if (!isNaN(page)) fetchCustomers(currentSearch, page);
                }
            });

            searchInput.addEventListener('input', function() {
                fetchCustomers(this.value, 1);
            });

            fetchCustomers();

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

    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</x-app-layout>
