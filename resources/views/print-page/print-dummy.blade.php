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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
        }

        .header .info {
            text-align: left;
        }

        .header .date {
            text-align: right;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .images {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            margin: 20px 0;
        }

        .images img {
            width: 100px;
            height: auto;
            border: 1px solid #000;
        }

        .total-section {
            text-align: right;
            margin-top: 20px;
        }

        .barcode {
            text-align: center;
            margin-top: 20px;
        }

        .barcode img {
            width: 200px;
            height: auto;
        }
    </style>

</head>

<body>
    <!-- Invoice -->
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="info">
                <p>Raditya Dika</p>
                <p>Jln Bunga Bangkai no 123</p>
            </div>
            <div class="date">
                <p>16 December 2024</p>
            </div>
        </div>

        <!-- Images Section -->
        <div class="images">
            <img src="image1.jpg" alt="Product Image">
            <img src="image2.jpg" alt="Product Image">
        </div>

        <!-- Table Section -->
        <table class="table">
            <thead>
                <tr>
                    <th>Qty</th>
                    <th>Nama Barang</th>
                    <th>Kadar</th>
                    <th>Berat</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>105</td>
                    <td>KalungTes</td>
                    <td>70%</td>
                    <td>3.33gr</td>
                    <td>Rp 2.500.000</td>
                </tr>
                <tr>
                    <td>102</td>
                    <td>KalungTes</td>
                    <td>70%</td>
                    <td>3.33gr</td>
                    <td>Rp 4.000.000</td>
                </tr>
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            <p><strong>Total:</strong> Rp 6.500.000</p>
        </div>

        <!-- Barcode Section -->
        <div class="barcode">
            <img src="barcode.png" alt="Barcode">
            <p>TRX-ID : 58306</p>
        </div>

        <!-- Footer Section -->
        <div class="footer" style="text-align: right;">
            <p>Pegawai Rumah</p>
        </div>
    </div>
    <!-- End Invoice -->
</body>

</html>