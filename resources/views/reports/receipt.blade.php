<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .receipt { width: 300px; margin: auto; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 5px; }
        hr { border: 1px dashed #000; }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt">
        <div class="header">
            <h3>Transaction Receipt</h3>
        </div>
        <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y · h:i A') }}</p>
        <p><strong>Customer:</strong> {{ $order->customer_name ?? 'Anonymous' }}</p>
        <p><strong>Address:</strong> {{ $order->address }}</p>
        <p><strong>Service Type:</strong> {{ $order->service_type }}</p>
        <p><strong>Total:</strong> ₱{{ number_format($order->total, 2) }}</p>
        <hr>
        <p style="text-align:center;">Thank you for your business!</p>
    </div>
</body>
</html>
