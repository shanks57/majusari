<div x-data="{ form: { code: ''} }"
    class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="form-modal-label" id="hs-add-modal">
    <div
        class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-2xl md:w-full">
        <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
            <div class="flex items-center justify-between px-4">
                <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                    Penjualan Baru
                </h3>
                <button type="button" class="text-red-500" aria-label="Close" data-hs-overlay="#hs-add-modal">
                    <i class="text-2xl ph ph-x-circle"></i>
                </button>
            </div>
            <div class="border-b border-[#D0D5DD]"></div>
            <form action="{{ route('sale.search-code') }}" method="post">
                @csrf
                <div class="p-4 overflow-y-auto">
                    <div class="w-full mb-4">
                        <label for="code" class="block text-sm text-[#344054]">Kode Barcode</label>
                        <div
                            class="flex items-center rounded-lg border border-[##667085]  w-full mt-1.5 overflow-hidden focus:outline-none">
                            <input type="text" id="code" name="code" x-model="form.code"
                                class="w-full px-3.5 py-2.5 text-base text-[#667085] focus:outline-none border-none focus:border-none focus:ring-0"
                                placeholder="Masukkan kode barcode" required>
                            <button type="submit" :disabled="!form.code"
                                class="flex items-center justify-center px-4 py-3 text-base font-medium leading-5 text-[#667085]"
                                :class="{ 'opacity-50 cursor-not-allowed': !form.code }">
                                <i class="ph ph-magnifying-glass"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
