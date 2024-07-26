@section('title', 'Jenis Barang')
@include('components.datatables')

<x-layout x-data="{ modalOpen: false }">
    <x-header title="Jenis Barang">
        <x-button-add>
            Tambah Jenis Barang
        </x-button-add>
    </x-header>

    <div class="container py-4 mx-auto">
        <div class="relative w-full mx-auto mb-4">
            <input type="text" id="searchEtalase"
                class="w-full p-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:border-[#79799B]"
                placeholder="Cari di etalase">
            <i class="ph ph-magnifying-glass absolute left-3 top-3 text-[#2D2F30]"></i>
        </div>
        <div class="overflow-hidden border border-gray-200 rounded-t-lg shadow-lg">
            <table id="etalaseTable" class="min-w-full bg-white border border-gray-200 display">
                <thead>
                    <tr class="w-full bg-[#79799B] text-white  text-sm leading-normal">
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th class="py-3 px-6 text-left !font-normal">No</th>
                        <th class="py-3 px-6 text-left !font-normal">Jenis Barang</th>
                        <th class="py-3 px-6 text-left !font-normal">Tambahan Biaya</th>
                        <th class="py-3 px-6 text-left !font-normal">Status</th>
                        <th class="py-3 px-6 text-center !font-normal"></th>
                    </tr>
                </thead>
                <tbody class="text-sm font-light text-gray-600">
                    @foreach ($types as $type)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-6 py-3 text-left">
                            <input type="checkbox" class="select-row">
                        </td>
                        <td class="px-6 py-3 text-left">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3 text-left">{{ $type->name }}</td>
                        <td class="px-6 py-3 text-left">
                            {{ 'Rp.' . number_format($type->additional_cost, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            @if ($type->status == 1)
                            <span class="text-[#12B76A]">Aktif</span>
                            @else
                            <span class="text-[#F04438]">Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-center">
                            <button class="px-3 py-1 text-white bg-purple-500 rounded" aria-haspopup="dialog"
                                aria-expanded="false" aria-controls="edit-modal" data-hs-overlay="#edit-modal"
                                data-id="{{ $type->id }}" 
                                data-name="{{ $type->name }}"
                                data-additional-cost="{{ $type->additional_cost }}" data-status="{{ $type->status }}">
                                <i class="ph ph-pencil-line"></i> Edit
                            </button>

                            <button class="px-3 py-1 text-white bg-red-500 rounded">
                                <i class="ph ph-trash"></i> Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div
            class="flex items-center justify-between mb-16 text-sm leading-5 text-[#282833] bg-white rounded-b-lg border-b border-r border-l border-gray-200">
            <div id="dataTableInfo" class="px-4 py-3"></div>
            <div class="flex items-center space-x-8">
                <div id="dataTableLength" class="flex items-center"></div>
                <div class="flex items-center justify-between px-2.5 py-3">
                    <div id="dataTableInfoEntry" class=""></div>
                    <div id="dataTablePagination" class="flex items-center px-4"></div>
                </div>
            </div>
        </div>
    </div>

    <div x-data="formHandler()" x-init="init()" @submit.prevent="submitForm" id="form-modal"
        class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
        role="dialog" tabindex="-1" aria-labelledby="form-modal-label">
        <div
            class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-2xl md:w-full">
            <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
                <div class="flex items-center justify-between px-4">
                    <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                        Jenis Barang Baru
                    </h3>
                    <button type="button" class="text-red-500" aria-label="Close" data-hs-overlay="#form-modal">
                        <i class="text-2xl ph ph-x-circle"></i>
                    </button>
                </div>
                <div class="border-b border-[#D0D5DD]"></div>
                <div class="p-4 overflow-y-auto">
                    <div class="w-full mb-4">
                        <label for="jenisBarang" class="block text-sm text-[#344054]">Jenis Barang</label>
                        <input type="text" id="jenisBarang" x-model="form.jenisBarang"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                            placeholder="Masukkan Jenis Barang">
                    </div>
                    <div class="w-full">
                        <label for="tambahanBiaya" class="block text-sm text-[#344054] leading-5">Tambahan Biaya</label>
                        <input type="number" id="tambahanBiaya" x-model.number="form.tambahanBiaya"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base leading-6 text-[#667085]"
                            placeholder="Masukkan Tambahan Biaya">
                    </div>
                </div>
                <div class="flex items-center justify-end px-4 gap-x-2">
                    <button @click="submitForm()"
                        :class="{'bg-[#EAECF0] text-[#F8F8F8]': !formIsValid || loading, 'bg-[#7F56D9] text-white': formIsValid && !loading}"
                        :disabled="!formIsValid || loading"
                        class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg">
                        <span x-text="loading ? 'Loading...' : 'Simpan'"></span>
                        <i class="ph ph-floppy-disk ml-1.5" x-show="!loading"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div x-data="editFormHandler()" x-init="init()" @submit.prevent="submitEditForm" id="edit-modal"
        class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
        role="dialog" tabindex="-1" aria-labelledby="form-modal-label">
        <div
            class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-2xl md:w-full">
            <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
                <div class="flex items-center justify-between px-4">
                    <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                        Edit Jenis Barang
                    </h3>
                    <button type="button" class="text-red-500" aria-label="Close" data-hs-overlay="#edit-modal"
                        @click="closeModal">
                        <i class="text-2xl ph ph-x-circle"></i>
                    </button>
                </div>
                <div class="border-b border-[#D0D5DD]"></div>
                <div class="p-4 overflow-y-auto">
                    <div class="w-full mb-4">
                        <label for="jenisBarang" class="block text-sm text-[#344054]">Jenis Barang</label>
                        <input type="text" id="jenisBarang" x-model="form.jenisBarang"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                            placeholder="Masukkan Jenis Barang" />
                    </div>
                    <div class="w-full">
                        <label for="tambahanBiaya" class="block text-sm text-[#344054] leading-5">Tambahan Biaya</label>
                        <input type="number" id="tambahanBiaya" x-model.number="form.tambahanBiaya"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base leading-6 text-[#667085]"
                            placeholder="Masukkan Tambahan Biaya" />
                    </div>
                </div>
                <div class="flex items-center justify-end px-4 gap-x-2">
                    <button @click="submitEditForm()" :class="{
                        'bg-[#EAECF0] text-[#F8F8F8]': !formIsValid || loading,
                        'bg-[#7F56D9] text-white': formIsValid && !loading
                    }" :disabled="!formIsValid || loading"
                        class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg">
                        <span x-text="loading ? 'Loading...' : 'Simpan'"></span>
                        <i class="ph ph-floppy-disk ml-1.5" x-show="!loading"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formHandler() {
            return {
                form: {
                    jenisBarang: '',
                    tambahanBiaya: '',
                },
                loading: false,
                success: false,

                get formIsValid() {
                    return this.form.jenisBarang !== '' && this.form.tambahanBiaya !== '';
                },

                init() {
                    this.resetForm();
                },

                resetForm() {
                    this.form.jenisBarang = '';
                    this.form.tambahanBiaya = '';
                    this.success = false;
                },

                async submitForm() {
                    if (!this.formIsValid) return;

                    this.loading = true;

                    try {
                        let url = '/goods-types/store';
                        let method = 'POST';

                        let response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify(this.form)
                        });

                        if (response.ok) {
                            this.success = true;
                            this.resetForm();
                            this.closeModal();
                        } else {
                            console.error('Failed to save data');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                closeModal() {
                    const modal = document.querySelector('[data-hs-overlay="#form-modal"]');
                    if (modal) {
                        modal.click(); // Simulate a click to close the modal
                    }
                }
            };
        }

    </script>

    <script>
        function editFormHandler() {
            return {
                form: {
                    jenisBarang: '',
                    tambahanBiaya: ''
                },
                currentId: null, // Track which item is being edited
                loading: false,
                success: false,

                get formIsValid() {
                    return this.form.jenisBarang !== '' && this.form.tambahanBiaya !== '';
                },

                init() {
                    
                },

                resetForm() {
                    this.form.jenisBarang = '';
                    this.form.tambahanBiaya = '';
                    this.currentId = null; // Reset currentId on form reset
                    this.success = false;
                },

                async submitEditForm() {
                    if (!this.formIsValid) return;

                    this.loading = true;

                    try {
                        let response = await fetch(`/goods-types/update/${this.currentId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: JSON.stringify(this.form)
                        });

                        if (response.ok) {
                            this.success = true;
                            this.resetForm();
                            this.closeModal();
                        } else {
                            console.error('Failed to save data');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                closeModal() {
                    const modal = document.querySelector('#edit-modal');
                    if (modal) {
                        modal.classList.remove('hs-overlay-open'); // Close the modal
                        modal.classList.add('hidden'); // Ensure the modal is hidden
                    }
                }
            };
        }

    </script>

</x-layout>
