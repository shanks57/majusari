<!-- <style>
        /* Set the page size for printing */
        @page {
            size: 351.028mm 210.566mm;
            /* width height for landscape */
            margin: 0;
        }

        /* Hide non-print elements when printing */
        @media print {
            body {
                margin: 0;
            }

            /* Force the content to fit exactly in the printable area */
            .printable {
                width: 241mm;
                height: 105mm;
                margin: 0;
            }

            /* Hide elements that are unnecessary for printing */
            .no-print {
                visibility: hidden;
            }
        }
    </style> -->

<div class="p-10 mx-auto bg-white print-container" style="zoom:70%">
    <!-- Header Section -->
    <div class="grid grid-cols-2 gap-4">
        <!-- Left Logo and Store Info -->
        <div class="no-print">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxHEfHvhA3rxFqIbu4H7XXAqCuJqIXQBIFUQ&s"
                alt="Logo" class="w-24 mb-4 print:hidden">
            <h2 class="text-lg font-bold">Toko Emas Maju Sari</h2>
            <p class="text-sm">Lantai Dasar Blok 173-182 Pasar Besar, Malang</p>
            <p class="text-sm">Telp: (0341) 5015595</p>
            <p class="text-sm">Kritik & Saran hubungi: 08222924500</p>

            <!-- Social Media -->
            <div class="flex items-center mt-2 space-x-4">
                <a href="#" class="text-teal-600">@maju_sari</a>
                <a href="#" class="text-teal-600">www.tokoemasmajusari.com</a>
            </div>
        </div>

        <!-- Right Info Section -->
        <div class="text-right">
            <h2 class="text-2xl font-bold text-teal-500"><span class="no-print">NOTA :</span> {{ $transaction->code }}</h2>
            <div class=>
                <p class="mt-2"><strong class="no-print">Tanggal:</strong>
                    {{ Carbon\Carbon::parse($transaction->date)->translatedFormat('j F Y') }}
                </p>
                <p><strong class="no-print">Nama:</strong> {{ $transaction->customer->name }}</p>
                <p><strong class="no-print">Alamat:</strong> {{ $transaction->customer->address }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-2 mb-6 pl-20">
        <div class="col-span-4">
            <div class="grid grid-cols-2 w-[9cm]">
                @foreach($sales as $sale)
                <div class="p-1">
                    <img src="{{ asset('storage/' . $sale->goods->image) }}" alt="{{ $sale->goods->name }}"
                        class="object-cover w-32 h-32 bg-gray-500 mx-auto">
                    <p class="mt-1 text-center no-print">{{ $sale->goods->name }}</p>
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-span-8">
            <!-- Item Table Section -->
            <table class="w-full mb-6 table-auto mt-6">
                <thead class="text-white bg-teal-500 no-print">
                    <tr>
                        <th class="p-2 text-left">Kode Barang</th>
                        <th class="p-2 text-left">Nama Barang</th>
                        <th class="p-2 text-left">Kadar</th>
                        <th class="p-2 text-left">Berat</th>
                        <th class="p-2 text-right">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <!-- Item Rows (can be dynamically generated) -->
                    <tr class="">
                        <td class="p-2">{{ $sale->goods->code }}</td>
                        <td class="p-2">{{ $sale->goods->name }}</td>
                        <td class="p-2">{{ $sale->goods->rate }}%</td>
                        <td class="p-2">{{ $sale->goods->size }}gr </td>
                        <td class="p-2 text-right">
                            {{ 'Rp ' . number_format($sale->harga_jual - $sale->goods->goodsType->additional_cost, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                    <!-- Additional rows can be added -->
                </tbody>
            </table>

            <!-- Total and Signature Section -->
            <div class="flex justify-between mb-4">
                <div class="flex-1 text-sm no-print">
                    <p><strong>Penting:</strong> Ketetapan tentang jual beli emas ada di belakang nota, mohon untuk
                        diperhatikan.</p>
                </div>
                <div class="flex-1 text-right">
                    <p><strong>Total:</strong> {{ 'Rp ' . number_format($transaction->total, 0, ',', '.') }}</p>
                    <p><strong>Pegawai:</strong> {{ $transaction->user->name }}</p>
                </div>
            </div>

            <div class="flex flex-col justify-center p-2 mt-2">
                <img class="h-10 max-w-xs mx-auto" src="data:image/png;base64,{{ DNS1D::getBarcodePNG($transaction->code, 'C128') }}"
                    alt="{{ $transaction->code }}">
                <p class="mt-2 text-center">TRX-ID : {{ $transaction->code }}</p>
            </div>

            <!-- Footer Message -->
            <div class="mt-4 text-center text-teal-600 print:hidden">
                <p>Terima kasih atas kunjungan anda</p>
                <p>Semoga anda banyak rejeki</p>
            </div>
        </div>
    </div>

</div>