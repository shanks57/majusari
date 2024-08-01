<div x-data="{ open: @js(session('success-store') ? true : false) }" x-show="open" x-on:click.away="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50" style="display: none;">
    <div class="w-full max-w-md p-6 mx-4 bg-white border border-[#D9D9D9] shadow-lg rounded-xl">
        <div class="flex flex-col items-center justify-between">
            <svg width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="4" y="4" width="82" height="82" rx="41" fill="#75FFC5" />
                <rect x="4" y="4" width="82" height="82" rx="41" stroke="#C9FFE8" stroke-width="8" />
                <path
                    d="M61.6783 36.7411L40.6783 57.7411C40.5565 57.8631 40.4117 57.9599 40.2524 58.026C40.093 58.092 39.9222 58.126 39.7498 58.126C39.5773 58.126 39.4065 58.092 39.2471 58.026C39.0878 57.9599 38.9431 57.8631 38.8212 57.7411L29.6337 48.5536C29.3874 48.3073 29.249 47.9733 29.249 47.625C29.249 47.2767 29.3874 46.9427 29.6337 46.6964C29.8799 46.4501 30.214 46.3118 30.5623 46.3118C30.9105 46.3118 31.2446 46.4501 31.4908 46.6964L39.7498 54.957L59.8212 34.8839C60.0674 34.6376 60.4015 34.4993 60.7498 34.4993C61.098 34.4993 61.4321 34.6376 61.6783 34.8839C61.9246 35.1302 62.063 35.4642 62.063 35.8125C62.063 36.1608 61.9246 36.4948 61.6783 36.7411Z"
                    fill="white" />
            </svg>
            <div class="my-4">
                <p class="text-gray-700">{{ session('success-store') }}</p>
            </div>
            <div class="flex items-center justify-stretch p-3 border border-[#D9D9D9] rounded-xl mb-4">
                    <div class="flex items-center gap-3 justify-start text-[#232323]">
                        <img src="{{ asset('storage/' . session('imageShowcase')) }}"
                            class="object-cover rounded-lg size-14" alt="{{ session('nameShowcase') }}">
                        <div class="flex flex-col items-start gap-1 mr-4">
                            <span class="text-sm">{{ session('showcase')}} - {{ session('goodType') }} ({{ session('rateShowcase') }}%) </span>
                            <span class="text-sm">Merek {{ session('merk') }} </span>
                            <span class="text-xs">{{ session('sizeShowcase') }}gr </span>
                        </div>
                    </div>
                    <div class="flex flex-col items-start gap-1">
                        <span class="text-sm font-semibold">{{ session('showcase')}} Baki {{ session('trayCode')}}</span>
                        <span class="text-xs text-[#9A9A9A] font-inter">{{ session('dateShowcase') }}</span>
                    </div>
                </div>
            <button @click="open = false" type="button"
                    class="flex items-center px-4 py-3 gap-1.5 text-sm font-medium rounded-lg bg-[#6634BB] text-[#F8F8F8]">
                    <span>Tutup</span> <i class="ph-bold ph-x"></i>
                </button>
        </div>
    </div>
</div>
