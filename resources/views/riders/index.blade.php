<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            Riders Management
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container-fluid">
            <div class="card shadow-sm rounded-3">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-people-fill me-2"></i>Riders List</h4>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="bi bi-plus-circle"></i> Add Rider
                    </button>
                </div>
                <div class="card-body">
                    
                    {{-- AJAX Message --}}
                    <div id="ajaxMessage" class="alert d-none rounded-3"></div>

                    {{-- Search --}}
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Search riders by name, username, address or contact...">
                        </div>
                    </div>

                    {{-- Riders Table --}}
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
                            <tbody id="ridersTable" class="text-center">
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

    {{-- Add Rider Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="addRiderForm" action="{{ route('register.rider') }}" method="POST" class="modal-content border-0 shadow">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Add Rider</h5>
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

    {{-- Scripts --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const messageBox = document.getElementById("ajaxMessage");
            const tableBody = document.getElementById("ridersTable");
            const searchInput = document.getElementById("searchInput");
            const paginationContainer = document.getElementById("paginationContainer");
            const addCustomerForm = document.getElementById("addRiderForm");
            const addModal = document.getElementById("addModal");

            let currentPage = 1;
            let currentSearch = '';

            function fetchRiders(search = '', page = 1) {
                currentSearch = search;
                currentPage = page;

                fetch(`/riders?search=${encodeURIComponent(search)}&page=${page}`, {
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                })
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = '';
                    if (data.riders.length === 0) {
                        tableBody.innerHTML = `<tr><td colspan="5" class="text-center">No riders found</td></tr>`;
                        paginationContainer.innerHTML = '';
                        return;
                    }

                    data.riders.forEach(rider => {
                        tableBody.insertAdjacentHTML('beforeend', `
                            <tr id="customerRow${rider.id}">
                                <td>${rider.name}</td>
                                <td>${rider.username}</td>
                                <td>${rider.address ?? ''}</td>
                                <td>${rider.contact_number ?? ''}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-danger deleteRidersBtn" data-id="${rider.id}">
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
                    if (!isNaN(page)) fetchRiders(currentSearch, page);
                }
            });

            searchInput.addEventListener('input', function() {
                fetchRiders(this.value, 1);
            });

            fetchRiders();

            document.addEventListener("click", function (e) {
                if (e.target.classList.contains("deleteRiderBtn")) {
                    const riderId = e.target.dataset.id;
                    if (!confirm("Are you sure you want to delete this rider?")) return;

                    fetch(`/riders/${riderId}`, {
                        method: "DELETE",
                        headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content }
                    })
                    .then(res => res.json())
                    .then(data => {
                        messageBox.classList.remove("d-none", "alert-success", "alert-danger");
                        if (data.success) {
                            messageBox.classList.add("alert-success");
                            messageBox.innerText = data.message;
                            fetchRiders(currentSearch, currentPage);
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

            addRiderForm.addEventListener("submit", function(e) {
                e.preventDefault();
                const formData = new FormData(addRiderForm);
                fetch(addRiderForm.action, {
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
                        fetchRiders(currentSearch, currentPage);
                        addRiderForm.reset();
                        const modalInstance = bootstrap.Modal.getInstance(addModal);
                        if (modalInstance) modalInstance.hide();
                    } else {
                        messageBox.classList.add("alert-danger");
                        messageBox.innerText = data.message;
                    }
                })
                .catch(err => console.error(err));
            });

        });
    </script>

    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</x-app-layout>
