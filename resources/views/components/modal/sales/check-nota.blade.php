<div x-data="{ form: { nota: ''} }"
    class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="nota-modal-label" id="hs-check-nota-modal">
    <div
        class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-xl md:w-full">
        <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
            <div class="flex items-center justify-between px-4">
                <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                    Pencarian Nota
                </h3>
                <button type="button" class="text-red-500" aria-label="Close" data-hs-overlay="#hs-check-nota-modal">
                    <i class="text-2xl ph ph-x-circle"></i>
                </button>
            </div>
            <div class="border-b border-[#D0D5DD]"></div>
            <form action="{{ route('sale.search-nota') }}" method="post">
                @csrf
                <div class="p-4 overflow-y-auto">
                    <div class="w-full mb-4">
                        <label for="nota" class="block text-sm text-[#344054]">Kode Penjualan</label>
                        <div
                            class="flex items-center rounded-lg border border-[##667085]  w-full mt-1.5 overflow-hidden focus:outline-none">
                            <input type="text" id="nota" name="nota" x-model="form.nota"
                                class="w-full px-3.5 py-2.5 text-base text-[#667085] focus:outline-none border-none focus:border-none focus:ring-0"
                                placeholder="Masukkan kode penjualan" required>
                            <button type="submit" :disabled="!form.nota"
                                class="flex items-center justify-center px-4 py-3 text-base font-medium leading-5 text-[#667085]"
                                :class="{ 'opacity-50 cursor-not-allowed': !form.nota }">
                                <i class="ph ph-magnifying-glass"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
