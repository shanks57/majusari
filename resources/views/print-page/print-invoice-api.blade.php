<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Emas Majusari - Cetak Nota</title>    
    <style>
    @media print {
        @page {
            size: 21.5cm 10.5cm landscape;
            margin: 0;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .print-container {
            width: 21.5cm;
            height: 10.5cm;
            padding: 1cm;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 0.2cm;
            font-size: 0.7rem;
        }

        .border-none {
            border: none;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    }

    .print-container {
        margin: auto;
        font-family: Arial, sans-serif;
        font-size: 0.7rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 0.2cm;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
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
    <table>
        <tr>
            <td colspan="2" class="border-none"></td>
            <td colspan="3" class="text-right">
                <p>{{ Carbon\Carbon::parse($transaction->date)->translatedFormat('j F Y') }}</p>
                <p>{{ $transaction->customer->name }}</p>
                <p>{{ $transaction->customer->address }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="width: 6cm;">
                <table>
                    @foreach ($sales->chunk(2) as $row)
                    <tr>
                        @foreach ($row as $sale)
                        <td style="width: 2.5cm; height: 2.5cm; padding: 0.1cm;">
                            <img src="{{ asset('app.tokoemasmajusarimalang.com/storage/' . $sale->goods->image) }}" alt="{{ $sale->goods->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </table>
            </td>
            <td colspan="3">
                <table>
                    @foreach($sales as $sale)
                    <tr>
                        <td style="">{{ $sale->goods->code }}</td>
                        <td style="width: 3cm;">{{ $sale->goods->name }}</td>
                        <td style="">{{ $sale->goods->rate }}%</td>
                        <td style="">{{ $sale->goods->size }}gr</td>
                        <td style="" class="text-right">{{ 'Rp ' . number_format($sale->harga_jual - $sale->goods->goodsType->additional_cost, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td class="text-right">
                <strong>{{ 'Rp ' . number_format($transaction->total, 0, ',', '.') }}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="border-none"></td>
            <td colspan="1" class="text-right">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($transaction->code, 'C128') }}" alt="{{ $transaction->code }}" style="height: 0.5cm;">
                <p>TRX-ID : {{ $transaction->code }}</p>
            </td>
            <td colspan="1" class="text-center">{{ $transaction->user->name }}</td>
        </tr>
        
    </table>
</div>
    <!-- End Invoice -->
</body>

</html>