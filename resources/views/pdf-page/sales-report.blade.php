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
                <th>Nama Barang</th>
                <th>Kadar</th>
                <th>Nilai Tukar Jual</th>
                <th>Nilai Tukar Bawah</th>
                <th>Berat</th>
                <th>Harga Jual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>
                        {{ $sale->code }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}
                    </td>
                    <td>
                        @foreach($sale->details as $detail)
                            {{ $detail->goods->name }}
                        @endforeach
                    </td>
                    <td>
                        @foreach($sale->details as $detail)
                            {{ $detail->goods->rate }}%
                        @endforeach
                    </td>
                    <td>
                        @foreach($sale->details as $detail)
                            {{ number_format($detail->goods->ask_rate, 0) }}%
                        @endforeach
                    </td>
                    <td>
                        @foreach($sale->details as $detail)
                            {{ number_format($detail->goods->bid_rate, 0) }}%
                        @endforeach
                    </td>
                    <td>
                        @foreach($sale->details as $detail)
                            {{ number_format($detail->goods->size, 2) }}gr
                        @endforeach
                    </td>
                    <td>
                        @foreach($sale->details as $detail)
                            {{ 'Rp.' . number_format($detail->goods->ask_price, 0, ',', '.') }}
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
