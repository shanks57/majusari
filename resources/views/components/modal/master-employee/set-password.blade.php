    <div x-data="{ 
            form: {
                id: '{{ $employee->id }}',
                password: '',
                password_confirmation: '',
                status: {{ $employee->status == 1 ? 'true' : 'false' }}
            }
        }" 
        class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
        role="dialog" tabindex="-1" aria-labelledby="set-password-modal-label"
        id="hs-set-password-modal-{{ $employee->id }}">

        <div
            class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-xl md:w-full">
            <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
                <div class="flex items-center justify-between px-4">
                    <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                        Set Password Pegawai
                    </h3>
                    <button type="button" class="text-red-500" aria-label="Close"
                        data-hs-overlay="#hs-set-password-modal-{{ $employee->id }}">
                        <i class="text-2xl ph ph-x-circle"></i>
                    </button>
                </div>
                <div class="border-b border-[#D0D5DD]"></div>
                <form :action="`{{ route('master.employees.set-password', $employee->id) }}`" method="post">
                    @csrf
                    @method('PUT')
                    <div class="px-4 overflow-y-auto">
                        <!-- Toggle Password Input -->
                        <div class="relative w-full mb-4">
                            <label for="password" class="block mb-2 text-sm text-[#344054]">Set Password</label>
                            <input id="hs-toggle-password" type="password" name="password" x-model="form.password"
                                class="block w-full py-3 text-base border-gray-200 rounded-lg ps-4 pe-10 "
                                placeholder="Masukkan Password Baru" required>
                            <button type="button" data-hs-toggle-password='{"target": "#hs-toggle-password"}'
                                class="absolute inset-y-0 z-20 flex items-center px-3 pt-4 mt-4 text-gray-400 cursor-pointer end-0 rounded-e-md focus:outline-none focus:text-blue-600 dark:text-neutral-600 dark:focus:text-blue-500">
                                <svg class="shrink-0 size-4" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                    <path class="hs-password-active:hidden"
                                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68">
                                    </path>
                                    <path class="hs-password-active:hidden"
                                        d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61">
                                    </path>
                                    <line class="hs-password-active:hidden" x1="2" x2="22" y1="2" y2="22"></line>
                                    <path class="hidden hs-password-active:block"
                                        d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                            @error('password')
                            <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Toggle Password Confirmation Input -->
                        <div class="relative w-full mb-4">
                            <label for="password_confirmation" class="block text-sm text-[#344054]">Konfirmasi
                                Password</label>
                            <input id="hs-toggle-password-confirmation" type="password" name="password_confirmation"
                                x-model="form.password_confirmation"
                                class="block w-full py-3 text-base border-gray-200 rounded-lg ps-4 pe-10"
                                placeholder="Konfirmasi Password Baru" required>
                            <button type="button"
                                data-hs-toggle-password='{"target": "#hs-toggle-password-confirmation"}'
                                class="absolute inset-y-0 z-20 flex items-center px-3 pt-3 mt-3 text-gray-400 cursor-pointer end-0 rounded-e-md focus:outline-none focus:text-blue-600 dark:text-neutral-600 dark:focus:text-blue-500">
                                <svg class="shrink-0 size-4" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path class="hs-password-active:hidden" d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                    <path class="hs-password-active:hidden"
                                        d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68">
                                    </path>
                                    <path class="hs-password-active:hidden"
                                        d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61">
                                    </path>
                                    <line class="hs-password-active:hidden" x1="2" x2="22" y1="2" y2="22"></line>
                                    <path class="hidden hs-password-active:block"
                                        d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                    <circle class="hidden hs-password-active:block" cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                            @error('password_confirmation')
                            <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex items-center justify-between w-full mb-4">
                            <label for="status" class="block text-sm text-[#344054] leading-5">Status Pegawai</label>
                            <div class="flex items-center mb-4">
                                <label for="status" class="text-sm text-gray-500 me-3">Tidak Aktif</label>
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" id="status" name="status" x-model="form.status"
                                    :value="form.status ? 1 : 0"
                                    class="relative w-[3.25rem] h-7 p-px bg-gray-100 border-transparent text-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-[#7F56D9] disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:text-[#7F56D9] checked:border-[#7F56D9] focus:checked:border-[#7F56D9] 
                            before:inline-block before:size-6 before:bg-white checked:before:bg-blue-200 before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200">
                                <label for="status" class="text-sm text-gray-500 ms-3">Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end px-4 gap-x-2">
                        <button type="submit"
                            :disabled="!form.password || !form.password_confirmation || form.password !== form.password_confirmation"
                            class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white"
                            :class="{ 'opacity-50 cursor-not-allowed': !form.password || !form.password_confirmation || form.password !== form.password_confirmation }">
                            <span>Simpan</span>
                            <i class="ph ph-floppy-disk ml-1.5"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
