<div 
    x-data="{ 
        form: {
            name: '{{ Auth::user()->name }}',
            phone: '{{ Auth::user()->phone }}',
            email: '{{ Auth::user()->email }}',
        }
    }" 
    class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" 
    role="dialog" tabindex="-1" aria-labelledby="update-profile-modal" id="hs-update-profile-modal">
    
    <div class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-2xl md:w-full">
        <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
            <div class="flex items-center justify-between px-4">
                <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                    Update Profile
                </h3>
                <button type="button" class="text-red-500" aria-label="Close" data-hs-overlay="#hs-update-profile-modal">
                    <i class="text-2xl ph ph-x-circle"></i>
                </button>
            </div>
            <div class="border-b border-[#D0D5DD]"></div>
            <form :action="`{{ route('profile.update', Auth::user()->id) }}`" method="post">
                @csrf
                @method('PUT')
                <div class="px-4 overflow-y-auto">
                    <div class="w-full mb-4">
                        <label for="name" class="block text-sm text-[#344054]">Nama Pegawai</label>
                        <input type="text" id="name" name="name" x-model="form.name"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                            placeholder="Masukkan Nama Pegawai" required>
                        @error('name')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full mb-4">
                        <label for="email" class="block text-sm text-[#344054]">Email Pegawai</label>
                        <input type="email" id="email" name="email" x-model="form.email"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                            placeholder="Masukkan Nama Pegawai" required>
                        @error('email')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full mb-4">
                        <label for="phone" class="block text-sm text-[#344054]">Nomor Handphone</label>
                        <input type="number" id="phone" name="phone" x-model="form.phone"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                            placeholder="Masukkan Nomor Handphone" required>
                        @error('phone')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex items-center justify-end px-4 gap-x-2">
                    <button type="submit"
                        :disabled="!form.name || !form.email || !form.phone"
                        class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white"
                        :class="{ 'opacity-50 cursor-not-allowed': !form.name || !form.email || !form.phone">
                        <span>Simpan</span>
                        <i class="ph ph-floppy-disk ml-1.5"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
