<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Emas Majusari - Print Data Etalase</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <script type="text/javascript">
        window.print();
        window.addEventListener('afterprint', function () {
            window.location.href = '/master/showcases';
        });

    </script>
    <style>
        @media print {
            @page {
                size: portrait;
                margin: 0;
            }
        }
    </style>
    @vite('resources/css/app.css')
</head>
<body class="text-black bg-white">
    <div class="container p-4 mx-auto">
        <h2 class="mb-6 text-2xl font-bold text-center">Data Pelanggan</h2>
        
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Kode Etalase</th>
                    <th class="px-4 py-2 border">Nama Etalase</th>
                    <th class="px-4 py-2 border">Jenis Barang</th>
                    <th class="px-4 py-2 border">Jumlah Baki</th>
                    <th class="px-4 py-2 border">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($showcases as $showcase)
                    <tr>
                        <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border">{{ $showcase->code }}</td>
                        <td class="px-4 py-2 border">{{ $showcase->name }}</td>
                        <td class="px-4 py-2 border">{{ $showcase->goodsType->name }}</td>
                        <td class="px-4 py-2 border">{{ $showcase->trays_count }}</td>
                        <td class="px-4 py-2 border">{{ $showcase->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
