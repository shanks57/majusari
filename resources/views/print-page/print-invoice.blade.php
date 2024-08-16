<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Cetak Nota</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
    <script type="text/javascript">
        window.print();
        window.addEventListener('afterprint', function () {
            window.location.href = '/sales';
        });

    </script>
</head>

<body class="h-full">
    <!-- Invoice -->
    <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto my-4 sm:my-10">
        <div class="mx-auto sm:w-11/12 lg:w-3/4">
            <!-- Card -->
            <div class="flex flex-col p-4 bg-white shadow-md sm:p-10 rounded-xl dark:bg-neutral-800">
                <!-- Grid -->
                <div class="flex justify-between">
                    <div>
                        <img class="object-cover"
                            src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxHEfHvhA3rxFqIbu4H7XXAqCuJqIXQBIFUQ&s"
                            alt="">

                        <h1 class="mt-2 ml-4 text-lg font-semibold text-gray-900 md:text-xl dark:text-white">Toko Emas
                            Maju Sari</h1>
                    </div>
                    <!-- Col -->

                    <div class="text-end">
                        <h2 class="text-2xl font-semibold text-gray-800 md:text-3xl ">Nota #</h2>
                        <span class="block mt-1 text-gray-500 ">{{ $sale->nota }}</span>

                        <address class="mt-4 not-italic text-gray-800 ">
                            Pasar Besar, Jl. Pasar Besar Lantai Dasar,<br>
                            Sukoharjo, Klojen, Malang City,<br>
                            Jawa Timur 65118<br>
                        </address>
                    </div>
                    <!-- Col -->
                </div>
                <!-- End Grid -->

                <!-- Grid -->
                <div class="grid gap-3 mt-8 sm:grid-cols-2">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 ">Pembayaran kepada:</h3>
                        <h3 class="text-lg font-semibold text-gray-800 ">{{ $sale->transaction->customer->name }}</h3>
                        <address class="mt-2 not-italic text-gray-500 ">
                            {{ $sale->transaction->customer->phone }},<br>
                            {{ $sale->transaction->customer->address }}<br>
                        </address>
                    </div>
                    <!-- Col -->

                    <div class="space-y-2 sm:text-end">
                        <!-- Grid -->
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-1 sm:gap-2">
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 ">Tanggal nota:</dt>
                                <dd class="col-span-2 text-gray-500 ">
                                    {{ Carbon\Carbon::parse($sale->transaction->date)->translatedFormat('j F Y') }}
                                </dd>
                            </dl>
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 ">Sales:</dt>
                                <dd class="col-span-2 text-gray-500 ">
                                    {{ $sale->transaction->user->name }}
                                </dd>
                            </dl>
                        </div>
                        <!-- End Grid -->
                    </div>
                    <!-- Col -->
                </div>
                <!-- End Grid -->

                <!-- Table -->
                <div class="mt-6">
                    <div class="p-4 space-y-4 border border-gray-200 rounded-lg dark:border-neutral-700">
                        <div class="hidden sm:grid sm:grid-cols-5">
                            <div class="text-xs font-medium text-gray-500 ">Nama Barang</div>
                            <div class="text-xs font-medium text-gray-500 text-start ">Kategori</div>
                            <div class="text-xs font-medium text-gray-500 text-start ">Berat & Kadar</div>
                            <div class="text-xs font-medium text-gray-500 text-start ">Warna</div>
                            <div class="text-xs font-medium text-gray-500 text-end ">Jumlah</div>
                        </div>

                        <div class="hidden border-b border-gray-200 sm:block dark:border-neutral-700"></div>

                        <div class="grid grid-cols-3 gap-2 sm:grid-cols-5">
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden ">Nama Barang</h5>
                                <p class="font-medium text-gray-800 ">{{ $sale->goods->code }} -
                                    {{ $sale->goods->name }}</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden ">Kategori</h5>
                                <p class="text-gray-800 max-w-20 truncate">{{ $sale->goods->goodsType->name }}</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden ">Berat & Kadar</h5>
                                <p class="text-gray-800 ">{{ $sale->goods->size }}gr- {{ $sale->goods->rate }}%</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden ">Warna</h5>
                                <p class="text-gray-800 ">{{ $sale->goods->color }}</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-medium text-gray-500 uppercase sm:hidden ">Jumlah</h5>
                                <p class="text-gray-800 sm:text-end">
                                    {{ 'Rp ' . number_format($sale->harga_jual, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="border-b border-gray-200 sm:hidden dark:border-neutral-700"></div>
                    </div>
                </div>
                <!-- End Table -->

                <!-- Flex -->
                <div class="flex mt-8 sm:justify-end">
                    <div class="w-full max-w-2xl space-y-2 sm:text-end">
                        <!-- Grid -->
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-1 sm:gap-2">
                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 ">Total:</dt>
                                <dd class="col-span-2 text-gray-500 ">
                                    {{ 'Rp ' . number_format($sale->harga_jual, 0, ',', '.') }}</dd>
                            </dl>

                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 ">PPN:</dt>
                                <dd class="col-span-2 text-gray-500 ">Rp. 0</dd>
                            </dl>

                            <dl class="grid sm:grid-cols-5 gap-x-3">
                                <dt class="col-span-3 font-semibold text-gray-800 ">Jumlah yang dibayarkan:</dt>
                                <dd class="col-span-2 text-gray-500 ">
                                    {{ 'Rp ' . number_format($sale->harga_jual, 0, ',', '.') }}</dd>
                            </dl>
                        </div>
                        <!-- End Grid -->
                    </div>
                </div>
                <!-- End Flex -->

                <div class="mt-8 sm:mt-12">
                    <h4 class="text-lg font-semibold text-gray-800 ">Terima kasih!</h4>
                    <p class="text-gray-500 ">Jika Anda memiliki pertanyaan mengenai faktur ini, gunakan informasi
                        kontak berikut:</p>
                    <div class="mt-2">
                        <p class="block text-sm font-medium text-gray-800 ">tokoemasmajusari.com</p>
                        <p class="block text-sm font-medium text-gray-800 ">(0341) 361271</p>
                    </div>
                </div>
            </div>
            <!-- End Card -->

        </div>
    </div>
    <!-- End Invoice -->
</body>

</html>
