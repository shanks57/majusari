<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Emas Majusari - Etalase</title>
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

    </style>
</head>
<body>
    <h2 class="title">Data Etalase</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Etalase</th>
                <th>Nama Etalase</th>
                <th>Jenis Barang</th>
                <th>Jumlah Baki</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($showcases as $showcase)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $showcase->code }}</td>
                <td>{{ $showcase->name }}</td>
                <td>{{ $showcase->goodsType->name }}</td>
                <td>{{ $showcase->trays_count }}</td>
                <td>{{ $showcase->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
