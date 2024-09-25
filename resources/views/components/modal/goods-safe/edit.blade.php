<div x-data="{ 
        form: {
            id: '{{ $goodsafe->id }}',
            code: '{{ $goodsafe->code }}', 
            name: '{{ $goodsafe->name }}', 
            unit: '{{ $goodsafe->unit }}', 
            category: '{{ $goodsafe->category }}', 
            color: '{{ $goodsafe->color }}', 
            rate: '{{ $goodsafe->rate }}', 
            size: '{{ $goodsafe->size }}', 
            dimensions: '{{ $goodsafe->dimensions }}', 
            merk_id: '{{ $goodsafe->merk_id }}', 
            ask_rate: '{{ $goodsafe->ask_rate }}', 
            bid_rate: '{{ $goodsafe->bid_rate }}', 
            ask_price: '{{ $goodsafe->ask_price }}', 
            bid_price: '{{ $goodsafe->bid_price }}', 
            image: '{{ $goodsafe->image }}', 
            type_id: '{{ $goodsafe->type_id }}', 
            date_entry: '{{ $goodsafe->date_entry }}' 
        }
    }"
    class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="edit-modal-label" id="hs-edit-modal-{{ $goodsafe->id }}">
    <div
        class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-2xl md:w-full">
        <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
            <div class="flex items-center justify-between px-4">
                <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                    Update Brankas
                </h3>
                <button type="button" class="text-red-500" aria-label="Close"
                    data-hs-overlay="#hs-edit-modal-{{ $goodsafe->id }}">
                    <i class="text-2xl ph ph-x-circle"></i>
                </button>
            </div>
            <div class="border-b border-[#D0D5DD]"></div>
            <form action="{{ route('goods.safeUpdate', $goodsafe->id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="px-4">
                    <div x-data="imageUploader('{{ $goodsafe->image ? asset('storage/' . $goodsafe->image) : '' }}')"
                        class="w-full mx-auto">
                        <!-- Drag and Drop Container -->
                        <div x-on:dragover.prevent="dragging = true" x-on:dragleave.prevent="dragging = false"
                            x-on:drop.prevent="handleDrop($event)"
                            :class="{' border-indigo-500': dragging, '': !dragging}"
                            class="relative flex items-center justify-center w-full h-36 py-4 bg-gray-100 border-2 border-dashed border-[#D0D5DD] rounded-lg cursor-pointer">

                            <input type="file" name="image" accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                x-on:change="handleFileSelect" x-ref="fileInput">

                            <template x-if="imageUrl">
                                <img :src="imageUrl" alt="Preview" class="object-contain w-full h-full" />
                            </template>

                            <template x-if="!imageUrl">
                                <div class="text-center">
                                    <i class="text-4xl ph ph-image"></i>
                                    <p class="text-[#344054] text-sm">Tarik gambar etalase ke area ini atau klik untuk
                                        memilih</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="px-4">
                    <label for="name" class="block text-sm text-gray-600">Nama Barang</label>
                    <input type="text" id="name" name="name" x-model="form.name"
                        class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan nama barang" required maxlength="20">
                </div>

                <div class="px-4">
                        <label for="category" class="block text-sm text-gray-600">Kategori</label>
                        <input type="text" id="category" name="category" x-model="form.category"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan kategori" required maxlength="20">
                    </div>

                <div class="flex gap-4 px-4">
                     <div class="w-full mb-4">
                            <label for="unit" class="block text-sm text-gray-600">Satuan</label>
                            <select id="unit" name="unit" x-model="form.unit"
                                class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]"
                                required>
                                <option value="" disabled selected>Pilih Satuan</option>
                                <option value="pcs">PCS</option>
                                <option value="pair">Pasang</option>
                                <option value="set">Set</option>
                            </select>
                            @error('unit')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                    </div>

                    <div class="w-full ">
                        <label for="color" class="block text-sm text-gray-600">Warna</label>
                        <select id="color" name="color" x-model="form.color"
                            class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]" required>
                            <!-- Add your options here -->
                            <option value="" disabled selected>Pilih Warna</option>
                                <option value="Gold">Gold</option>
                                <option value="Silver">Silver</option>
                                <option value="White Gold">White Gold</option>
                                <option value="Black Gold">Black Gold</option>
                                <option value="Rose Gold">Rose Gold</option>
                                <option value="Yellow Gold">Yellow Gold</option>
                                <option value="Gold Kombinasi">Gold Kombinasi</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-4 px-4">
                    <div class="w-full ">
                        <label for="rate" class="block text-sm text-gray-600">Kadar</label>
                        <input type="number" id="rate" name="rate" x-model="form.rate"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" step="0.001" min=0 max=100
                            placeholder="Masukkan kadar %" required>
                    </div>

                    <div class="w-full ">
                        <label for="dimensions" class="block text-sm text-gray-600">Size</label>
                        <input type="number" id="dimensions" name="dimensions" x-model="form.dimensions"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan size" min=0 max=100 required>
                    </div>
                </div>

                <div x-data="priceCalculatorEdit()" x-init="init()" @input="updatePricesEdit()">
                <div class="flex gap-4 px-4 mb-4">
                    <div class="w-full ">
                        <label for="size" class="block text-sm text-gray-600">Berat</label>
                        <input type="number" id="size" name="size" x-model="form.size"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" step="0.001" min=0
                            placeholder="Masukkan berat" required>
                    </div>

                    <div class="w-full ">
                        <label for="ask_rate" class="block text-sm text-gray-600">Nilai Tukar Atas</label>
                        <input type="number" id="ask_rate" name="ask_rate" x-model="form.ask_rate"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan nilai tukar atas" min=0 max=100
                            required>
                    </div>

                    <div class="w-full ">
                        <label for="bid_rate" class="block text-sm text-gray-600">Nilai Tukar Bawah</label>
                        <input type="number" id="bid_rate" name="bid_rate" x-model="form.bid_rate"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan nilai tukar bawah" min=0 max=100
                            required>
                    </div>
                </div>

                <div class="px-4 mb-4">
                    <label for="date_entry" class="block text-sm text-gray-600">Tanggal Masuk</label>
                    <input type="date" id="date_entry" name="date_entry" x-model="form.date_entry"
                        class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]" required>
                </div>

                <div class="px-4 mb-4">
                        <div class="w-full">
                            <label for="merk_id" class="block text-sm text-gray-600">Merk</label>
                            <select id="merk_id" name="merk_id" x-model="form.merk_id"
                                class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]" required>
                                <option value="" disabled selected>Pilih merk</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $brand->id == $goodsafe->merk_id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                                @endforeach
                                
                                <!-- Menambahkan secara manual jika merk_id status = 0 -->
                                @if ($goodsafe->merk && $goodsafe->merk->status == 0)
                                <option value="{{ $goodsafe->merk->id }}" selected>{{ $goodsafe->merk->name }}</option>
                                @endif
                            </select>
                        </div>
                </div>

                <div class="flex gap-4 px-4">
                        <div class="w-full p-3 mb-4 bg-gray-100 border rounded">
                            <label for="ask_price" class="block text-sm font-normal text-gray-700">Harga Jual</label>
                            <div class="flex items-center gap-1 mt-1 text-lg text-gray-700">
                                <span>Rp</span>
                                <input type="number" id="ask_price" name="ask_price" x-model="form.ask_price"
                                    class="w-full text-lg bg-gray-100 border-transparent border-none focus:outline-none focus:border-transparent focus:ring-0"
                                    placeholder="Harga jual" required readonly>
                            </div>
                        </div>

                        <div class="w-full p-3 mb-4 bg-gray-100 border rounded">
                            <label for="bid_price" class="block text-sm font-normal text-gray-700">Harga Bawah</label>
                            <div class="flex items-center gap-1 mt-1 text-lg text-gray-700">
                                <span>Rp</span>
                                <input type="number" id="bid_price" name="bid_price" x-model="form.bid_price"
                                    class="w-full text-lg bg-gray-100 border-transparent border-none focus:outline-none focus:border-transparent focus:ring-0"
                                    placeholder="Harga bawah" required readonly>
                            </div>
                        </div>
                </div>
            </div>

                <div class="flex items-center justify-end px-4 gap-x-2">
                    <button type="submit"
                        class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white">
                        Simpan
                        <i class="ph ph-floppy-disk ml-1.5"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function priceCalculatorEdit() {
        return {
            lastKurs: @json($lastKursPrice), // Nilai tukar terbaru dari backend

            updatePricesEdit() {
                this.form.ask_price = ((this.form.ask_rate / 100) * this.form.size * this.lastKurs).toFixed(0);
                this.form.bid_price = ((this.form.bid_rate / 100) * this.form.size * this.lastKurs).toFixed(0);
            },

            init() {
                this.updatePricesEdit(); // Hitung harga saat inisialisasi
            }
        }
    }
</script>