<div x-data="{ open: @js(session('modal-form') ? true : false), form: { new_selling_price: ''} }" x-show="open"
    class="fixed inset-0 z-50 flex items-start justify-end bg-gray-800 bg-opacity-50" style="display: none;">

    <div class="flex flex-col max-h-screen p-6 m-8 overflow-y-auto bg-white shadow-lg pointer-events-auto md:max-w-2xl md:w-full gap-y-4 rounded-xl">
        <div class="flex items-center justify-between px-4">
            <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                Penjualan Baru
            </h3>
            <button @click="open = false" type="button" class="text-red-500">
                <i class="text-2xl ph ph-x-circle"></i>
            </button>
        </div>
        <div class="border-b border-[#D0D5DD]"></div>
        <form action="{{ route('cart.insert-to-cart') }}" method="post">
            @csrf
            <input type="hidden" name="goods_id" value="{{ session('good-id-form') }}">
            <input type="hidden" name="ask_price" value="{{ session('good-price-form') }}">
            <input type="hidden" name="tray_id" value="{{ session('good-tray-id-form') }}">
            <div class="p-4 overflow-y-auto border">
                <div class="border rounded-lg border-[#E5E5E5] mb-6 text-sm text-[#151617]">
                    <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                        <p class="mb-1">Barang & Merek</p>
                        <p>{{ session('good-name-form') }} - {{ session('good-color-form') }}</p>
                        <p class="max-w-xs font-bold truncate">{{ session('good-merk-form') }}</p>
                    </div>
                    <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                         <p class="mb-1">Berat & Kadar</p>
                         <p>{{ session('good-size-form')}} gr <span class="ml-1 text-xs text-black rounded-xl bg-[#F1F1F1] px-2">{{ session('good-rate-form')}}%</span></p>
                    </div>
                    <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                         <p class="mb-1">Kategori</p>
                         <p class="max-w-xs truncate">{{ session('good-type-form') }}</p>
                         <p class="mb-1">Tambahan Biaya</p>
                         <p class="max-w-xs font-bold truncate">
                            Rp. {{ number_format(session('good-type-additional-cost-form'), 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                         <p class="mb-1">Tempat</p>
                         <p>{{ session('good-showcase-form') }} Baki {{ session('good-tray-form') }}</p>
                    </div>
                    <div class="w-full px-3.5 py-2.5">
                         <p class="mb-1">Foto</p>
                        <img class="size-24 rounded-xl" src="{{ asset('storage/' . session('good-image-form')) }}" alt="{{ session('good-name-form') }}">
                    </div>
                </div>
                <div class="w-full px-3.5 py-2 border rounded-lg border-[#E5E5E5]">
                    <div class="mb-3">
                        <label for="new_selling_price" class="block text-sm text-[#344054] border-[#E5E5E5]">Masukkan Harga</label>
                        <input type="number" id="new_selling_price" name="new_selling_price" x-model="form.new_selling_price"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Contoh 100000" required>
                        @error('new_selling_price')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex items-center justify-end gap-x-2">
                        <button type="submit" :disabled="!form.new_selling_price"
                            class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white"
                            :class="{ 'opacity-50 cursor-not-allowed': !form.new_selling_price}">
                            Selanjutnya
                            <i class="ph ph-arrow-right ml-1.5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
