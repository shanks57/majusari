<div 
    x-data="{ 
        form: {
            id: '{{ $type->id }}',
            jenisBarang: '{{ $type->name }}',
            tambahanBiaya: '{{ $type->additional_cost }}',
            status: {{ $type->status == 1 ? 'true' : 'false' }} // Konversi angka ke boolean
        }
    }" 
    class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" 
    role="dialog" tabindex="-1" aria-labelledby="edit-modal-label" id="hs-edit-modal-{{ $type->id }}">
    
    <div class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-2xl md:w-full">
        <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
            <div class="flex items-center justify-between px-4">
                <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                    Update Jenis Barang
                </h3>
                <button type="button" class="text-red-500" aria-label="Close" data-hs-overlay="#hs-edit-modal-{{ $type->id }}">
                    <i class="text-2xl ph ph-x-circle"></i>
                </button>
            </div>
            <div class="border-b border-[#D0D5DD]"></div>
            <form :action="`{{ route('master.types.update', $type->id) }}`" method="post">
                @csrf
                @method('PUT') <!-- Specify the PUT method for updates -->
                <div class="p-4 overflow-y-auto">
                    <div class="w-full mb-4">
                        <label for="jenisBarang" class="block text-sm text-[#344054]">Jenis Barang</label>
                        <input type="text" id="jenisBarang" name="jenisBarang" x-model="form.jenisBarang"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                            placeholder="Masukkan Jenis Barang" required>
                        
                        <!-- Error message for jenisBarang -->
                        @error('jenisBarang')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full mb-4">
                        <label for="tambahanBiaya" class="block text-sm text-[#344054] leading-5">Tambahan Biaya</label>
                        <input type="number" id="tambahanBiaya" name="tambahanBiaya" x-model="form.tambahanBiaya"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base leading-6 text-[#667085]"
                            placeholder="Masukkan Tambahan Biaya" required min="0">
                        
                        <!-- Error message for tambahanBiaya -->
                        @error('tambahanBiaya')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between w-full mb-4">
                        <label for="status" class="block text-sm text-[#344054] leading-5">Status</label>
                        <div class="flex items-center mb-4">
                            <label for="status-toggle-{{ $type->id }}"
                                class="text-sm text-gray-500 me-3 dark:text-neutral-400">Tidak Aktif</label>
                            <input type="checkbox" id="status-toggle-{{ $type->id }}" name="status"
                                x-model="form.status"
                                :value="form.status ? 1 : 0"
                                class="relative w-[3.25rem] h-7 p-px bg-gray-100 border-transparent text-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-blue-600 disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:text-blue-600 checked:border-blue-600 focus:checked:border-blue-600 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-600
                                before:inline-block before:size-6 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:before:bg-neutral-400 dark:checked:before:bg-blue-200">
                            <label for="status-toggle-{{ $type->id }}"
                                class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Aktif</label>
                            
                            <!-- Error message for status -->
                            @error('status')
                            <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end px-4 gap-x-2">
                    <button type="submit"
                        :disabled="!form.jenisBarang || form.tambahanBiaya === '' || form.tambahanBiaya < 0"
                        class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white"
                        :class="{ 'opacity-50 cursor-not-allowed': !form.jenisBarang || form.tambahanBiaya === '' || form.tambahanBiaya < 0 }">
                        <span>Simpan</span>
                        <i class="ph ph-floppy-disk ml-1.5"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
