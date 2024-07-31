<div x-data="{ 
        form: { 
            code: '', 
            name: '', 
            category: '', 
            color: '', 
            rate: '', 
            size: '', 
            dimensions: '', 
            merk_id: '', 
            ask_rate: '', 
            bid_rate: '', 
            ask_price: '', 
            bid_price: '', 
            image: '', 
            type_id: '', 
            date_entry: '' 
        } 
    }"
    class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="form-modal-label" id="hs-add-modal">

    <div
        class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-2xl md:w-full">
        <div class="flex flex-col p-6 bg-white shadow-lg pointer-events-auto gap-y-4 rounded-xl">
            <div class="flex items-center justify-between px-4">
                <h3 id="hs-medium-modal-label" class="text-xl font-medium text-[#344054]">
                    Brankas Baru
                </h3>
                <button type="button" class="text-red-500" aria-label="Close" data-hs-overlay="#hs-add-modal">
                    <i class="text-2xl ph ph-x-circle"></i>
                </button>
            </div>
            <div class="border-b border-[#D0D5DD]"></div>
            <form action="{{ route('goods.safeStore') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="px-4 mb-4">
                    <div x-data="imageUploader" class="w-full mx-auto">
                        <!-- Drag and Drop Container -->
                        <div x-on:dragover.prevent="dragging = true" x-on:dragleave.prevent="dragging = false"
                            x-on:drop.prevent="handleDrop($event)"
                            :class="{' border-indigo-500': dragging, '': !dragging}"
                            class="relative flex items-center justify-center w-full h-36 py-4 bg-gray-100 border-2 border-dashed border-[#D0D5DD] rounded-lg cursor-pointer">

                            <!-- File Input (Hidden) -->
                            <input type="file" name="image" accept="image/*" required
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                x-on:change="handleFileSelect" x-ref="fileInput">

                            <!-- Image Preview -->
                            <template x-if="imageUrl">
                                <img :src="imageUrl" alt="Preview" class="object-contain w-full h-full" />
                            </template>

                            <!-- Placeholder -->
                            <template x-if="!imageUrl">
                                <div class="text-center ">
                                    <i class="text-4xl ph ph-image"></i>
                                    <p class="text-[#344054] text-sm">Tarik gambar brankas ke area ini atau klik untuk
                                        memilih</p>
                                </div>
                            </template>
                        </div>
                    </div>
                    @error('image')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="px-4 mb-4">
                    <label for="name" class="block text-sm text-gray-600">Nama Barang</label>
                    <input type="text" id="name" name="name" x-model="form.name"
                        class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan nama barang" required>
                    @error('name')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="px-4 mb-4">
                    <label for="code" class="block text-sm text-gray-600">Kode Barang</label>
                    <input type="text" id="code" name="code" x-model="form.code"
                        class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan Kode Barang" required>
                    @error('code')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-4 px-4">
                    <div class="w-full mb-4">
                        <label for="category" class="block text-sm text-gray-600">Kategori</label>
                        <input type="text" id="category" name="category" x-model="form.category"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan kategori" required>
                        @error('category')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="w-full mb-4">
                        <label for="type_id" class="block text-sm text-gray-600">Jenis</label>
                        <select id="type_id" name="type_id" x-model="form.type_id"
                            class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]" required>

                            <option value="" disabled selected>Pilih Jenis</option>
                            @foreach ($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('type_id')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="w-full mb-4">
                        <label for="color" class="block text-sm text-gray-600">Warna</label>
                        <select id="color" name="color" x-model="form.color"
                            class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]" required>
                            <!-- Add your options here -->
                            <option value="" disabled selected>Pilih Warna</option>
                            <option value="Gold">Gold</option>
                            <option value="Silver">Silver</option>
                        </select>
                        @error('color')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-4 px-4">
                    <div class="w-full mb-4">
                        <label for="rate" class="block text-sm text-gray-600">Kadar</label>
                        <input type="number" id="rate" name="rate" x-model="form.rate"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" step="0.001" min=0
                            placeholder="Masukkan kadar %" required>
                        @error('rate')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="w-full mb-4">
                        <label for="dimensions" class="block text-sm text-gray-600">Size</label>
                        <input type="text" id="dimensions" name="dimensions" x-model="form.dimensions"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan size" required>
                        @error('dimensions')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-4 px-4">
                    <div class="w-full mb-4">
                        <label for="size" class="block text-sm text-gray-600">Berat</label>
                        <input type="number" id="size" name="size" x-model="form.size"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" step="0.001" min=0
                            placeholder="Masukkan berat" required>
                        @error('size')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="w-full mb-4">
                        <label for="ask_rate" class="block text-sm text-gray-600">Nilai Tukar Atas</label>
                        <input type="number" id="ask_rate" name="ask_rate" x-model="form.ask_rate"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan nilai tukar atas"
                            required>
                        @error('ask_rate')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="w-full mb-4">
                        <label for="bid_rate" class="block text-sm text-gray-600">Nilai Tukar Bawah</label>
                        <input type="number" id="bid_rate" name="bid_rate" x-model="form.bid_rate"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan nilai tukar bawah"
                            required>
                        @error('bid_rate')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="px-4 mb-4">
                    <label for="date_entry" class="block text-sm text-gray-600">Tanggal Masuk</label>
                    <input type="date" id="date_entry" name="date_entry" x-model="form.date_entry"
                        class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]" required>
                    @error('date_entry')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div class="px-4 mb-4">
                    <div class="w-full mb-4">
                        <label for="merk_id" class="block text-sm text-gray-600">Merk</label>
                        <select id="merk_id" name="merk_id" x-model="form.merk_id"
                            class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]" required>
                            <option value="" disabled selected>Pilih merk</option>
                            @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('merk_id')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-4 px-4">
                    <div class="w-full mb-4">
                        <label for="ask_price" class="block text-sm text-gray-600">Harga Jual</label>
                        <input type="number" id="ask_price" name="ask_price" x-model="form.ask_price"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Harga jual" required>
                        @error('ask_price')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="w-full mb-4">
                        <label for="bid_price" class="block text-sm text-gray-600">Harga Bawah</label>
                        <input type="number" id="bid_price" name="bid_price" x-model="form.bid_price"
                            class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Harga bawah" required>
                        @error('bid_price')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end px-4 gap-x-2">
                    <button type="submit"
                        :disabled="!form.code || !form.name || !form.category || !form.color || !form.rate || !form.size || !form.dimensions || !form.merk_id || !form.ask_rate || !form.bid_rate || !form.ask_price || !form.bid_price || !form.type_id || !form.date_entry"
                        class="flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 rounded-lg bg-[#7F56D9] text-white"
                        :class="{ 'opacity-50 cursor-not-allowed': !form.code || !form.name || !form.category || !form.color || !form.rate || !form.size || !form.dimensions || !form.merk_id || !form.ask_rate || !form.bid_rate || !form.ask_price || !form.bid_price || !form.type_id || !form.date_entry }">
                        Simpan
                        <i class="ph ph-floppy-disk ml-1.5"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

