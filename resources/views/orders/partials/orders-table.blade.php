@if(Auth::user()->role === 'admin')
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-hover" id="ordersTable">
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
                <tr id="orderRow{{ $order->id }}">
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->contact_number }}</td>
                    <td>{{ $order->address }}</td>
                    <td class="weight">{{ $order->weight }}</td>
                    <td class="total">{{ $order->total }}</td>
                    <td class="amount_status">{{ $order->amount_status }}</td>
                    <td>{{ $order->service_type }}</td>
                    <td class="laundry_status">{{ $order->laundry_status }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editOrderModal{{ $order->id }}">
                            Edit
                        </button>
                    </td>
                </tr>
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

{{-- MODALS OUTSIDE TABLE --}}
@foreach($orders as $order)
<div class="modal fade" id="editOrderModal{{ $order->id }}" tabindex="-1" aria-labelledby="editOrderModalLabel{{ $order->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content edit-order-form" data-order-id="{{ $order->id }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="editOrderModalLabel{{ $order->id }}">Edit Order #{{ $order->id }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Weight (kg)</label>
          <input type="number" min="1" class="form-control weight-input" data-order-id="{{ $order->id }}" value="{{ $order->weight }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Total (PHP)</label>
          <input type="text" class="form-control total-input" id="total{{ $order->id }}" value="{{ $order->total }}" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label">Amount Status</label>
          <select class="form-select amount_status-input">
            <option value="Pending" {{ $order->amount_status === 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Paid" {{ $order->amount_status === 'Paid' ? 'selected' : '' }}>Paid</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Laundry Status</label>
          <select class="form-select laundry_status-input">
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
@endforeach

<!-- jQuery required for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    // Auto-calculate total
    $(document).on('input', '.weight-input', function(){
        const orderId = $(this).data('order-id');
        const weight = parseFloat($(this).val()) || 0;
        let total = 0;
        if(weight <= 6) total = 130;
        else total = 130 + (weight - 6) * 20;
        $('#total' + orderId).val(total.toFixed(2));
    });

    // AJAX form submission
    $(document).on('submit', '.edit-order-form', function(e){
        e.preventDefault();
        const orderId = $(this).data('order-id');
        const weight = parseFloat($(this).find('.weight-input').val()) || 0;
        const total = parseFloat($(this).find('.total-input').val()) || 0;
        const amount_status = $(this).find('.amount_status-input').val();
        const laundry_status = $(this).find('.laundry_status-input').val();

        $.ajax({
            url: '/orders/' + orderId,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                weight: weight,
                total: total,
                amount_status: amount_status,
                laundry_status: laundry_status
            },
            success: function(res){
                // Update table row without reload
                const row = $('#orderRow' + orderId);
                row.find('.weight').text(weight);
                row.find('.total').text(total);
                row.find('.amount_status').text(amount_status);
                row.find('.laundry_status').text(laundry_status);

                // Close modal
                $('#editOrderModal' + orderId).modal('hide');
            },
            error: function(err){
                alert('Update failed. Please try again.');
            }
        });
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
