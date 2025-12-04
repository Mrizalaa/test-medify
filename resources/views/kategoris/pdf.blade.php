<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Kategori - {{ $kategori->kode }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h1, h2, h3, h4 {
            margin: 0;
            padding: 0;
        }

        .header {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #000;
        }

        .info {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        table th {
            background: #f0f0f0;
        }

        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            right: 0;
            font-size: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Detail Kategori Items</h2>
</div>

<div class="info">
    <p><strong>Nama Kategori:</strong> {{ $kategori->nama }}</p>
    <p><strong>Kode Kategori:</strong> {{ $kategori->kode }}</p>
</div>

<h3>Daftar Items dalam Kategori Ini</h3>

@if($items->isEmpty())
    <p><em>Tidak ada item yang terhubung dengan kategori ini.</em></p>
@else
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Kode Item</th>
                <th style="width: 30%;">Nama Item</th>
                <th style="width: 15%;">Jenis</th>
                <th style="width: 20%;">Supplier</th>
                <th style="width: 20%;">Harga Beli</th>
            </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->jenis }}</td>
                <td>{{ $item->supplier }}</td>
                <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

<div class="footer">
    Dicetak pada: {{ $printed_at->format('d-m-Y H:i:s') }}
</div>

</body>
</html>
