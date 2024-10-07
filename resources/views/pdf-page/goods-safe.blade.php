<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Emas Majusari - Barang Brankas</title>
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
    <h2 class="title">Data Barang Brankas</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Tanggal Masuk</th>
                <th>Gambar</th>
                <th>Barang</th>
                <th>Berat & Kadar</th>
                <th>Kategori</th>
                <th>Harga Jual</th>
                <th>Harga Bawah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($goodsSafe as $goods)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $goods->code }}</td>
                <td>{{ \Carbon\Carbon::parse($goods->date_entry)->format('d/m/Y') }}</td>
                <td>
                    <img src="{{ public_path('storage/' . $goods->image) }}" alt="{{ $goods->name }}" class="rounded-full size-10">
                </td>
                <td>{{ $goods->code }}</td>
                <td>{{ $goods->size }} gr - {{ $goods->rate }}%</td>
                <td>{{ $goods->goodsType->name }}</td>
                <td>{{ 'Rp.' . number_format($goods->ask_price, 0, ',', '.') }} - {{ $goods->ask_rate }}%</td>
                <td>{{ 'Rp.' . number_format($goods->bid_price, 0, ',', '.') }} - {{ $goods->bid_rate }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
