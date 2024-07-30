<div 
    x-data="showcaseForm()" 
    class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" 
    role="dialog" tabindex="-1" aria-labelledby="form-modal-label" id="hs-add-modal">
    
    <div class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-2xl md:w-full">
        <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
            <div class="flex items-center justify-between px-4">
                <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                    Etalase Baru
                </h3>
                <button type="button" class="text-red-500" aria-label="Close" data-hs-overlay="#hs-add-modal">
                    <i class="text-2xl ph ph-x-circle"></i>
                </button>
            </div>
            <div class="border-b border-[#D0D5DD]"></div>
            <form action="{{ route('master.showcase.store') }}" method="post">
                @csrf
                <div class="p-4 overflow-y-auto">
                    <div class="w-full mb-4">
                        <label for="code" class="block text-sm text-[#344054]">Kode Etalase</label>
                        <input type="text" id="code" name="code" x-model="form.code"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                            placeholder="Masukkan Kode Etalase" required>
                    </div>

                    <div class="w-full mb-4">
                        <label for="name" class="block text-sm text-[#344054]">Nama Etalase</label>
                        <input type="text" id="name" name="name" x-model="form.name"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                            placeholder="Masukkan Nama Etalase" required>
                    </div>

                    <div class="w-full mb-4">
                        <label for="type_id" class="block text-sm text-[#344054] leading-5">Jenis Barang</label>
                        <select id="type_id" name="type_id" x-model="form.type_id" class="block w-full px-3.5 py-2.5 mt-1.5 text-base rounded-lg pe-9 disabled:pointer-events-none text-[#667085] focus:outline-none focus:border-[#79799B] border border-[#D0D5DD]" required>
                            <option value="" disabled selected>Pilih Jenis Barang</option>
                            @foreach($goodsTypes as $goodsType)
                                <option value="{{ $goodsType->id }}">{{ $goodsType->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full mb-4">
                        <label for="jumlah_baki" class="block text-sm text-[#344054]">Jumlah Baki</label>
                        <div class="flex items-center justify-between gap-4">
                            <input type="number" id="jumlah_baki" name="jumlah_baki" x-model="form.jumlah_baki"
                            class="w-full px-3.5 py-2.5 mt-1.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]"
                            placeholder="Masukkan Jumlah Baki" required min="1">
                            <button type="button" @click="addTrays" class="px-3.5 py-2.5 mt-1.5 rounded-lg bg-[#7F56D9] text-[#F6F6F6]"><i class="ph ph-plus"></i></button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#EDEDED]">
                            <thead class="bg-[#79799B] text-white font-normal">
                                <tr>
                                    <th scope="col" class="px-5 py-3 text-left text-sm uppercase tracking-wider rounded-l">
                                        Kode Baki
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm uppercase tracking-wider rounded-r">
                                        Kapasitas
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" x-ref="trayList">
                                <template x-for="(tray, index) in trays" :key="index">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" :name="'trays['+index+'][codeTray]'" x-model="tray.codeTray"
                                                class="w-full px-3.5 py-2.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]" readonly>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" :name="'trays['+index+'][capacity]'" x-model="tray.capacity"
                                                class="w-full px-3.5 py-2.5 border border-[#D0D5DD] rounded-lg focus:outline-none focus:border-[#79799B] text-base text-[#667085]" min="1">
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex items-center justify-end px-4 gap-x-2">
                    <button type="submit"
                        :disabled="!form.name || !form.type_id || !form.code || trays.length === 0"
                        class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white"
                        :class="{ 'opacity-50 cursor-not-allowed': !form.name || !form.type_id || !form.code || trays.length === 0 }">
                        <span>Simpan</span>
                        <i class="ph ph-floppy-disk ml-1.5"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showcaseForm() {
        return {
            form: {
                name: '',
                type_id: '',
                code: '',
                jumlah_baki: 0,
                trays: []
            },
            trays: [],

            addTrays() {
                this.trays = [];

                if (this.form.name) {
                    for (let i = 0; i < this.form.jumlah_baki; i++) {
                        this.trays.push({
                            codeTray: `${this.form.name}-${i + 1}`,
                            capacity: 1
                        });
                    }
                } else {
                    alert('Nama etalase harus diisi terlebih dahulu!');
                }
            }
        };
    }
</script>
