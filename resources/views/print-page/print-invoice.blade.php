<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Emas Majusari - Cetak Nota</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    @stack('styles')
    <script type="text/javascript">
        window.print();
        window.addEventListener('afterprint', function() {
            window.location.href = '/sales';
        });
    </script>
</head>

<body class="h-full">
    <!-- Invoice -->
    <div class="max-w-3xl mx-auto bg-white p-8">

        <!-- Header Section -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <!-- Left Logo and Store Info -->
            <div>
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxHEfHvhA3rxFqIbu4H7XXAqCuJqIXQBIFUQ&s" alt="Logo" class="w-24 mb-4">
                <h2 class="text-lg font-bold">Toko Emas Maju Sari</h2>
                <p class="text-sm">Lantai Dasar Blok 173-182 Pasar Besar, Malang</p>
                <p class="text-sm">Telp: (0341) 5015595</p>
                <p class="text-sm">Kritik & Saran hubungi: 08222924500</p>

                <!-- Social Media -->
                <div class="flex items-center space-x-4 mt-2">
                    <a href="#" class="text-teal-600">@maju_sari</a>
                    <a href="#" class="text-teal-600">www.tokoemasmajusari.com</a>
                </div>
            </div>

            <!-- Right Info Section -->
            <div class="text-right">
                <h2 class="text-2xl font-bold text-teal-500">NOTA : {{ $sale->nota }}</h2>
                <p class="mt-2"><strong>Tanggal:</strong> {{ Carbon\Carbon::parse($sale->transaction->date)->translatedFormat('j F Y') }}</p>
                <p><strong>Nama:</strong> {{ $sale->transaction->customer->name }}</p>
                <p><strong>Alamat:</strong> {{ $sale->transaction->customer->address }}</p>
            </div>
        </div>

        <!-- Item Table Section -->
        <table class="w-full table-auto mb-6">
            <thead class="bg-teal-500 text-white">
                <tr>
                    <th class="p-2 text-left">QTY</th>
                    <th class="p-2 text-left">Nama Barang</th>
                    <th class="p-2 text-left">Kadar</th>
                    <th class="p-2 text-left">Berat</th>
                    <th class="p-2 text-left">Harga</th>
                </tr>
            </thead>
            <tbody>
                <!-- Item Rows (can be dynamically generated) -->
                <tr class="border-b">
                    <td class="p-2">1</td>
                    <td class="p-2">{{ $sale->goods->name }} - {{ $sale->goods->code }}</td>
                    <td class="p-2">{{ $sale->goods->rate }}%</td>
                    <td class="p-2">{{ $sale->goods->size }}gr </td>
                    <td class="p-2">{{ 'Rp ' . number_format($sale->harga_jual - $sale->goods->goodsType->additional_cost, 0, ',', '.') }}</td>
                </tr>
                <tr class="border-b">
                    <td class="p-2">1</td>
                    <td class="p-2">{{ $sale->goods->name }} - {{ $sale->goods->code }}</td>
                    <td class="p-2">{{ $sale->goods->rate }}%</td>
                    <td class="p-2">{{ $sale->goods->size }}gr </td>
                    <td class="p-2">{{ 'Rp ' . number_format($sale->harga_jual - $sale->goods->goodsType->additional_cost, 0, ',', '.') }}</td>
                </tr>
                <!-- Additional rows can be added -->
            </tbody>
        </table>

        <!-- Total and Signature Section -->
        <div class="flex justify-between items-center mb-4">
            <div>
                <p><strong>Penting:</strong> Ketetapan tentang jual beli emas ada di belakang nota, mohon untuk diperhatikan.</p>
            </div>
            <div class="text-right">
                <p><strong>Total:</strong> {{ 'Rp ' . number_format($sale->harga_jual, 0, ',', '.') }}</p>
                <p><strong>Pegawai:</strong> {{ $sale->transaction->user->name }}</p>
            </div>
        </div>

        <!-- Footer Message -->
        <div class="text-center text-teal-600">
            <p>Terima kasih atas kunjungan anda</p>
            <p>Semoga anda banyak rejeki</p>
        </div>
    </div>
    <!-- End Invoice -->
</body>

</html>