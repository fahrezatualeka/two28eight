<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resi Pesanan #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.5;
        }
        .container {
            width: 300px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2, h3 {
            margin-top: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .details, .items, .footer {
            margin-bottom: 15px;
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
        }
        .items th, .items td {
            padding: 8px 0;
            border-bottom: 1px dashed #ccc;
            text-align: left;
        }
        .items th {
            font-weight: bold;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Resi Pesanan</h2>
            <p>Nomor Pesanan: **{{ $order->order_number }}**</p>
        </div>

        <div class="details">
            <h3>Informasi Pembeli</h3>
            <p><strong>Nama:</strong> {{ $order->nama }}</p>
            <p><strong>Alamat:</strong> {{ $order->alamat }}, {{ $order->kota }}</p>
            <p><strong>Telepon:</strong> {{ $order->telepon }}</p>
        </div>

        <div class="items">
            <h3>Detail Produk</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->name }} ({{ $item->size }})</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p class="total">Total Pembayaran: Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
        </div>
    </div>
</body>
</html>