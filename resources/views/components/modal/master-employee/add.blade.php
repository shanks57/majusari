<div x-data="{ form: { name: '', email: '',username: '', phone: '', debt_receipt: '', wages: '', address: '', status: false } }"
    class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="form-modal-label" id="hs-add-modal">

    <div
        class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-2xl md:w-full">
        <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
            <div class="flex items-center justify-between px-4">
                <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                    Tambah Pegawai Baru
                </h3>
                <button type="button" class="text-red-500" aria-label="Close" data-hs-overlay="#hs-add-modal">
                    <i class="text-2xl ph ph-x-circle"></i>
                </button>
            </div>
            <div class="border-b border-[#D0D5DD]"></div>
            <form action="{{ route('master.employees.store') }}" method="post">
                @csrf
                <div class="p-4 overflow-y-auto">
                    <div class="w-full mb-4">
                        <label for="name" class="block text-sm text-[#344054]">Nama Pegawai</label>
                        <input type="text" id="name" name="name" x-model="form.name"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-black placeholder:text-[#667085]"
                            placeholder="Masukkan Nama Pegawai" required>
                        @error('name')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                     <div class="w-full mb-4">
                        <label for="email" class="block text-sm text-[#344054]">Nama Email</label>
                        <input type="email" id="email" name="email" x-model="form.email"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-black placeholder:text-[#667085]"
                            placeholder="Masukkan Email" required>
                        @error('email')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full mb-4">
                        <label for="username" class="block text-sm text-[#344054]">Nama Pengguna</label>
                        <input type="text" id="username" name="username" x-model="form.username"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-black placeholder:text-[#667085]"
                            placeholder="Masukkan Nama Pengguna" required>
                        @error('username')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full mb-4">
                        <label for="phone" class="block text-sm text-[#344054]">Nomor Handphone</label>
                        <input type="text" id="phone" name="phone" x-model="form.phone"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-black placeholder:text-[#667085]"
                            placeholder="Masukkan Nomor Handphone" required pattern="[0-9]{10,13}" title="Nomor HP harus terdiri dari 10 hingga 13 digit.">
                        @error('phone')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full mb-4">
                        <label for="wages" class="block text-sm text-[#344054]">Gaji Pegawai</label>
                        <input type="number" id="wages" name="wages" x-model="form.wages"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-black placeholder:text-[#667085]"
                            placeholder="Masukkan Gaji Pegawai"  min="0" required>
                        @error('wages')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full mb-4">
                        <label for="debt_receipt" class="block text-sm text-[#344054]">Bon Hutang</label>
                        <input type="number" id="debt_receipt" name="debt_receipt" x-model="form.debt_receipt"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-black placeholder:text-[#667085]"
                            placeholder="Masukkan Bon Hutang" min="0" required>
                        @error('debt_receipt')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full mb-4">
                        <label for="address" class="block text-sm text-[#344054]">Alamat</label>
                        <textarea id="address" name="address" x-model="form.address" class="block w-full px-4 py-3 text-base mt-1.5 border-gray-200 rounded-lg focus:border-[#79799B] disabled:opacity-50 text-black placeholder:text-[#667085] disabled:pointer-events-none" rows="3" placeholder="Masukkan Alamat" required></textarea>
                        @error('address')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between w-full mb-4">
                        <label for="status" class="block text-sm text-[#344054] leading-5">Status Pegawai</label>
                        <div class="flex items-center mb-4">
                            <label for="status" class="text-sm text-gray-500 me-3 ">Tidak
                                Aktif</label>
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" id="status" name="status" x-model="form.status" :value="form.status ? 1 : 0" class="relative w-[3.25rem] h-7 p-px bg-gray-100 border-transparent text-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-blue-600 disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:text-blue-600 checked:border-blue-600 focus:checked:border-blue-600 
                          before:inline-block before:size-6 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200">
                            <label for="status" class="text-sm text-gray-500 ms-3">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end px-4 gap-x-2">
                    <button type="submit"
                        :disabled="!form.name || !form.email || !form.username || !form.phone || !form.debt_receipt || !form.wages || !form.address"
                        class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white"
                        :class="{ 'opacity-50 cursor-not-allowed': !form.name || !form.email || !form.username || !form.phone || !form.debt_receipt || !form.wages || !form.address }">
                        <span>Simpan</span>
                        <i class="ph ph-floppy-disk ml-1.5"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
