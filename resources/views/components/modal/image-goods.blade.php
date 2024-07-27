<div id="hs-image-goods-modal-{{ $goodShowcase->id }}"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="hs-image-goods-modal-{{ $goodShowcase->id }}-label">
    <div
        class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 sm:max-w-md sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div
            class="flex flex-col w-full bg-white border-[0.5px] shadow-sm pointer-events-auto rounded-xl border-[#D9D9D9] p-6 gap-4">
            <div class="flex items-center justify-center pb-4 border-b">
                <h3 id="hs-image-goods-modal-{{ $goodShowcase->id }}-label" class="text-xl font-semibold text-[#344054]">
                    Tampilan Penuh Etalase
                </h3>
            </div>
            <div class="flex justify-center">
                <img src="{{ asset('storage/' . $goodShowcase->image) }}" class="rounded size-80"
                    alt="{{ $goodShowcase->name }}">
            </div>
            <div class="flex justify-center text-sm text-[#151617] gap-3">
                <p class="">{{ $goodShowcase->name }} - {{$goodShowcase->color}}</p>
                <p>{{ $goodShowcase->merk->company }}</p>
            </div>
            <div class="flex items-center justify-center ">
                <button type="button"
                    class="flex items-center px-4 py-3 gap-1.5 text-sm font-medium rounded-lg bg-[#6634BB] text-[#F8F8F8]"
                    data-hs-overlay="#hs-image-goods-modal-{{ $goodShowcase->id }}">
                    <p>Tutup</p> <i class="ph ph-x"></i>
                </button>
            </div>
        </div>
    </div>
</div>
