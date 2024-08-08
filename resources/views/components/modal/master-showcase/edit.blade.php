<div 
    x-data="{ 
        form: {
            showcase_id: '{{ $etalase->id }}',
            code: '{{ $etalase->name }}-',
            capacity: '',
        }
    }" 
    class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" 
    role="dialog" tabindex="-1" aria-labelledby="edit-modal-label" id="hs-edit-modal-{{ $etalase->id }}">
    
    <div class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-2xl md:w-full">
        <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl border border-[#E0E0E0]">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-medium text-[#344054]">
                    Tambah Baki
                </h3>
                <button type="button" class="text-red-500" aria-label="Close" data-hs-overlay="#hs-edit-modal-{{ $etalase->id }}">
                    <i class="text-2xl ph ph-x-circle"></i>
                </button>
            </div>
            <div class="border-b border-[#D0D5DD]"></div>
            <form action="{{ route('master.showcase.add-trays') }}" method="post">
                @csrf
                <div class="space-y-4">
                    <div class="w-full p-3 rounded bg-[#F9FAFB] text-[#344054]">
                        <label class="block mb-1 text-sm">Etalase</label>
                        <span class="text-lg font-medium">{{ $etalase->name }}</span>
                    </div>
                    <div class="w-full">
                        <label for="code" class="block text-sm text-[#344054]">Kode Baki</label>
                        <input type="text" id="code" name="code" x-model="form.code"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                            placeholder="Masukkan kode baki" required>
                    </div>
                    <div class="w-full">
                        <label for="capacity" class="block text-sm text-[#344054] leading-5">Jumlah Item</label>
                        <input type="number" id="capacity" name="capacity" x-model="form.capacity"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base leading-6 text-[#667085]"
                            placeholder="Masukkan jumlah item" required min="1" max="100">
                    </div>
                </div>
                <input type="hidden" name="showcase_id" value="{{ $etalase->id }}">
                <div class="flex items-center justify-end mt-4 gap-x-2">
                    <button type="submit"
                        :disabled="!form.code || form.capacity === ''"
                        class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white"
                        :class="{ 'opacity-50 cursor-not-allowed': !form.code || form.capacity === '' }">
                        <span>Simpan</span>
                        <i class="ph ph-floppy-disk ml-1.5"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
