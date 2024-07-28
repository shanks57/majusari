<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Emas Majusari -     Cetak Barcode</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .print-section {
            width: 80%;
            margin: 0 auto;
        }
        .barcode img {
            height: 10px;
        }
        .info {
            margin-top: 1px;
            font-size: 10px;
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="print-section">
        <div class="barcode">
            <img src="data:image/png;base64,{{ $barcode }}" alt="{{ $goodShowcase->id }}">
        </div>
        <div class="info">
            <span>{{ $goodShowcase->code }}</span>
            <br>
            <span>{{ $goodShowcase->rate }} % | {{ $goodShowcase->size }} gr</span>
        </div>
    </div>
</body>
</html>
