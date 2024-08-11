<div id="hs-move-to-safe-modal-{{ $good->id }}"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="hs-move-to-safe-modal-{{ $good->id }}-label">
    <div
        class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 sm:max-w-md sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div
            class="flex flex-col w-full bg-white border-[0.5px] shadow-sm pointer-events-auto rounded-xl border-[#D9D9D9] p-6 gap-4">
            <div class="flex items-center justify-start p-3 border border-[#D9D9D9] rounded-xl">
                <div class="flex items-center gap-3 justify-between text-[#232323] w-full">
                    <img src="{{ asset('storage/' . $good->image) }}" class="object-cover rounded-lg size-32"
                        alt="{{ $good->name }}">
                    <div class="grid w-full grid-cols-1 mr-4">
                        <div class="flex items-center justify-between text-base font-semibold text-[#232323]">
                            <span class="text-sm font-normal">Nama Barang</span>
                            <span>{{ $good->name }}</span>
                        </div>
                        <div class="flex items-center justify-between text-base font-semibold text-[#232323]">
                            <span class="text-sm font-normal">Berat</span>
                            <span>{{ $good->size }} gr</span>
                        </div>
                        <div class="flex items-center justify-between text-base font-semibold text-[#232323]">
                            <span class="text-sm font-normal">Kadar</span>
                            <span>{{ $good->rate }} %</span>
                        </div>
                        <div class="flex items-center justify-between text-base font-semibold text-[#232323]">
                            <span class="text-sm font-normal">Kategori</span>
                            <span class="truncate max-w-28">{{ $good->category }}</span>
                        </div>
                        <div class="flex items-center justify-between text-base font-semibold text-[#232323]">
                            <span class="text-sm font-normal">Merek</span>
                            <span class="truncate max-w-28">{{ $good->merk->name }}</span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="flex items-center justify-end gap-4">
                <form method="POST" action="{{ route('goods-tray.moveToSafe', ['id' => $good->id]) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="flex items-center px-4 py-3 gap-1.5 text-sm font-medium rounded-lg bg-[#6634BB] text-[#F8F8F8]">
                        <span>Pindahkan ke Brankas <i class="ml-1 ph ph-vault"></i></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
