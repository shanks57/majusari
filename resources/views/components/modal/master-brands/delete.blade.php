<div id="hs-delete-modal-{{ $brand->id }}"
    class="hs-overlay hidden fixed top-0 end-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none mr-8"
    role="dialog" tabindex="-1" aria-labelledby="hs-delete-modal-{{ $brand->id }}-label">
    <div
        class="m-3 mt-0 transition-all ease-out opacity-0 hs-overlay-animation-target hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 sm:mx-auto">
        <div class="flex flex-col gap-4 p-4 bg-[#FCFCFD] border shadow-sm pointer-events-auto rounded-xl">
            <div class="flex items-center justify-center border-[#EAECF0]">
                <h3 id="hs-delete-modal-{{ $brand->id }}-label" class="text-lg text-[#344054] leading-7">
                    Yakin mau hapus data?
                </h3>
            </div>
            <div class="flex items-center justify-center gap-4">
                <form action="{{ route('master.brands.destroy', ['id' => $brand->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex items-center gap-1.5 text-sm px-4 py-3 font-medium rounded-lg bg-[#F04438] text-[#F8F8F8]">
                        <span>Hapus <i class="ph ph-check"></i></span>
                    </button>
                </form>
                <button type="button"
                    class="flex items-center px-4 py-3 gap-1.5 text-sm font-medium rounded-lg bg-white text-[#606060] border border-[#D0D5DD]"
                    data-hs-overlay="#hs-delete-modal-{{ $brand->id }}">
                    <span>Batal</span> <i class="ph ph-x"></i>
                </button>
            </div>
        </div>
    </div>
</div>
