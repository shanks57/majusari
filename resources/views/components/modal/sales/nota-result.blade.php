<div x-data="{ open: @js(session('nota-result') ? true : false), form: { new_selling_price: ''} }" x-show="open"
    class="fixed inset-0 z-50 flex items-start justify-end bg-gray-800 bg-opacity-50" style="display: none;">

    <div
        class="flex flex-col max-h-screen p-6 m-8 overflow-x-auto overflow-y-auto bg-white shadow-lg pointer-events-auto md:max-w-md md:w-full gap-y-4 rounded-xl">
        <div class="flex items-center justify-between px-4">
            <h3 id="hs-medium-modal-label" class="text-xl font-semibold text-[#344054]">
                Nota Ditemukan
            </h3>
        </div>
        <div class="border-b border-[#D0D5DD]"></div>
        <div class="border rounded-lg border-[#E5E5E5] text-sm text-[#151617]">
            <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                <p class="mb-1">Kode Penjualan</p>
                <p>{{ session('nota-penjualan') }}</p>
            </div>
            <div class="w-full px-3.5 py-2.5">
                <p class="mb-1">Foto</p>
                <img class="size-24 rounded-xl" src="{{ asset('storage/' . session('nota-goods-image')) }}"
                    alt="{{ session('nota-good-name') }}">
            </div>
            <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                <p class="mb-1">Barang & Merek</p>
                <p>{{ session('nota-good-name') }} - {{ session('nota-good-color') }}</p>
                <p class="font-bold">{{ session('nota-good-merk') }}</p>
            </div>
            <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                <p class="mb-1">Berat & Kadar</p>
                <p>{{ session('nota-good-size')}} gr <span
                        class="ml-1 text-xs text-black rounded-xl bg-[#F1F1F1] px-2">{{ session('nota-good-rate')}}%</span>
                </p>
            </div>
            <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                <p class="mb-1">Kategori</p>
                <p>{{ session('nota-good-type') }}</p>
            </div>
            <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                <p class="mb-1">Tempat</p>
                <p>{{ session('nota-good-showcase') }} Baki {{ session('nota-good-tray') }}</p>
            </div>
            <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                <p class="mb-1">Harga</p>
                <p>Rp. {{ number_format(session('nota-harga-jual'), 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="flex items-center justify-center mb-4 gap-x-2">
            <a href="{{ route('sale.printNota', $sale->id) }}"
                class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white">
                Cetak
                <i class="ph ph-printer ml-1.5"></i>
            </a>
            <button type="button" @click="open = false"
                class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 text-gray-600 bg-gray-100 border border-gray-400 rounded-lg">
                Tutup
                <i class="ph ph-x ml-1.5"></i>
            </button>
        </div>
    </div>
</div>
