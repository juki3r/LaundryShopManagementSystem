<x-app-layout>
<x-slot name="header">
    <h2 class="text-2xl font-bold text-gray-800">Orders Management</h2>
</x-slot>

<div class="container mt-4">

    {{-- AJAX Messages --}}
    <div id="ajaxMessageContainer" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Top Bar: Search + Filter --}}
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="searchInput" class="form-control" placeholder="Search Orders..." value="{{ $search ?? '' }}">
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-filter"></i></span>
                <select id="amountStatusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option value="Pending" {{ ($amountStatus ?? '') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Paid" {{ ($amountStatus ?? '') === 'Paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div id="ordersTableContainer">
        @include('orders.partials.orders-table', ['orders' => $orders, 'riders' => $riders])
    </div>

</div>

{{-- JS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<script>
$(document).ready(function() {

    // Auto-calc weight -> total
    $(document).on('input', '.weight-input', function() {
        const orderId = $(this).data('order-id');
        const weight = parseFloat($(this).val()) || 0;
        const total = weight <= 6 ? 140 : 140 + (weight - 6) * 20;
        $('#total' + orderId).val(total.toFixed(2));
        const row = $('#orderRow' + orderId);
        row.find('.weight').text(weight);
        row.find('.total').text(total.toFixed(2));
    });

    // Show message + reload
    function showMessage(message, type = 'success') {
        const msgId = 'msg' + Date.now();
        const html = `<div id="${msgId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
        $('#ajaxMessageContainer').append(html);
        setTimeout(() => { $('#' + msgId).alert('close'); }, 4000);
        setTimeout(() => { location.reload(); }, 500);
    }

    // Edit order AJAX
    $(document).on('submit', '.edit-order-form', function(e){
        e.preventDefault();
        const form = $(this);
        const orderId = form.data('order-id');
        const weight = parseFloat(form.find('.weight-input').val()) || 0;
        const total = parseFloat(form.find('.total-input').val()) || 0;
        const amount_status = form.find('.amount_status-input').val();
        const laundry_status = form.find('.laundry_status-input').val();
        const rider = form.find('.rider_id-input').val();
         const claimed = form.find('.claimed-input').val();

        $.ajax({
            url: '/orders/' + orderId,
            method: 'PUT',
            dataType: 'json',
            data: {
                _token: '{{ csrf_token() }}',
                weight,
                total,
                amount_status,
                laundry_status,
                rider,
                claimed 
            },
            success: function(res){
                const modalEl = document.getElementById('editOrderModal' + orderId);
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) { modal.hide(); $('.modal-backdrop').remove(); }
                showMessage('Order updated successfully!', 'success');
            },
            error: function(err){
                showMessage('Update failed. Please try again.', 'danger');
            }
        });
    });

    // Fetch orders on search/filter
    function fetchOrders() {
        const query = $('#searchInput').val();
        const status = $('#amountStatusFilter').val();

        $.ajax({
            url: "{{ route('orders.index') }}",
            data: { search: query, amount_status: status },
            success: function(data) {
                $('#ordersTableContainer').html(data);
            }
        });
    }

    $('#searchInput').on('keyup', fetchOrders);
    $('#amountStatusFilter').on('change', fetchOrders);

});
</script>
</x-app-layout>
