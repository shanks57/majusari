<div x-data="{ 
        form: { 
            tray_id: '', 
            position: ''
        } 
    }" id="hs-move-to-showcase-modal-{{ $goodsafe->id }}"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="hs-move-to-showcase-modal-{{ $goodsafe->id }}-label">
    <div
        class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div
            class="flex flex-col w-full bg-white border-[0.5px] shadow-sm pointer-events-auto rounded-xl border-[#D9D9D9] p-6 gap-4">
            <div class="flex items-center pb-4 border-b">
                <h3 id="hs-move-to-showcase-modal-{{ $goodsafe->id }}-label"
                    class="text-xl font-semibold text-[#344054]">
                    Pindahkan ke Etalase?
                </h3>
            </div>
            <div class="">
                <p class="mb-4 text-sm text-black">Apakah anda ingin memindahkan barang berikut untuk dipindahkan ke
                    etalase</p>
                <div class="flex justify-center items-center p-2 border border-[#D9D9D9] rounded-xl">
                    <!-- Column 1: Image and Details -->
                    <div class="flex flex-col w-full items-center gap-3 text-[#232323] text-center">
                        <img src="{{ asset('storage/' . $goodsafe->image) }}" class="object-cover rounded-lg w-14 h-14"
                            alt="{{ $goodsafe->name }}">
                        <div class="flex flex-col items-center justify-center w-full gap-1 text-center">
                            <span class="text-sm truncate max-w-28"> - {{ $goodsafe->goodsType->name }} ({{ $goodsafe->rate }}%) </span>
                            <span class="text-sm truncate max-w-28">Merek {{ $goodsafe->merk->name }} </span>
                            <span class="text-xs">{{ $goodsafe->size }}gr </span>
                            <span
                                class="text-sm text-[#9A9A9A] font-inter">
                            {{ \Carbon\Carbon::parse($goodsafe->date_entry)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Column 2: Form -->
                    <div class="flex flex-col items-start w-full gap-1">
                        <form method="POST" action="{{ route('goods.moveToShowcase', ['id' => $goodsafe->id]) }}">
                            @csrf
                            @method('PATCH')

                                <div x-data="showcaseForm()" x-init="init()" class="flex flex-col w-full gap-1">
                                    <div class="w-full mb-2">
                                        <label for="type_id" class="block text-xs text-gray-600">Jenis</label>
                                        <select id="type_id" name="type_id" x-model="form.type_id" @change="updateShowcases()"
                                            class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054] text-xs" required>
                                            <option value="" disabled selected>Pilih Jenis</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('type_id')
                                            <span class="text-sm text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Showcase Selection -->
                                    <div class="w-full mb-2">
                                        <label for="showcase_id" class="block text-xs text-gray-600">Etalase</label>
                                        <select id="showcase_id" name="showcase_id" x-model="form.showcase_id" @change="updateTrays()"
                                            class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054] text-xs" required>
                                            <option value="" disabled selected>Pilih Etalase</option>
                                            <template x-for="showcase in filteredShowcases" :key="showcase.id">
                                                <option :value="showcase.id" x-text="showcase.name"></option>
                                            </template>
                                        </select>
                                        @error('showcase_id')
                                            <span class="text-sm text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Tray Selection -->
                                    <div class="w-full mb-2">
                                        <label for="tray_id" class="block text-xs text-gray-600">Baki</label>
                                        <select id="tray_id" name="tray_id" x-model="form.tray_id" @change="updatePositions()"
                                            class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054] text-xs" required>
                                            <option value="" disabled selected>Pilih Baki</option>
                                            <template x-for="tray in filteredTrays" :key="tray.id">
                                                <option :value="tray.id" x-text="tray.code"></option>
                                            </template>
                                        </select>
                                        @error('tray_id')
                                            <span class="text-sm text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Position Selection -->
                                    <div class="w-full mb-2">
                                        <label for="position" class="block text-xs text-gray-600">Position</label>
                                        <select id="position" name="position" x-model="form.position"
                                            class="w-full px-3 py-2 mt-1 border rounded-lg border-[#D0D5DD] text-[#344054] text-xs" required>
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

                    </div>
                </div>

            </div>
            <div class="flex items-center justify-center gap-4">
                <button type="submit"
                    class="flex items-center px-4 py-3 gap-1.5 text-sm font-medium rounded-lg bg-[#6634BB] text-[#F8F8F8]">
                    <span>Ya <i class="ph ph-check"></i></span>
                </button>
                </form>
                <button type="button"
                    class="flex items-center px-4 py-3 gap-1.5 text-sm font-medium rounded-lg bg-white text-[#606060] border border-[#D0D5DD]"
                    data-hs-overlay="#hs-move-to-showcase-modal-{{ $goodsafe->id }}">
                    <span>Tutup</span> <i class="ph ph-x"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function showcaseForm() {
        return {
            form: {
                showcase_id: '',
                tray_id: '',
                type_id: '',
                position: '',
            },
            trays: @json($trays),
            showcases: @json($showcases),
            occupiedPositions: @json($occupiedPositions),
            filteredTrays: [],
            filteredShowcases: [],
            availablePositions: [],

            updateShowcases() {
                // Reset showcase_id, tray_id, dan position sebelum mengupdate showcases
                this.form.showcase_id = ''; // Reset showcase_id
                this.form.tray_id = ''; // Reset tray_id
                this.form.position = ''; // Reset position

                // Filter showcases berdasarkan type_id
                this.filteredShowcases = this.showcases.filter(showcase => showcase.type_id == this.form.type_id);
                this.updateTrays(); // Pastikan trays diupdate
            },

            updateTrays() {
                this.form.tray_id = ''; // Reset tray_id
                this.filteredTrays = this.trays.filter(tray => tray.showcase_id == this.form.showcase_id);
                this.updatePositions();
            },

            updatePositions() {
                const selectedTray = this.trays.find(tray => tray.id == this.form.tray_id);
                const capacity = selectedTray ? selectedTray.capacity : 0;

                // Get occupied positions for the selected tray
                const occupied = this.occupiedPositions[this.form.tray_id] || [];

                // Generate positions excluding occupied ones
                this.availablePositions = Array.from({ length: capacity }, (_, i) => i + 1)
                    .filter(pos => !occupied.includes(pos));
                this.form.position = '';
            },

            init() {
                // Optional: Initial showcase filtering if needed
                this.updateShowcases();
            }
        }
    }
</script>
