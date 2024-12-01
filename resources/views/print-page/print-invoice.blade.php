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
    <style>
        /* CSS untuk tampilan cetak */
        @media print {
            @page {
                size: 21.5cm 10.5cm landscape;
                /* Ukuran halaman dan orientasi */
                margin: 0;
                /* Menghilangkan margin default browser */
            }

            body {
                margin: 0;
                /* Menghilangkan margin pada body */
                width: 21.5cm;
                height: 10.5cm;
                display: flex;
                justify-content: center;
                align-items: center;
                font-family: Arial, sans-serif;
            }

            .print-container {
                width: 100%;
                height: 100%;
                padding: 1cm;
                /* Padding dalam kertas */
                box-sizing: border-box;
            }
        }

        /* CSS untuk tampilan layar */
        .print-container {
            width: 21.5cm;
            height: 10.5cm;
            padding: 1cm;
            border: 1px solid #ddd;
            margin: auto;
            font-family: Arial, sans-serif;
            font-size: 0.7rem;
        }

        .text-small {
            font-size: 0.7rem;
            margin: 0px;
        }
    </style>

    <script type="text/javascript">
        window.print();
        window.addEventListener('afterprint', function() {
            window.location.href = '/sales';
        });
    </script>
</head>

<body>
    <!-- Invoice -->
    <div class="print-container">
        <div class="flex flex-col items-end">
            <div style="width: 2.5cm; margin-top: 1cm;" class="">
                <p class="text-small">
                    {{ Carbon\Carbon::parse($transaction->date)->translatedFormat('j F Y') }}
                </p>
                <p class="text-small">{{ $transaction->customer->name }}</p>
                <p class="text-small">{{ $transaction->customer->address }}</p>
            </div>
        </div>
        <div class="flex justify-between">
            <div style="width: 6cm; margin-top: -0.5cm; height: 4cm; margin-left: 2cm;" class="grid grid-cols-2">
                @foreach($sales as $sale)
                <div style="width: 2.5cm; height: 2.5cm; padding: 0.1cm;">
                    <img src="{{ asset('storage/' . $sale->goods->image) }}" alt="{{ $sale->goods->name }}"
                        class="object-cover w-full h-full bg-gray-200">
                </div>
                @endforeach
            </div>
            <div style="width: 12cm; margin-top: 1cm; height: 3cm;" class="">
                @foreach($sales as $sale)
                <!-- Item Rows (can be dynamically generated) -->
                <div class="flex mb-2">
                    <span style="width: 1.5cm; padding-left:0.2cm; padding-top:0.1cm;" class="text-wrap break-words">{{ $sale->goods->code }}</span>
                    <span style="width: 5cm; padding-left:0.2cm; padding-top:0.1cm;" class="text-wrap break-words">{{ $sale->goods->name }}</span>
                    <span style="width: 1.5cm; padding-left:0.2cm; padding-top:0.1cm;" class="text-wrap break-words">{{ $sale->goods->rate }}%</span>
                    <span style="width: 1.5cm; padding-left:0.2cm; padding-top:0.1cm;" class="text-wrap break-words ">{{ $sale->goods->size }}gr </span>
                    <span style="width: 2.5cm; padding-left:0.2cm; padding-top:0.1cm;" class="text-wrap break-words text-small">
                        {{ 'Rp ' . number_format($sale->harga_jual - $sale->goods->goodsType->additional_cost, 0, ',', '.') }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="flex flex-col items-end">
            <p class="font-bold text-wrap break-words text-small" style="width: 2.5cm; padding-left: 0.5cm; padding-top:0.3cm;">{{ 'Rp ' . number_format($transaction->total, 0, ',', '.') }}</p>

        </div>

        <div class="flex justify-end" style="margin-top: 0.7cm;">
            <div style="width: 2.5cm; margin-right:2cm;">
                <img style="height: 0.5cm;" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($transaction->code, 'C128') }}"
                    alt="{{ $transaction->code }}">
                <p class="text-small">TRX-ID : {{ $transaction->code }}</p>
            </div>
            <p class="text-center text-wrap break-words" style="width: 4cm; padding-top: 0.3cm; margin-left: -1cm">{{ $transaction->user->name }}</p>
        </div>

    </div>
    <!-- End Invoice -->
</body>

</html>