<div id="hs-move-to-showcase-modal-{{ $goodsafe->id }}"
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
                <div class="flex items-center justify-stretch p-3 border border-[#D9D9D9] rounded-xl">
                    <div class="flex items-center gap-3 justify-start text-[#232323]">
                        <img src="{{ asset('storage/' . $goodsafe->image) }}" class="object-cover rounded-lg size-14"
                            alt="{{ $goodsafe->name }}">
                        <div class="flex flex-col items-start gap-1 mr-4">
                            <span class="text-sm"> - {{ $goodsafe->goodsType->name }} ({{ $goodsafe->rate }}%) </span>
                            <span class="text-sm">Merek {{ $goodsafe->merk->name }} </span>
                            <span class="text-xs">{{ $goodsafe->size }}gr </span>
                        </div>
                    </div>
                    <div class="flex flex-col items-start gap-1">
                        <form method="POST" action="{{ route('goods.moveToShowcase', ['id' => $goodsafe->id]) }}">
                            @csrf
                            @method('PATCH')
                            <div class="text-xs font-semibold">
                                <div class="mb-1">
                                    <label for="showcase-select" class="block mb-2 text-xs font-medium">Pilih
                                        Etalase</label>
                                    <select id="showcase-select"
                                        class="block w-full px-4 py-3 text-xs border border-gray-200 rounded-lg pe-9 focus:outline-none disabled:opacity-50 disabled:pointer-events-none" required>
                                        <option value="" selected disabled>Pilih Etalase</option>
                                        @foreach($showcases as $showcase)
                                        <option value="{{ $showcase->id }}">{{ $showcase->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="tray-select" class="block mb-2 text-xs font-medium">Pilih Baki</label>
                                    <select id="tray-select" name="tray-select"
                                        class="block w-full px-4 py-3 text-xs border border-gray-200 rounded-lg pe-9 focus:outline-none disabled:opacity-50 disabled:pointer-events-none" required>
                                        <option value="" selected disabled>Pilih Baki</option>
                                    </select>
                                </div>

                            </div>
                            <span
                                class="text-xs text-[#9A9A9A] font-inter">{{ $goodsafe->created_at->format('d F Y') }}</span>
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
    document.addEventListener('DOMContentLoaded', function () {
        const showcases = @json($showcases);
        const trays = @json($trays);

        const showcaseSelect = document.getElementById('showcase-select');
        const traySelect = document.getElementById('tray-select');

        showcaseSelect.addEventListener('change', function () {
            const selectedShowcaseId = this.value;

            // Kosongkan opsi baki sebelumnya
            traySelect.innerHTML = '<option value="" selected disabled>Pilih Baki</option>';

            // Filter baki berdasarkan etalase yang dipilih
            const filteredTrays = trays.filter(tray => tray.showcase_id == selectedShowcaseId);

            // Tambahkan opsi baki ke dropdown baki
            filteredTrays.forEach(tray => {
                const option = document.createElement('option');
                option.value = tray.id;
                option.textContent =
                    `${tray.code} - Sisa Kapasitas: ${tray.remaining_capacity}`;
                traySelect.appendChild(option);
            });
        });
    });

</script>
