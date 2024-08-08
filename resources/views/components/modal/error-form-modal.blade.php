@if ($errors->any())
<div x-data="{ open: true }" x-show="open" @click.away="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50" style="display: none;">
    <div class="w-full max-w-md p-6 mx-4 bg-white border border-[#D9D9D9] shadow-lg rounded-xl">
        <div class="flex flex-col items-center justify-between">
            <svg width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="90" height="90" rx="45" fill="#FF3B30" />
                <rect x="4" y="4" width="82" height="82" rx="41" stroke="#E7E0EC" stroke-opacity="0.16"
                    stroke-width="8" />
                <path
                    d="M57.7408 55.8839C57.8628 56.0058 57.9595 56.1506 58.0255 56.3099C58.0915 56.4693 58.1255 56.64 58.1255 56.8125C58.1255 56.985 58.0915 57.1557 58.0255 57.3151C57.9595 57.4744 57.8628 57.6191 57.7408 57.7411C57.6189 57.863 57.4741 57.9598 57.3148 58.0258C57.1555 58.0918 56.9847 58.1257 56.8123 58.1257C56.6398 58.1257 56.469 58.0918 56.3097 58.0258C56.1504 57.9598 56.0056 57.863 55.8837 57.7411L44.9998 46.8555L34.1158 57.7411C33.8696 57.9874 33.5355 58.1257 33.1873 58.1257C32.839 58.1257 32.5049 57.9874 32.2587 57.7411C32.0124 57.4948 31.874 57.1608 31.874 56.8125C31.874 56.4642 32.0124 56.1302 32.2587 55.8839L43.1442 45L32.2587 34.1161C32.0124 33.8698 31.874 33.5358 31.874 33.1875C31.874 32.8392 32.0124 32.5052 32.2587 32.2589C32.5049 32.0126 32.839 31.8743 33.1873 31.8743C33.5355 31.8743 33.8696 32.0126 34.1158 32.2589L44.9998 43.1445L55.8837 32.2589C56.1299 32.0126 56.464 31.8743 56.8123 31.8743C57.1605 31.8743 57.4946 32.0126 57.7408 32.2589C57.9871 32.5052 58.1255 32.8392 58.1255 33.1875C58.1255 33.5358 57.9871 33.8698 57.7408 34.1161L46.8553 45L57.7408 55.8839Z"
                    fill="white" />
            </svg>

            <div class="my-4">
                <p class="mb-2 font-medium text-center text-gray-700">Terjadi Kesalahan</p>
                <ul class="px-4 text-sm list-disc">
                    @foreach ($errors->all() as $error)
                    <li class="text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
            <button @click="open = false" type="button"
                class="flex items-center px-4 py-3 gap-1.5 text-sm font-medium rounded-lg bg-white text-[#606060] border border-[#D0D5DD]">
                <span>Tutup</span> <i class="ph-bold ph-x"></i>
            </button>
        </div>
    </div>
</div>
@endif
