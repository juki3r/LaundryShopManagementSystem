<!DOCTYPE html>
<html>
<head>
    <title>Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .receipt { width: 300px; margin: auto; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h2, .header p { margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 3px; text-align: left; font-size: 12px; }
        th { background-color: #f0f0f0; }
        .no-border { border: none; }
        .text-right { text-align: right; }
        .total { font-weight: bold; }
        hr { border: 1px dashed #000; margin: 10px 0; }
        .footer { text-align: center; font-size: 10px; margin-top: 5px; }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h2>YOUR STORE NAME</h2>
            <p>123 ABC STREET, ABC TOWN, ST 00000</p>
            <p>Tel: 555-555-5555 | Email: store@email.com</p>
        </div>

        <!-- Customer Info -->
        <table class="no-border">
            <tr>
                <td><strong>Ready Date:</strong> {{ $order->ready_date ?? $order->created_at->format('F d, Y') }}</td>
                <td><strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td><strong>Name:</strong> {{ $order->customer_name ?? 'Anonymous' }}</td>
                <td><strong>Tel:</strong> {{ $order->contact_number ?? '-' }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Email:</strong> {{ $order->email ?? '-' }}</td>
            </tr>
        </table>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>QTY</th>
                    <th>DESCRIPTION</th>
                    <th>PRICE</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order as $item)
                <tr>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">₱{{ number_format($item->price, 2) }}</td>
                    <td class="text-right">₱{{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="total text-right">TOTAL</td>
                    <td class="total text-right">₱{{ number_format($order->total, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <hr>
        <div class="footer">
            THANK YOU FOR YOUR BUSINESS!<br>
            NOT RESPONSIBLE FOR GOODS LEFT OVER 30 DAYS
        </div>
    </div>
</body>
</html>
