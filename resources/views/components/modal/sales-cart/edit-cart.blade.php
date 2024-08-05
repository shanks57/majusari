<div 
    x-data="{ 
        form: {
            id: '{{ $cart->id }}',
            new_selling_price: '{{ $cart->new_selling_price }}',
        }
    }" 
    class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" 
    role="dialog" tabindex="-1" aria-labelledby="edit-modal-label" id="hs-edit-modal-{{ $cart->id }}">
    
    <div class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-2xl md:w-full">
        <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
            <div class="flex items-center justify-between px-4">
                <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                    Update Harga Jual Barang
                </h3>
                <button type="button" class="text-red-500" aria-label="Close" data-hs-overlay="#hs-edit-modal-{{ $cart->id }}">
                    <i class="text-2xl ph ph-x-circle"></i>
                </button>
            </div>
            <div class="border-b border-[#D0D5DD]"></div>
            <form :action="`{{ route('cart.update', $cart->id) }}`" method="post">
                @csrf
                @method('PATCH')
                <div class="p-4 overflow-y-auto">
                    <div class="w-full mb-4">
                        <label for="new_selling_price" class="block text-sm text-[#344054]">Harga Jual</label>
                        <input type="text" id="new_selling_price" name="new_selling_price" x-model="form.new_selling_price"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                            placeholder="Masukkan Nama Perusahaan" required>
                        
                        @error('new_selling_price')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex items-center justify-end px-4 gap-x-2">
                    <button type="submit"
                        :disabled="!form.new_selling_price"
                        class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white"
                        :class="{ 'opacity-50 cursor-not-allowed': !form.new_selling_price }">
                        <span>Simpan</span>
                        <i class="ph ph-floppy-disk ml-1.5"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
