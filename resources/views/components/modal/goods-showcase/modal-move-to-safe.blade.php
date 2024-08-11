<div id="hs-move-to-safe-modal-{{ $goodShowcase->id }}"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="hs-move-to-safe-modal-{{ $goodShowcase->id }}-label">
    <div
        class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 sm:max-w-md sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div
            class="flex flex-col w-full bg-white border-[0.5px] shadow-sm pointer-events-auto rounded-xl border-[#D9D9D9] p-6 gap-4">
            <div class="flex items-center pb-4 border-b">
                <h3 id="hs-move-to-safe-modal-{{ $goodShowcase->id }}-label"
                    class="text-xl font-semibold text-[#344054]">
                    Pindahkan ke Brankas?
                </h3>
            </div>
            <div class="">
                <p class="mb-4 text-sm text-black">Apakah anda ingin memindahkan barang berikut untuk dipindahkan ke
                    brankas</p>
                <div class="flex items-center justify-stretch p-3 border border-[#D9D9D9] rounded-xl">
                    <div class="flex items-center gap-3 justify-start text-[#232323]">
                        <img src="{{ asset('storage/' . $goodShowcase->image) }}"
                            class="object-cover rounded-lg size-14" alt="{{ $goodShowcase->name }}">
                        <div class="flex flex-col items-start gap-1 mr-4">
                            <span class="text-sm truncate max-w-28">{{ $goodShowcase->tray->showcase->name}} - {{ $goodShowcase->goodsType->name }} ({{ $goodShowcase->rate }}%) </span>
                            <span class="text-sm truncate max-w-28">Merek {{ $goodShowcase->merk->name }} </span>
                            <span class="text-xs">{{ $goodShowcase->size }}gr </span>
                        </div>
                    </div>
                    <div class="flex flex-col items-start gap-1">
                        <span class="text-sm font-semibold">{{ $goodShowcase->tray->showcase->name}} Baki {{ $goodShowcase->tray->code}}</span>
                        <span class="text-xs text-[#9A9A9A] font-inter">
                            {{ \Carbon\Carbon::parse($goodShowcase->date_entry)->translatedFormat('d F Y') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-center gap-4">
                <form method="POST" action="{{ route('goods.moveToSafe', ['id' => $goodShowcase->id]) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                    class="flex items-center px-4 py-3 gap-1.5 text-sm font-medium rounded-lg bg-[#6634BB] text-[#F8F8F8]">
                    <span>Ya <i class="ph ph-check"></i></span>
                    </button>
                </form>
                <button type="button"
                    class="flex items-center px-4 py-3 gap-1.5 text-sm font-medium rounded-lg bg-white text-[#606060] border border-[#D0D5DD]"
                    data-hs-overlay="#hs-move-to-safe-modal-{{ $goodShowcase->id }}">
                    <span>Tutup</span> <i class="ph ph-x"></i>
                </button>
            </div>
        </div>
    </div>
</div>
