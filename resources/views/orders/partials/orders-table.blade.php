@if(Auth::user()->role === 'admin')
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Customer</th>
                        <th>Contact #</th>
                        <th>Address</th>
                        <th>Weight</th>
                        <th>Total amount</th>
                        <th>Payment</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->contact_number }}</td>
                            <td>{{ $order->address }}</td>
                            <td>{{ $order->weight }}</td>
                            <td>{{ $order->total }}</td>
                            <td>{{ $order->amount_status }}</td>
                            <td>{{ $order->service_type }}</td>
                            <td>{{ $order->laundry_status }}</td>
                            <td>
                                <!-- Edit button triggers modal -->
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editOrderModal{{ $order->id }}">
                                    Edit
                                </button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editOrderModal{{ $order->id }}" tabindex="-1" aria-labelledby="editOrderModalLabel{{ $order->id }}" aria-hidden="true">
                          <div class="modal-dialog">
                            <form action="{{ route('orders.update', $order->id) }}" method="POST" class="modal-content">
                              @csrf
                              @method('PUT')
                              <div class="modal-header">
                                <h5 class="modal-title" id="editOrderModalLabel{{ $order->id }}">Edit Order #{{ $order->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <div class="mb-3">
                                  <label for="weight{{ $order->id }}" class="form-label">Weight (kg)</label>
                                  <input type="number" name="weight" min="1" value="{{ $order->weight }}" class="form-control weight-input" id="weight{{ $order->id }}" data-order-id="{{ $order->id }}">
                                </div>

                                <div class="mb-3">
                                  <label for="total{{ $order->id }}" class="form-label">Total (PHP)</label>
                                  <input type="text" name="total" class="form-control" id="total{{ $order->id }}" value="{{ $order->total }}" readonly>
                                </div>

                                <div class="mb-3">
                                  <label for="amount_status{{ $order->id }}" class="form-label">Amount Status</label>
                                  <select name="amount_status" id="amount_status{{ $order->id }}" class="form-select">
                                    <option value="Pending" {{ $order->amount_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Paid" {{ $order->amount_status === 'Paid' ? 'selected' : '' }}>Paid</option>
                                  </select>
                                </div>

                                <div class="mb-3">
                                  <label for="laundry_status{{ $order->id }}" class="form-label">Laundry Status</label>
                                  <select name="laundry_status" id="laundry_status{{ $order->id }}" class="form-select">
                                    <option value="Waiting" {{ $order->laundry_status === 'Waiting' ? 'selected' : '' }}>Waiting</option>
                                    <option value="Processing" {{ $order->laundry_status === 'Processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="Completed" {{ $order->laundry_status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                  </select>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                              </div>
                            </form>
                          </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Auto-calculate total JS -->
    <script>
    document.querySelectorAll('.weight-input').forEach(input => {
        input.addEventListener('input', function() {
            const orderId = this.dataset.orderId;
            const weight = parseFloat(this.value) || 0;
            let total = 0;
            if(weight <= 6) {
                total = 130;
            } else {
                total = 130 + (weight - 6) * 20;
            }
            document.getElementById('total' + orderId).value = total.toFixed(2);
        });
    });
    </script>

@else
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Service</th>
                        <th>Weight</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->service_type }}</td>
                            <td>{{ $order->weight }}</td>
                            <td>{{ $order->total }}</td>
                            <td>{{ $order->laundry_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endif
