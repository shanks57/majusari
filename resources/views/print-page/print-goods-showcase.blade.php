<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Emas Majusari - Print Data Barang Etalase</title>
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
            window.location.href = '/goods/showcases';
        });

    </script>
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }
        }
    </style>
    @vite('resources/css/app.css')
</head>
<body class="text-black bg-white">
    <div class="container p-4 mx-auto">
        <h2 class="mb-6 text-2xl font-bold text-center">Data Barang Etalase</h2>
        
        <table class="w-full bg-white border">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Kode Barang</th>
                    <th class="px-4 py-2 border">Tanggal Masuk</th>
                    <th class="px-4 py-2 border">Gambar</th>
                    <th class="px-4 py-2 border">Barang</th>
                    <th class="px-4 py-2 border">Berat & Kadar</th>
                    <th class="px-4 py-2 border">Kategori</th>
                    <th class="px-4 py-2 border">Harga Jual</th>
                    <th class="px-4 py-2 border">Harga Bawah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($goodsShowcase as $goods)
                   
                    <tr>
                        <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border">{{ $goods->code }}</td>
                        <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($goods->date_entry)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 border">
                             <img src="{{ asset('storage/' . $goods->image) }}" class="size-10"
                                    alt="{{ $goods->name }}">
                        </td>
                        <td class="px-4 py-2 border">{{ $goods->code }}</td>
                        <td class="px-4 py-2 border">{{ $goods->size }} gr - {{ $goods->rate }}%</td>
                        <td class="px-4 py-2 border">{{ $goods->goodsType->name }}</td>
                        <td class="px-4 py-2 border">{{ 'Rp.' . number_format($goods->ask_price, 0, ',', '.') }} - {{ $goods->ask_rate }}%</td>
                        <td class="px-4 py-2 border">{{ 'Rp.' . number_format($goods->bid_price, 0, ',', '.') }} - {{ $goods->bid_rate }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
