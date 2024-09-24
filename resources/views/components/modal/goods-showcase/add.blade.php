<div x-data="{ 
        form: { 
            code: '{{ $latestAddedGoods->code ?? '' }}',
            unit: '{{ $latestAddedGoods->unit ?? '' }}',  
            name: '{{ $latestAddedGoods->name ?? '' }}', 
            category: '{{ $latestAddedGoods->category ?? '' }}', 
            color: '{{ $latestAddedGoods->color ?? '' }}', 
            rate: '{{ $latestAddedGoods->rate ?? '' }}', 
            size: '{{ $latestAddedGoods->size ?? '' }}', 
            dimensions: '{{ $latestAddedGoods->dimensions ?? '' }}', 
            merk_id: '{{ $latestAddedGoods->merk_id ?? '' }}', 
            ask_rate: '{{ $latestAddedGoods->ask_rate ?? '' }}', 
            bid_rate: '{{ $latestAddedGoods->bid_rate ?? '' }}', 
            ask_price: '{{ $latestAddedGoods->ask_price ?? '' }}', 
            bid_price: '{{ $latestAddedGoods->bid_price ?? '' }}', 
            image: '{{ $latestAddedGoods->image ?? '' }}', 
            type_id: '{{ $latestAddedGoods->type_id ?? '' }}', 
            {{-- tray_id: '{{ $latestAddedGoods->tray_id }}', 
            position: '{{ $latestAddedGoods->position }}',  --}}
            date_entry: '{{ $latestAddedGoods->date_entry ?? '' }}' 
        } 
    }"
    class="hs-overlay hidden size-full fixed top-0 start-0 p-6 mt-4 mr-4 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="form-modal-label" id="hs-add-modal">

    <div
        class="m-3 transition-all ease-out opacity-0 md:ml-auto hs-overlay-open:opacity-100 hs-overlay-open:duration-500 md:max-w-2xl md:w-full">
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
            <form action="{{ route('goods.showcaseStore') }}" method="post" enctype="multipart/form-data">
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
                                    <p class="text-[#344054] text-sm">Tarik gambar etalase ke area ini atau klik untuk
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
                        class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan nama barang" required
                        maxlength="20">
                    @error('name')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- <div class="px-4 mb-4">
                    <label for="code" class="block text-sm text-gray-600">Kode Barang</label>
                    <input type="text" id="code" name="code" x-model="form.code"
                        class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan Kode Barang" required
                        max="10">
                    @error('code')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div> -->

                <div class="px-4 mb-4">
                    <label for="category" class="block text-sm text-gray-600">Kategori</label>
                    <input type="text" id="category" name="category" x-model="form.category"
                        class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan kategori" required
                        maxlength="20">
                    @error('category')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <div x-data="showcaseForm()" x-init="init">
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

                        <div class="w-full mb-4">
                            <label for="type_id" class="block text-sm text-gray-600">Jenis</label>
                            <select id="type_id" name="type_id" x-model="form.type_id" @change="updateShowcases()"
                                class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]"
                                required>
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
                                class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]"
                                required>
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
                            @error('color')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex gap-4 px-4">
                        <div class="w-full mb-4">
                            <label for="rate" class="block text-sm text-gray-600">Kadar</label>
                            <input type="number" id="rate" name="rate" x-model="form.rate"
                                class="w-full px-3 py-2 mt-1 border rounded-lg" step="0.001" min=0 max=100
                                placeholder="Masukkan kadar %" required>
                            @error('rate')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="w-full mb-4">
                            <label for="dimensions" class="block text-sm text-gray-600">Size</label>
                            <input type="number" id="dimensions" name="dimensions" x-model="form.dimensions"
                                class="w-full px-3 py-2 mt-1 border rounded-lg" placeholder="Masukkan size" min=0
                                max=100 required>
                            @error('dimensions')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div x-data="priceCalculator({{ $lastKursPrice }})">
                        <div class="flex gap-4 px-4">
                            <div class="w-full mb-4">
                                <label for="size" class="block text-sm text-gray-600">Berat</label>
                                <input type="number" id="size" name="size" x-model="form.size" @input="updatePrices"
                                    class="w-full px-3 py-2 mt-1 border rounded-lg" step="0.001" min=0
                                    placeholder="Masukkan berat" required>
                                @error('size')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full mb-4">
                                <label for="ask_rate" class="block text-sm text-gray-600">Nilai Tukar Atas</label>
                                <input type="number" id="ask_rate" name="ask_rate" x-model="form.ask_rate"
                                    @input="updatePrices" class="w-full px-3 py-2 mt-1 border rounded-lg"
                                    placeholder="Masukkan nilai tukar atas" min=0 max=100 required>
                                @error('ask_rate')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full mb-4">
                                <label for="bid_rate" class="block text-sm text-gray-600">Nilai Tukar Bawah</label>
                                <input type="number" id="bid_rate" name="bid_rate" x-model="form.bid_rate"
                                    @input="updatePrices" class="w-full px-3 py-2 mt-1 border rounded-lg"
                                    placeholder="Masukkan nilai tukar bawah" min=0 max=100 required>
                                @error('bid_rate')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex gap-4 px-4">
                            <div class="w-full mb-4">
                                <label for="showcase_id" class="block text-sm text-gray-600">Etalase</label>
                                <select id="showcase_id" name="showcase_id" x-model="form.showcase_id" @change="updateTrays()"
                                    class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]"
                                    required>
                                    <template x-for="showcase in filteredShowcases" :key="showcase.id">
                                        <option :value="showcase.id" x-text="showcase.name"></option>
                                    </template>
                                </select>
                                @error('showcase_id')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full mb-4">
                                <label for="tray_id" class="block text-sm text-gray-600">Baki</label>
                                <select id="tray_id" name="tray_id" x-model="form.tray_id" @change="updatePositions()"
                                    class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]"
                                    required>
                                    <option value="" disabled selected>Pilih Baki</option>
                                    <template x-for="tray in filteredTrays" :key="tray.id">
                                        <option :value="tray.id" x-text="tray.code"></option>
                                    </template>
                                </select>
                                @error('tray_id')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full mb-4">
                                <label for="position" class="block text-sm text-gray-600">Position</label>
                                <select id="position" name="position" x-model="form.position"
                                    class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]"
                                    required>
                                    <option value="" disabled selected>Pilih Position</option>
                                    <template x-for="position in availablePositions" :key="position">
                                        <option :value="position" x-text="position"></option>
                                    </template>
                                </select>
                                @error('position')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="px-4 mb-4">
                            <label for="date_entry" class="block text-sm text-gray-600">Tanggal Masuk</label>
                            <input type="date" id="date_entry" name="date_entry" x-model="form.date_entry"
                                class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]"
                                required>
                            @error('date_entry')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="px-4 mb-4">
                            <div class="w-full mb-4">
                                <label for="merk_id" class="block text-sm text-gray-600">Merk</label>
                                <select id="merk_id" name="merk_id" x-model="form.merk_id"
                                    class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054]"
                                    required>
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
                            <div class="w-full p-3 mb-4 bg-gray-100 border rounded">
                                <label for="ask_price" class="block text-sm font-normal text-gray-700">Harga Jual</label>
                                <div class="flex items-center gap-1 mt-1 text-lg text-gray-700">
                                    <span>Rp</span> <input type="number" id="ask_price" name="ask_price" x-model="form.ask_price"
                                        class="w-full text-lg bg-gray-100 border-transparent border-none focus:outline-none focus:border-transparent focus:ring-0" placeholder="Harga jual" readonly>
                                </div>
                                @error('ask_price')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="w-full p-3 mb-4 bg-gray-100 border rounded">
                                <label for="bid_price" class="block text-sm font-normal text-gray-700">Harga Bawah</label>
                                <div class="flex items-center gap-1 mt-1 text-lg text-gray-700">
                                    <span>Rp</span> <input type="number" id="bid_price" name="bid_price" x-model="form.bid_price"
                                        class="w-full text-lg bg-gray-100 border-transparent border-none focus:outline-none focus:border-transparent focus:ring-0" placeholder="Harga bawah" readonly>
                                </div>
                                @error('bid_price')
                                <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
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
    //define a function to set cookies
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }
</script>

