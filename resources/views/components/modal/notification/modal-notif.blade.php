<div class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="add-confirmation-complaint-modal-label"
    id="hs-confirmation-complaint-modal-{{ $notif->id }}">

    <div
        class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-md md:w-full">
        <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
            @if ($notif->status_price == 0)
            <div class="flex flex-col items-center justify-center">
                <svg width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="4" width="82" height="82" rx="41" fill="#FF3B30" />
                    <rect x="4" y="4" width="82" height="82" rx="41" stroke="#FFD1C9" stroke-width="8" />
                    <path
                        d="M57.7418 55.8839C57.8638 56.0058 57.9605 56.1506 58.0265 56.3099C58.0925 56.4693 58.1265 56.64 58.1265 56.8125C58.1265 56.985 58.0925 57.1557 58.0265 57.3151C57.9605 57.4744 57.8638 57.6191 57.7418 57.7411C57.6199 57.863 57.4751 57.9598 57.3158 58.0258C57.1565 58.0918 56.9857 58.1257 56.8132 58.1257C56.6408 58.1257 56.47 58.0918 56.3107 58.0258C56.1514 57.9598 56.0066 57.863 55.8846 57.7411L45.0007 46.8555L34.1168 57.7411C33.8705 57.9874 33.5365 58.1257 33.1882 58.1257C32.8399 58.1257 32.5059 57.9874 32.2596 57.7411C32.0134 57.4948 31.875 57.1608 31.875 56.8125C31.875 56.4642 32.0134 56.1302 32.2596 55.8839L43.1452 45L32.2596 34.1161C32.0134 33.8698 31.875 33.5358 31.875 33.1875C31.875 32.8392 32.0134 32.5052 32.2596 32.2589C32.5059 32.0126 32.8399 31.8743 33.1882 31.8743C33.5365 31.8743 33.8705 32.0126 34.1168 32.2589L45.0007 43.1445L55.8846 32.2589C56.1309 32.0126 56.4649 31.8743 56.8132 31.8743C57.1615 31.8743 57.4955 32.0126 57.7418 32.2589C57.9881 32.5052 58.1265 32.8392 58.1265 33.1875C58.1265 33.5358 57.9881 33.8698 57.7418 34.1161L46.8563 45L57.7418 55.8839Z"
                        fill="white" />
                </svg>

            </div>
            <p class="text-xl font-medium text-center text-black">Harga jual yang diajukan ditolak</p>
            @elseif ($notif->status_price == 1)
            <div class="flex flex-col items-center justify-center">
                <svg width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="4" width="82" height="82" rx="41" fill="#75FFC5" />
                    <rect x="4" y="4" width="82" height="82" rx="41" stroke="#C9FFE8" stroke-width="8" />
                    <path
                        d="M61.6793 36.7411L40.6793 57.7411C40.5574 57.8631 40.4127 57.9599 40.2533 58.026C40.094 58.092 39.9232 58.126 39.7507 58.126C39.5782 58.126 39.4075 58.092 39.2481 58.026C39.0888 57.9599 38.944 57.8631 38.8221 57.7411L29.6346 48.5536C29.3884 48.3073 29.25 47.9733 29.25 47.625C29.25 47.2767 29.3884 46.9427 29.6346 46.6964C29.8809 46.4501 30.2149 46.3118 30.5632 46.3118C30.9115 46.3118 31.2455 46.4501 31.4918 46.6964L39.7507 54.957L59.8221 34.8839C60.0684 34.6376 60.4024 34.4993 60.7507 34.4993C61.099 34.4993 61.433 34.6376 61.6793 34.8839C61.9256 35.1302 62.064 35.4642 62.064 35.8125C62.064 36.1608 61.9256 36.4948 61.6793 36.7411Z"
                        fill="white" />
                </svg>
            </div>
            <p class="text-xl font-medium text-center text-black">Berhasil menyetujui harga jual</p>
            @elseif ($notif->status_price == 2)
            <div class="flex flex-col items-center justify-center">
                <svg width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="4" width="82" height="82" rx="41" fill="#FFCC00" />
                    <rect x="4" y="4" width="82" height="82" rx="41" stroke="#F9FFC9" stroke-width="8" />
                    <path
                        d="M60.7507 31.875V58.125C60.7507 58.8212 60.4742 59.4889 59.9819 59.9812C59.4896 60.4734 58.8219 60.75 58.1257 60.75H46.3132C45.9651 60.75 45.6313 60.6117 45.3851 60.3656C45.139 60.1194 45.0007 59.7856 45.0007 59.4375C45.0007 59.0894 45.139 58.7556 45.3851 58.5094C45.6313 58.2633 45.9651 58.125 46.3132 58.125H58.1257V31.875H31.8757V47.625C31.8757 47.9731 31.7374 48.3069 31.4913 48.5531C31.2452 48.7992 30.9113 48.9375 30.5632 48.9375C30.2151 48.9375 29.8813 48.7992 29.6352 48.5531C29.389 48.3069 29.2507 47.9731 29.2507 47.625V31.875C29.2507 31.1788 29.5273 30.5111 30.0196 30.0188C30.5119 29.5266 31.1795 29.25 31.8757 29.25H58.1257C58.8219 29.25 59.4896 29.5266 59.9819 30.0188C60.4742 30.5111 60.7507 31.1788 60.7507 31.875ZM44.6168 49.3214C44.4949 49.1994 44.3502 49.1026 44.1908 49.0365C44.0315 48.9705 43.8607 48.9365 43.6882 48.9365C43.5157 48.9365 43.345 48.9705 43.1856 49.0365C43.0263 49.1026 42.8815 49.1994 42.7596 49.3214L34.5007 57.582L31.4918 54.5714C31.3699 54.4495 31.2251 54.3527 31.0658 54.2867C30.9065 54.2207 30.7357 54.1868 30.5632 54.1868C30.3908 54.1868 30.22 54.2207 30.0607 54.2867C29.9013 54.3527 29.7566 54.4495 29.6346 54.5714C29.5127 54.6933 29.416 54.8381 29.35 54.9974C29.284 55.1568 29.25 55.3275 29.25 55.5C29.25 55.6725 29.284 55.8432 29.35 56.0026C29.416 56.1619 29.5127 56.3067 29.6346 56.4286L33.5721 60.3661C33.694 60.4881 33.8388 60.5849 33.9981 60.651C34.1575 60.717 34.3282 60.751 34.5007 60.751C34.6732 60.751 34.844 60.717 35.0033 60.651C35.1627 60.5849 35.3074 60.4881 35.4293 60.3661L44.6168 51.1786C44.7389 51.0567 44.8357 50.9119 44.9017 50.7526C44.9678 50.5933 45.0018 50.4225 45.0018 50.25C45.0018 50.0775 44.9678 49.9067 44.9017 49.7474C44.8357 49.5881 44.7389 49.4433 44.6168 49.3214Z"
                        fill="white" />
                </svg>

            </div>
            <p class="text-xl font-medium text-center text-black">Setujui harga jual yang telah diajukan</p>
            @endif

            <div class="mt-4 bg-white border x-auto rounded-xl">
                <div class="flex items-center gap-4 p-6">
                    <img width="96" class="rounded-lg" src="{{ asset('storage/'. $notif->goods->image) }}"
                        alt="{{ $notif->goods->name }}">
                    <div class="flex items-start justify-between w-full">
                        <div class="grid gap-2">
                            <p class="mt-2 text-xs text-gray-500">Barang & Merek</p>
                            <h3 class="block mt-1 text-lg leading-tight text-black">{{ $notif->goods->name }} -
                                {{ $notif->goods->color }}</h3>
                            <p class="max-w-xs text-sm text-gray-500 truncate">{{ $notif->goods->merk->name }}</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between px-6 py-4 text-sm border-t bg-gray-50">
                    <div class="flex gap-12">

                        <div>
                            <span class="block font-bold text-gray-700">Berat & Kadar</span>
                            <span>{{ $notif->goods->size }}gr</span>
                            <span
                                class="px-2 py-1 ml-2 text-xs text-gray-800 bg-gray-200 rounded">{{ $notif->goods->rate }}%</span>
                        </div>

                        <div>
                            <span class="block font-bold text-gray-700">Harga</span>
                            @if ($notif->status_price == 0 || $notif->status_price == 2)
                            <span
                                class="text-base font-medium text-red-500">{{ 'Rp.' . number_format($notif->new_selling_price, 0, ',', '.') }}</span>
                            @else
                            <span
                                class="text-base font-medium">{{ 'Rp.' . number_format($notif->new_selling_price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="p-4 text-sm bg-gray-100 border-t rounded">
                    <i class="text-gray-500 fas fa-info-circle"></i>
                    <span>Alasan :</span>
                    <span class="text-gray-700">{{ $notif->complaint }}</span>
                </div>
            </div>
            <div class="flex items-center justify-center px-4 gap-x-2">
                @if ($notif->status_price == 2)
                <form action="{{ route('cart.reject-price', $notif->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_price" value="0">
                    <button type="submit"
                        class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 text-white bg-red-500 rounded-lg">
                        <span>Tolak</span>
                        <i class="ph ph-x ml-1.5"></i>
                    </button>
                </form>
                <form action="{{ route('cart.agree-price', $notif->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status_price" value="1">
                    <button type="submit"
                        class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 text-white bg-green-500 rounded-lg">
                        <span>Setujui</span>
                        <i class="ph ph-check ml-1.5"></i>
                    </button>
                </form>
                @else
                <button type="button" aria-label="Close"
                    data-hs-overlay="#hs-confirmation-complaint-modal-{{ $notif->id }}"
                    class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 text-gray-500 border rounded-lg bg-gray-50">
                    <span>Tutup</span>
                    <i class="ph ph-x ml-1.5"></i>
                </button>
                @endif
            </div>

        </div>
    </div>
</div>
