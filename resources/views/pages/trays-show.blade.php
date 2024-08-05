@section('title', 'Show Baki')
<x-layout>
    <div class="container py-4 mx-auto">
        <a href="/" class="flex items-center gap-4 mb-4 text-gray-500 hover:text-gray-700">
            <i class="text-2xl ph ph-caret-left"></i>
            <h1 class="text-2xl ">Etalase {{ $tray->showcase->name }}</h1>
        </a>

        <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-3">
            <div class="grid gap-3 p-4 bg-white border rounded-lg">
                <h2 class="text-lg">Jumlah Barang di Baki</h2>
                <p class="text-4xl">{{ $countGoods }}</p>
                <p class="text-gray-500">Jumlah barang baki</p>
            </div>
            <div class="grid gap-3 p-4 bg-white border rounded-lg">
                <h2 class="text-lg">Slot Kosong Baki</h2>
                <p class="text-4xl">{{ $tray->capacity - $countGoods }}</p>
                <p class="text-gray-500">Jumlah slot kosong baki</p>
            </div>
            <div class="grid gap-3 p-4 bg-white border rounded-lg">
                <h2 class="text-lg">Berat</h2>
                <p class="text-4xl">{{ $totalWeight }} gr</p>
                <p class="text-gray-500">Jumlah total berat baki</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6">
            @for ($i = 1; $i <= $trayCapacity; $i++)
                @php
                    // Find the good for the current position
                    $good = $goods->firstWhere('position', $i);
                @endphp

                @if ($good)
                    <button
                        type="button"
                        class="p-4 transition-shadow bg-white border cursor-pointer hover:shadow-md"
                        aria-haspopup="dialog"
                        aria-expanded="false"
                        aria-controls="hs-move-to-safe-modal-{{ $good->id }}"
                        data-hs-overlay="#hs-move-to-safe-modal-{{ $good->id }}"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-lg font-semibold">{{ $good->name }}100{{ $i }}</p>
                            <i class="ph ph-caret-right"></i>
                        </div>
                        <div class="flex justify-between">
                            <p class="text-sm text-yellow-500">{{ $good->rate }}%</p>
                            <p class="text-sm text-green-500">{{ $good->size }}gr</p>
                        </div>
                    </button>
                    {{-- modal pindah ke brankas --}}
                    @include('components.modal.master-trays.modal-move-to-safe')
                @else
                    <button
                        type="button"
                        aria-haspopup="dialog"
                        aria-expanded="false"
                        aria-controls="hs-scale-animation-modal"
                        data-hs-overlay="#hs-add-modal-{{ $i }}"
                        class="p-4 transition-shadow bg-[#F9F5FF] border cursor-pointer hover:shadow-md flex items-center justify-center"
                    >
                        <div class="flex flex-col items-center justify-center mb-3">
                            <p class="text-sm font-medium text-[#151617] mb-2">Baki Kosong</p>
                            <span class="flex items-center justify-center text-[#7F56D9] text-xs">
                                <i class="ph ph-plus-circle"></i> Tambah Baki
                            </span>
                        </div>
                    </button>
                @include('components.modal.master-trays.add')
                @endif
            @endfor
        </div>
    </div>
</x-layout>

@include('components.modal.master-trays.success-modal')
@include('components.modal.master-trays.success')
@include('components.modal.goods-showcase.error-modal')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('imageUploader', (existingImageUrl) => ({
            imageUrl: existingImageUrl || '',
            dragging: false,
            selectedFile: null,

            handleDrop(event) {
                this.dragging = false;
                const file = event.dataTransfer.files[0];
                if (file) {
                    this.readFile(file);
                    this.selectedFile = file;
                    this.updateFileInput(file);
                }
            },

            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    this.readFile(file);
                    this.selectedFile = file;
                }
            },

            updateFileInput(file) {
                // Create a new DataTransfer object to set the files
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                this.$refs.fileInput.files = dataTransfer.files;
            },

            readFile(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageUrl = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('Please upload a valid image file.');
                }
            }
        }));
    });
</script>   