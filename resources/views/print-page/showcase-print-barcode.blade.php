<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Emas Majusari - Cetak Barcode</title>
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
    <style>
        @page {
            size: 25.4mm 21mm;

        }

        @media print {
            body {
                size: 25.4mm 21mm;
                max-width: 100%;
                margin: 0px;
                font-family: Arial, sans-serif;
                text-align: left;
            }

            .barcode img {
                padding: 0.1cm;
                width: 90%;
                height: 0.9cm !important;
                margin: 0px !important;
            }

            .info {
                width: 100%;
                text-align: center;
                font-size: 0.3cm;
                margin: 0cm !important;
            }

            .info-container {
                margin-top: -0.2cm;
            }

        }
    </style>
    <script type="text/javascript">
        window.print();
        window.addEventListener('afterprint', function() {
            window.location.href = '/goods/showcases';
        });
    </script>
</head>

<body>
    <div>
        <div class="barcode">
            <img src="data:image/png;base64,{{ $barcode }}" alt="{{ $goodShowcase->id }}">
        </div>
        <div class="info-container">
            <p class="info">{{ $goodShowcase->code }}</p>
            <p class="info">{{ $goodShowcase->rate }}% | <span>{{ $goodShowcase->category }} </span></p>
            <p class="info">{{ $goodShowcase->size }}gr</p>

        </div>
    </div>
</body>

</html>