<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Emas Majusari - Print Data Penjualan</title>
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
            window.location.href = '/sales';
        });

    </script>
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 0;
            }
        }
    </style>
    @vite('resources/css/app.css')
</head>
<body class="text-black bg-white">
    <div class="container p-4 mx-auto">
        <h2 class="mb-6 text-2xl font-bold text-center">Data Penjualan</h2>
        
        <table class="w-full bg-white border">
            <thead>
                <tr>
                    <th>Nota</th>
                    <th class="px-4 py-2 border">Tanggal Penjualan</th>
                    <th class="px-4 py-2 border">ID & Nama</th>
                    <th class="px-4 py-2 border">Berat & Kadar</th>
                    <th class="px-4 py-2 border">Harga Jual</th>
                    <th class="px-4 py-2 border">Harga Bawah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td class="px-4 py-2 border">{{ $sale->nota }}</td>
                        <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($sale->transaction->date)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 border">{{ $sale->goods->code }} - {{ $sale->goods->name }}</td>
                        <td class="px-4 py-2 border">{{ $sale->goods->size }} gr - {{ $sale->goods->rate }}%</td>
                        <td class="px-4 py-2 border">{{ 'Rp.' . number_format($sale->goods->ask_price, 0, ',', '.') }} - {{ $sale->goods->ask_rate }}%</td>
                        <td class="px-4 py-2 border">{{ 'Rp.' . number_format($sale->goods->bid_price, 0, ',', '.') }} - {{ $sale->goods->bid_rate }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
