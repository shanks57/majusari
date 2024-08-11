<div x-data="{ open: @js(session('success-checkout') ? true : false) }" x-show="open"
    class="fixed inset-0 z-50 flex items-start justify-end bg-gray-800 bg-opacity-50" style="display: none;">

    <div
        class="flex flex-col max-h-screen p-6 m-8 overflow-y-auto bg-white shadow-lg pointer-events-auto md:max-w-md md:w-full gap-y-4 rounded-xl">
        <div class="flex flex-col items-center justify-center my-4">
            <svg width="120" height="120" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="4" y="4" width="82" height="82" rx="41" fill="#75FFC5" />
                <rect x="4" y="4" width="82" height="82" rx="41" stroke="#C9FFE8" stroke-width="8" />
                <path
                    d="M61.6783 36.7411L40.6783 57.7411C40.5565 57.8631 40.4117 57.9599 40.2524 58.026C40.093 58.092 39.9222 58.126 39.7498 58.126C39.5773 58.126 39.4065 58.092 39.2471 58.026C39.0878 57.9599 38.9431 57.8631 38.8212 57.7411L29.6337 48.5536C29.3874 48.3073 29.249 47.9733 29.249 47.625C29.249 47.2767 29.3874 46.9427 29.6337 46.6964C29.8799 46.4501 30.214 46.3118 30.5623 46.3118C30.9105 46.3118 31.2446 46.4501 31.4908 46.6964L39.7498 54.957L59.8212 34.8839C60.0674 34.6376 60.4015 34.4993 60.7498 34.4993C61.098 34.4993 61.4321 34.6376 61.6783 34.8839C61.9246 35.1302 62.063 35.4642 62.063 35.8125C62.063 36.1608 61.9246 36.4948 61.6783 36.7411Z"
                    fill="white" />
            </svg>
            <p class="mt-4 text-xl font-medium text-center text-black">Berhasil Menambahkan Data Penjualan Baru</p>
        </div>
        @if (session('transaction_details'))
        @foreach (session('transaction_details') as $detail)
        <div class="mt-4 bg-white border x-auto rounded-xl">
            <div class="flex items-center gap-4 p-6">
                <img width="96" class="rounded-lg" src="{{ asset('storage/'. $detail->goods->image) }}"
                    alt="{{ $detail->goods->name }}">
                <div class="flex items-start justify-between w-full">
                    <div class="grid gap-2">
                        <p class="mt-2 text-xs text-gray-500">Barang & Merek</p>
                        <h3 class="block mt-1 text-lg leading-tight text-black">{{ $detail->goods->name }} -
                            {{ $detail->goods->color }}</h3>
                        <p class="text-sm text-gray-500 truncate max-w-28">{{ $detail->goods->merk->name }}</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-between px-6 py-4 text-sm border-t bg-gray-50">
                <div class="flex gap-12">
                    <div>
                        <span class="block font-bold text-gray-700">Berat & Kadar</span>
                        <span>{{ $detail->goods->size }}gr</span>
                        <span
                            class="px-2 py-1 ml-2 text-xs text-gray-800 bg-gray-200 rounded">{{ $detail->goods->rate }}%</span>
                    </div>
                    <div>
                        <span class="block font-bold text-gray-700">Harga</span>
                        <span
                            class="text-base font-medium">{{ 'Rp.' . number_format($detail->harga_jual, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @endif
        <div class="flex items-center justify-center gap-4 mb-4">
            <button type="button" @click="open = false"
                class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg text-[#D0D5DD] border-[#D0D5DD] border bg-white">
                Tutup
                <i class="ph ph-x ml-1.5"></i>
            </button>
        </div>
    </div>
</div>
