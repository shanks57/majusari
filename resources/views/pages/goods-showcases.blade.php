@section('title', 'Etalase')
{{-- @include('components.datatables') --}}
<x-layout>
    <x-header title="Etalase" subtitle="Etalase">
        @role('superadmin|home_employee')
        <x-button-add url="{{ route('goods-types.index') }}">
            Tambah Barang Etalase
        </x-button-add>
        @endrole
    </x-header>
    <div class="container py-4 mx-auto">
        

    <form action="{{ route('goods.showcase') }}" method="GET">
        <div class="inline-flex justify-center w-full mx-auto rounded-md" role="group">
            <a href="{{ route('goods.showcase.export-pdf', request()->all()) }}" class="p-3 font-normal text-white bg-gray-400 rounded-s-xl hover:bg-gray-500 focus:z-10 focus:ring-1 focus:ring-gray-500">
                PDF
            </a>
            <a href="{{ route('goods.showcase.export-excel', request()->all()) }}" class="px-2.5 py-3 font-normal text-white bg-gray-400 hover:bg-gray-500 focus:z-10 focus:ring-1 focus:ring-gray-500">
                Excel
            </a>
            <a href="{{ route('goods.showcase.print', request()->all()) }}" target="_blank" class="p-3 font-normal text-white bg-gray-400 rounded-e-xl hover:bg-gray-500 focus:z-10 focus:ring-1 focus:ring-gray-500">
                Print
            </a>
        </div>

        <div class="mt-4 overflow-hidden overflow-x-auto border border-gray-200 rounded-t-lg shadow-lg" x-data="multiDelete()">
             <button @click="deleteSelected()" class="m-2 px-4 py-2 bg-red-600 text-white rounded">
                Hapus Terpilih
            </button>
            <table id="etalaseTable" class="min-w-full bg-white border border-gray-200 display">
                <thead>
                    @php
                        $sortField = request('sort_field', 'created_at');
                        $sortDirection = request('sort_direction', 'desc');
                    @endphp
                    <input type="hidden" name="sort_field" value="{{ request('sort_field', 'created_at') }}">
                    <input type="hidden" name="sort_direction" value="{{ request('sort_direction', 'desc') }}">
                    <tr class="w-full bg-[#79799B] text-white text-sm leading-normal">
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all" @click="toggleAll">
                        </th>
                        <th class="py-3 px-4 !font-normal text-center">
                            Kode Barang
                            <a href="{{ request()->fullUrlWithQuery(['sort_field' => 'code', 'sort_direction' => ($sortField === 'code' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}">
                                @if($sortField === 'code')
                                    {{ $sortDirection === 'asc' ? '🔼' : '🔽' }}
                                @else
                                    🔼🔽
                                @endif
                            </a>
                            <input type="text" name="code" value="{{ request('code') }}" onkeydown="if(event.key === 'Enter') event.preventDefault();" onchange="this.form.submit()" class="p-1 mt-1 text-sm text-black border rounded-md">
                        </th>
                        <th class="py-3 px-4 !font-normal text-center">
                            Tanggal Masuk
                            <a href="{{ request()->fullUrlWithQuery(['sort_field' => 'date_entry', 'sort_direction' => ($sortField === 'date_entry' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}">
                                @if($sortField === 'date_entry')
                                    {{ $sortDirection === 'asc' ? '🔼' : '🔽' }}
                                @else
                                    🔼🔽
                                @endif
                            </a>
                            <input type="date" name="date_entry" value="{{ request('date_entry') }}" onkeydown="if(event.key === 'Enter') event.preventDefault();" onchange="this.form.submit()" class="p-1 mt-1 text-sm text-black border rounded-md">
                        </th>
                        <th class="py-3 px-6 text-left !font-normal">Gambar</th>
                        <th class="py-3 px-4 !font-normal text-center">
                            Barang
                            <a href="{{ request()->fullUrlWithQuery(['sort_field' => 'name', 'sort_direction' => ($sortField === 'name' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}">
                                @if($sortField === 'name')
                                    {{ $sortDirection === 'asc' ? '🔼' : '🔽' }}
                                @else
                                    🔼🔽
                                @endif
                            </a>
                            <input type="text" name="name" value="{{ request('name') }}" onkeydown="if(event.key === 'Enter') event.preventDefault();" onchange="this.form.submit()" class="p-1 mt-1 text-sm text-black border rounded-md">
                        </th>
                        <th class="py-3 px-4 !font-normal text-center">
                            Berat
                            <a href="{{ request()->fullUrlWithQuery(['sort_field' => 'size', 'sort_direction' => ($sortField === 'size' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}">
                                @if($sortField === 'size')
                                    {{ $sortDirection === 'asc' ? '🔼' : '🔽' }}
                                @else
                                    🔼🔽
                                @endif
                            </a>
                            <input type="number" name="size" value="{{ request('size') }}" onkeydown="if(event.key === 'Enter') event.preventDefault();" onchange="this.form.submit()" class="p-1 mt-1 text-sm text-black border rounded-md">
                        </th>
                        <th class="py-3 px-4 !font-normal text-center">
                            Kadar
                           <a href="{{ request()->fullUrlWithQuery(['sort_field' => 'rate', 'sort_direction' => ($sortField === 'rate' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}">
                                @if($sortField === 'rate')
                                    {{ $sortDirection === 'asc' ? '🔼' : '🔽' }}
                                @else
                                    🔼🔽
                                @endif
                            </a>
                            <input type="number" name="rate" value="{{ request('rate') }}" onkeydown="if(event.key === 'Enter') event.preventDefault();" onchange="this.form.submit()" class="p-1 mt-1 text-sm text-black border rounded-md">
                        </th>
                        <th class="py-3 px-4 !font-normal text-center">
                            Kategori
                            <a href="{{ request()->fullUrlWithQuery(['sort_field' => 'type_id', 'sort_direction' => ($sortField === 'type_id' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}">
                                @if($sortField === 'type_id')
                                    {{ $sortDirection === 'asc' ? '🔼' : '🔽' }}
                                @else
                                    🔼🔽
                                @endif
                            </a>
                            <input type="text" name="goods_type" value="{{ request('goods_type') }}" onkeydown="if(event.key === 'Enter') event.preventDefault();" onchange="this.form.submit()" class="p-1 mt-1 text-sm text-black border rounded-md">
                        </th>
                        <th class="py-3 px-4 !font-normal text-center">
                            Harga
                            <a href="{{ request()->fullUrlWithQuery(['sort_field' => 'ask_price', 'sort_direction' => ($sortField === 'ask_price' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}">
                                @if($sortField === 'ask_price')
                                    {{ $sortDirection === 'asc' ? '🔼' : '🔽' }}
                                @else
                                    🔼🔽
                                @endif
                            </a>
                            <input type="number" name="ask_price" value="{{ request('ask_price') }}" onkeydown="if(event.key === 'Enter') event.preventDefault();" onchange="this.form.submit()" class="p-1 mt-1 text-sm text-black border rounded-md">
                        </th>
                        <th class="py-3 px-6 text-center !font-normal"></th>
                    </tr>
                </thead>
                <tbody class="text-sm font-light text-gray-600">

                    @foreach ($goodShowcases as $goodShowcase)

                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="px-6 py-3 text-left">
                                <input type="checkbox" class="select-row" x-model="selectedItems" value="{{ $goodShowcase->id }}">
                            </td>
                            <td class="px-6 py-3 text-left">{{ $goodShowcase->code }}</td>
                            <td class="px-6 py-3 text-left">
                                {{ \Carbon\Carbon::parse($goodShowcase->date_entry)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-3 text-left">
                                <button type="button" aria-haspopup="dialog" aria-expanded="false"
                                    aria-controls="hs-image-goods-modal-{{ $goodShowcase->id }}"
                                    data-hs-overlay="#hs-image-goods-modal-{{ $goodShowcase->id }}">
                                    <img src="{{ asset('storage/' . $goodShowcase->image) }}" class="rounded-full size-10" class="lazyload"
                                        alt="{{ $goodShowcase->name }}">
                                </button>
                                {{-- modal image sale --}}
                                @include('components.modal.image-goods')
                            </td>
                            <td class="px-6 py-3 text-left truncate max-w-20">
                                {{ $goodShowcase->name }} - {{ $goodShowcase->merk->name }}
                            </td>
                            <td class="px-6 py-3 text-left">
                                {{ $goodShowcase->size }} gr
                            </td>
                            <td class="px-6 py-3 text-left">
                                <span
                                    class="bg-[#FFF6ED] text-[#C4320A] leading-6 rounded-xl px-2">{{ $goodShowcase->rate }}%</span>
                            </td>
                            <td class="px-6 py-3 text-left truncate max-w-20">{{ $goodShowcase->goodsType->name }}</td>
                            <td class="flex flex-col px-6 py-3 text-left">
                                <span><i class="ph ph-arrow-line-up-right text-[#027A48]"></i> Jual
                                    {{ 'Rp.' . number_format($goodShowcase->ask_price, 0, ',', '.') }}
                                    <span
                                        class="bg-[#ECFDF3] text-[#027A48] text-xs leading-6 rounded-xl px-2">{{ $goodShowcase->ask_rate }}%
                                    </span>
                                </span>
                                <span><i class="ph ph-arrow-line-down-right text-[#C4320A]"></i> Bawah
                                    {{ 'Rp.' . number_format($goodShowcase->bid_price, 0, ',', '.') }}
                                    <span
                                        class="bg-[#FFF6ED] text-[#C4320A] text-xs leading-6 rounded-xl px-2">{{ $goodShowcase->bid_rate }}%
                                    </span>
                                </span>
                                <span class="font-bold">Harga Hari ini</span>
                                
                                @php
                                    $ask_rate = $goodShowcase->ask_rate / 100;
                                    $bid_rate = $goodShowcase->bid_rate / 100;
                                    $ask_price_today = $ask_rate * $goodShowcase->size * $goldRate;
                                    $bid_price_today = $bid_rate * $goodShowcase->size * $goldRate;
                                @endphp

                                <span><i class="ph ph-arrow-line-up-right text-[#027A48]"></i> Jual
                                    {{ 'Rp.' . number_format($ask_price_today, 0, ',', '.') }}
                                    <span
                                        class="bg-[#ECFDF3] text-[#027A48] text-xs leading-6 rounded-xl px-2">{{ $goodShowcase->ask_rate }}%
                                    </span>
                                </span>
                                <span><i class="ph ph-arrow-line-down-right text-[#C4320A]"></i> Bawah
                                    {{ 'Rp.' . number_format($bid_price_today, 0, ',', '.') }}
                                    <span
                                        class="bg-[#FFF6ED] text-[#C4320A] text-xs leading-6 rounded-xl px-2">{{ $goodShowcase->bid_rate }}%
                                    </span>
                                </span>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <div class="hs-dropdown [--placement:bottom-right] relative inline-flex">
                                    <button id="hs-dropright" type="button"
                                        class="px-3 py-1 text-[#464646] bg-[#F9F9F9] rounded-lg boreder-s border border-[#DCDCDC]"
                                        aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                        <i class="ph ph-dots-three-outline-vertical"></i> Opsi
                                    </button> 
                                    <div class="hs-dropdown-menu w-48 transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden z-10 bg-white shadow-md rounded-xl p-3"
                                        role="menu" aria-orientation="vertical" aria-labelledby="hs-dropright">
                                        <a class="flex items-center gap-x-3.5 py-2 rounded-lg text-sm text-[#344054] focus:outline-none focus:bg-gray-100"
                                            href="{{ route('goods-showcase.printBarcode', ['id' => $goodShowcase->id]) }}">
                                            <i class="ph ph-barcode"></i>
                                            Cetak Barcode
                                        </a>
                                        @role('superadmin')
                                        <button type="button"
                                            class="flex items-center gap-x-3.5 py-2 rounded-lg text-sm text-[#344054] focus:outline-none focus:bg-gray-100 "
                                            aria-haspopup="dialog" aria-expanded="false"
                                            aria-controls="hs-move-to-safe-modal-{{ $goodShowcase->id }}"
                                            data-hs-overlay="#hs-move-to-safe-modal-{{ $goodShowcase->id }}">
                                            <i class="ph ph-vault"></i>
                                            Pindahkan Ke Brankas
                                        </button>
                                        <button type="button" class="flex items-center gap-x-3.5 py-2 rounded-lg text-sm text-[#344054] focus:outline-none focus:bg-gray-100 w-full"
                                            aria-haspopup="dialog" aria-expanded="false"
                                            aria-controls="hs-edit-modal-{{ $goodShowcase->id }}"
                                            data-hs-overlay="#hs-edit-modal-{{ $goodShowcase->id }}">
                                            <i class="ph ph-pencil-line"></i>
                                            Edit
                                        </button>
                                        <button type="button"
                                            class="flex items-center gap-x-3.5 py-2 rounded-lg text-sm text-[#344054]  w-full focus:outline-none focus:bg-gray-100"
                                            aria-haspopup="dialog" aria-expanded="false"
                                            aria-controls="hs-delete-modal-{{ $goodShowcase->id }}"
                                            data-hs-overlay="#hs-delete-modal-{{ $goodShowcase->id }}">
                                            <i class="ph ph-trash"></i>
                                            Hapus
                                        </button>
                                        @endrole
                                    </div>
                                </div>
                            </td>

                        </tr>

                    {{-- modal pindah ke brankas --}}
                    @include('components.modal.goods-showcase.modal-move-to-safe')
                    {{-- modal edit --}}
                    @include('components.modal.goods-showcase.edit')
                    {{-- modal hapus --}}
                    @include('components.modal.goods-showcase.modal-delete')
                    @endforeach
                </tbody>
            </table>

        </div>
        <div
            class="flex flex-wrap gap-4 items-center justify-between p-4 mb-16 text-sm leading-5 text-[#282833] bg-white rounded-b-lg border-b border-r border-l border-gray-200">
                    <div>Menampilkan {{ $goodShowcases->count() }} Data Etalase</div>
                    <div class="flex items-center justify-between">
                        <span class="mr-2">Baris diper halaman</span> 
                        
                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full px-6 py-2.5" name="paginate" onchange="this.form.submit()">
                                <option value="10" {{ $paginate == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ $paginate == 20 ? 'selected' : '' }}>20</option>
                                <option value="30" {{ $paginate == 30 ? 'selected' : '' }}>30</option>
                                <option value="40" {{ $paginate == 40 ? 'selected' : '' }}>40</option>
                                <option value="50" {{ $paginate == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        
                    </div>
                    <div class="flex items-center justify-between">
                        
                        <span>{{ $goodShowcases->appends(request()->all())->links() }}</span>
                    </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="p-4 bg-white border rounded-lg">
                <p class="text-sm text-neutral-500">Total Barang</p>
                <p class="text-3xl">{{$totalItemsInShowcase}}</p>
            </div>
            <div class="p-4 bg-white border rounded-lg">
                <p class="text-sm text-neutral-500">Total Berat Keseluruhan Barang</p>
                <p class="text-3xl">{{$totalWeightInShowcase}}</p>
            </div>
            <div class="p-4 bg-white border rounded-lg">
                @foreach ($cardGoodsSummary as $summary)
                <p class="mb-2">
                    Kadar <b>{{ $summary->rate }}%</b> : Total Berat <b>{{ number_format($summary->total_weight, 2) }}gr</b>, Total Barang <b>{{ $summary->total_items }}pcs</b>
                </p>
                @endforeach
            </div>
        </div>
    </form>
    </div>
</x-layout>
@include('components.modal.error-form-modal')
@include('components.modal.goods-showcase.add')
@include('components.modal.goods-showcase.success-modal')
@include('components.modal.master-trays.success-modal')
@include('components.modal.goods-showcase.error-modal')

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('imageUploader', () => ({
            imageUrl: '',
            dragging: false,

            openCamera() {
                this.$refs.cameraInput.click();
            },

            openGallery() {
                this.$refs.galleryInput.click();
            },

            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    this.readFile(file);
                }
            },

            readFile(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageUrl = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('Silakan pilih file gambar yang valid.');
                }
            }
        }));
    });
</script>

<script>
    function multiDelete() {
    return {
        selectedItems: [],
        toggleAll() {
            let checkboxes = document.querySelectorAll('.select-row');
            if (this.selectedItems.length === checkboxes.length) {
                this.selectedItems = [];
            } else {
                this.selectedItems = [...checkboxes].map(cb => cb.value);
            }
        },
        deleteSelected() {
            if (this.selectedItems.length === 0) {
                alert('Pilih setidaknya satu item untuk dihapus.');
                return;
            }
            if (confirm('Apakah Anda yakin ingin menghapus item yang dipilih?')) {
                fetch('{{ route('goodShowcases.deleteMultiple') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ ids: this.selectedItems })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Ambil URL saat ini dan hapus parameter halaman (jika ada)
                        let url = new URL(window.location.href);
                        url.searchParams.delete('page'); // Hapus param page agar tetap di halaman pertama setelah delete
                        window.location.href = url.toString(); // Refresh dengan mempertahankan filter pencarian
                    } else {
                        alert('Gagal menghapus item.');
                    }
                });
            }
        }
    };
}

</script>