<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Emas Majusari - Laporan Penjualan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .title {
            margin-bottom: 1rem; /* mb-4 */
            font-size: 1.5rem; /* text-2xl */
            font-weight: 700; /* font-bold */
            text-align: center; /* text-center */
        }
         .img-rounded-full {
            border-radius: 9999px;
        }

        .size-10 {
            width: 40px;
            height: 40px;
        }

    </style>
</head>
<body>
    <h2 class="title">Data Laporan Penjualan</h2>
    <table>
        <thead>
            <tr>
                <th>Nota</th>
                <th>Tanggal Penjualan</th>
                <th>ID & Nama</th>
                <th>Berat & Kadar</th>
                <th>Harga Jual</th>
                <th>Harga Bawah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->nota }}</td>
                <td>{{ \Carbon\Carbon::parse($sale->transaction->date)->format('d/m/Y') }}</td>
                <td>{{ $sale->goods->code }} - {{ $sale->goods->name }}</td>
                <td>{{ $sale->goods->size }} gr - {{ $sale->goods->rate }}%</td>
                <td>{{ 'Rp.' . number_format($sale->goods->ask_price, 0, ',', '.') }} - {{ $sale->goods->ask_rate }}%</td>
                <td>{{ 'Rp.' . number_format($sale->goods->bid_price, 0, ',', '.') }} - {{ $sale->goods->bid_rate }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
