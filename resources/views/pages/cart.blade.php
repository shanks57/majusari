@section('title', 'Cart')
<x-layout>
    <x-header title="Penjualan">
    </x-header>


    <div class="grid grid-cols-4 gap-5 mt-6">
        <div class="col-span-3">
            <div class="rounded-xl border overflow-hidden">
                <div class="flex justify-between items-start bg-white p-3">
                    <div class="flex gap-3 items-center">
                        <img class="rounded-lg" width="96" height="96" src="https://placehold.co/400" alt="GoodsImage">
                        <div class="grid gap-1">
                            <span class="text-sm">Barang & Merek</span>
                            <p class="text-lg">CC - Gold</p>
                            <span>Saputra Budi Utama</span>
                        </div>
                    </div>
                    <button class="text-red-500 text-sm flex items-center gap-1 px-2 py-1 bgd hover:bg-gray-100 rounded">
                        <i class="ph ph-trash"></i>
                        Hapus
                    </button>
                </div>
                <div class="bg-gray-100 p-3 flex gap-12">
                    <div class="grid gap-3">
                        <span class="text-sm">Berat & Kadar</span>
                        <div class="flex gap-1 items-center">
                            <span>0.96gr</span>
                            <span class="text-xs py-1 px-2 bg-gray-200 rounded-full">70%</span>
                        </div>
                    </div>
                    <div class="grid gap-3">
                        <span class="text-sm">Tempat</span>
                        <span>CC BAKI CC1065</span>
                    </div>
                    <div class="grid gap-3">
                        <span class="text-sm">Harga</span>
                        <span>Rp. 2.590.000</span>
                    </div>
                    <div class="grid gap-3">
                        <span class="text-sm">Kategori</span>
                        <span>Cincin</span>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-span-1">
            <div class="rounded-xl border p-4 bg-white grid gap-3">
                <div class="grid gap-1">
                    <span class="text-gray-600 text-sm">Tanggal Transaksi</span>
                    <p class="text-lg">26 Juni 2024</p>
                </div>
                <div class="grid gap-1">
                    <span class="text-gray-600 text-sm">Total Berat</span>
                    <p class="text-lg">0.96gr</p>
                </div>
                <div class="grid gap-1">
                    <span class="text-gray-600 text-sm">Total Penjualan</span>
                    <p class="text-lg">Rp. 2.590.000</p>
                </div>

                <button class="w-full py-3 text-white bg-purple-700 rounded-lg text-sm">
                    Selanjutnya
                </button>

            </div>
        </div>
    </div>

</x-layout>