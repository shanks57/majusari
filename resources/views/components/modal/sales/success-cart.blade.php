<div x-data="{ open: @js(session('success-cart') ? true : false) }" x-show="open"
    class="fixed inset-0 z-50 flex items-start justify-end bg-gray-800 bg-opacity-50" style="display: none;">

    <div
        class="flex flex-col max-h-screen p-6 m-8 overflow-y-auto bg-white shadow-lg pointer-events-auto md:max-w-xl md:w-full gap-y-4 rounded-xl">
        <div class="flex items-center justify-between px-4">
            <h3 id="hs-medium-modal-label" class="text-xl font-semibold text-[#232323] font-inter">
                Penjualan Baru
            </h3>
        </div>
        <div class="border-b border-[#D0D5DD]"></div>
        <div class="flex flex-col items-center justify-center my-4">
            <svg width="120" height="120" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="4" y="4" width="82" height="82" rx="41" fill="#75FFC5" />
                <rect x="4" y="4" width="82" height="82" rx="41" stroke="#C9FFE8" stroke-width="8" />
                <path
                    d="M61.6783 36.7411L40.6783 57.7411C40.5565 57.8631 40.4117 57.9599 40.2524 58.026C40.093 58.092 39.9222 58.126 39.7498 58.126C39.5773 58.126 39.4065 58.092 39.2471 58.026C39.0878 57.9599 38.9431 57.8631 38.8212 57.7411L29.6337 48.5536C29.3874 48.3073 29.249 47.9733 29.249 47.625C29.249 47.2767 29.3874 46.9427 29.6337 46.6964C29.8799 46.4501 30.214 46.3118 30.5623 46.3118C30.9105 46.3118 31.2446 46.4501 31.4908 46.6964L39.7498 54.957L59.8212 34.8839C60.0674 34.6376 60.4015 34.4993 60.7498 34.4993C61.098 34.4993 61.4321 34.6376 61.6783 34.8839C61.9246 35.1302 62.063 35.4642 62.063 35.8125C62.063 36.1608 61.9246 36.4948 61.6783 36.7411Z"
                    fill="white" />
            </svg>
            <p class="mt-4 text-xl font-medium text-black font-inter">{{ session('success-cart') }}</p>
        </div>
        <div class="p-4 border">
            <div class="border rounded-lg border-[#E5E5E5] mb-6 text-sm text-[#151617]">
                <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                    <p class="mb-1">Foto</p>
                    <img class="size-24 rounded-xl" src="{{ asset('storage/' . session('good-image-cart')) }}"
                        alt="{{ session('good-name-cart') }}">
                </div>
                <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                    <p class="mb-1">Barang & Merek</p>
                    <p>{{ session('good-name-cart') }} - {{ session('good-color-cart') }}</p>
                    <p class="max-w-xs font-bold truncate">{{ session('good-merk-cart') }}</p>
                </div>
                <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                    <p class="mb-1">Berat & Kadar</p>
                    <p>{{ session('good-size-cart')}} gr <span
                            class="ml-1 text-xs text-black rounded-xl bg-[#F1F1F1] px-2">{{ session('good-rate-cart')}}%</span>
                    </p>
                </div>
                <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                    <p class="mb-1">Kategori</p>
                    <p class="max-w-xs truncate">{{ session('good-type-cart') }}</p>
                </div>
                <div class="w-full px-3.5 py-2.5 border-b border-[#E5E5E5]">
                    <p class="mb-1">Tempat</p>
                    <p>{{ session('good-showcase-cart') }} Baki {{ session('good-tray-cart') }}</p>
                </div>
                <div class="w-full px-3.5 py-2.5">
                    <p class="mb-1">Harga</p>
                    <p>Rp {{ number_format(session('new_selling_price'), 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="flex items-center justify-center gap-4">
                <button type="submit"
                    class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-scale-animation-modal" data-hs-overlay="#hs-add-modal" @click="open = false">
                    Tambahkan Lainnya
                    <i class="ph ph-plus ml-1.5"></i>
                </button>
                <a href="{{route('pages.cart')}}"
                    class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg text-[#8d8e91] border-[#D0D5DD] border bg-white">
                    Tutup
                    <i class="ph ph-x ml-1.5"></i>
                </a>
            </div>
        </div>
    </div>
</div>