<script>
    function showcaseForm() {
        return {
            trays: @json($trays),
            showcases: @json($showcases),
            occupiedPositions: @json($occupiedPositions),
            filteredTrays: [],
            filteredShowcases: [],
            availablePositions: [],

            // Mengupdate showcases berdasarkan type_id
            updateShowcases() {
                // Reset showcase_id, tray_id, dan position sebelum mengupdate showcases
                this.form.showcase_id = null; // Set to null to ensure default option is selected
                this.form.tray_id = ''; // Reset tray_id
                this.form.position = ''; // Reset position

                // Filter showcases berdasarkan type_id
                this.filteredShowcases = this.showcases.filter(showcase => showcase.type_id == this.form.type_id);
                this.filteredShowcases.unshift({
                    id: '',
                    name: 'Pilih Etalase',
                    disabled: true
                });
                // Memastikan trays diupdate setiap kali showcase_id diubah
                this.updateTrays();
            },

            // Mengupdate trays berdasarkan showcase_id
            updateTrays() {
                this.form.tray_id = ''; // Reset tray_id
                this.form.position = ''; // Reset position

                this.filteredTrays = this.trays.filter(tray => tray.showcase_id == this.form.showcase_id);

                // Memastikan positions diupdate setiap kali tray_id diubah
                this.updatePositions();
            },

            // Mengupdate positions berdasarkan tray_id
            updatePositions() {
                const selectedTray = this.trays.find(tray => tray.id == this.form.tray_id);
                const capacity = selectedTray ? selectedTray.capacity : 0;

                const occupied = this.occupiedPositions[this.form.tray_id] || [];

                this.availablePositions = Array.from({
                    length: capacity
                }, (_, i) => i + 1).filter(pos => !occupied.includes(pos));
                this.form.position = '';
            },

            // Inisialisasi fungsi
            init() {
                this.updateShowcases(); // Mengupdate showcases saat inisialisasi
            }
        }
    }
</script>

<script>
    function priceCalculator(lastKurs) {
        return {
            // form: {
            //     ask_rate: 0,
            //     bid_rate: 0,
            //     size: 0,
            //     ask_price: 0,
            //     bid_price: 0,
            // },
            updatePrices() {
                const size = this.form.size;
                const ask_rate = this.form.ask_rate / 100;
                const bid_rate = this.form.bid_rate / 100;

                this.form.ask_price = (ask_rate * size * lastKurs).toFixed(0);
                this.form.bid_price = (bid_rate * size * lastKurs).toFixed(0);
            }
        }
    }
</script>