@section('title', 'Etalase')
@include('components.datatables')
<x-layout>
    <x-header title="Etalase">
        <x-button-add url="{{ route('goods-types.index') }}">
            Tambah Barang Etalase
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
                        <th class="py-3 px-6 text-left !font-normal">Tanggal Masuk</th>
                        <th class="py-3 px-6 text-left !font-normal">Gambar</th>
                        <th class="py-3 px-6 text-left !font-normal">Barang & Merek</th>
                        <th class="py-3 px-6 text-left !font-normal">Berat & Kadar</th>
                        <th class="py-3 px-6 text-left !font-normal">Kategori</th>
                        <th class="py-3 px-6 text-left !font-normal">Harga Jual & Nilai Tukar</th>
                        <th class="py-3 px-6 text-center !font-normal"></th>
                    </tr>
                </thead>
                <tbody class="text-sm font-light text-gray-600">
                    @foreach ($goodShowcases as $goodShowcase)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-6 py-3 text-left">
                            <input type="checkbox" class="select-row">
                        </td>
                        <td class="px-6 py-3 text-left">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3 text-left">
                            {{ \Carbon\Carbon::parse($goodShowcase->created_at)->translatedFormat('d F Y') }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            <button type="button" aria-haspopup="dialog" aria-expanded="false"
                                aria-controls="hs-image-goods-modal-{{ $goodShowcase->id }}"
                                data-hs-overlay="#hs-image-goods-modal-{{ $goodShowcase->id }}">
                                <img src="{{ asset('storage/' . $goodShowcase->image) }}" class="rounded-full size-10"
                                    alt="{{ $goodShowcase->name }}">
                            </button>
                            {{-- modal image sale --}}
                            @include('components.modal.image-goods')
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ $goodShowcase->name }} - {{ $goodShowcase->merk->name }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ $goodShowcase->size }} gr <span
                                class="bg-[#FFF6ED] text-[#C4320A] text-xs leading-6 rounded-xl px-2">{{ $goodShowcase->rate }}%</span>
                        </td>
                        <td class="px-6 py-3 text-left">{{ $goodShowcase->goodsType->name }}</td>
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
                                        href="{{ route('goods-showcase.printBarcode', ['id' => $goodShowcase->id]) }}" target="_blank">
                                        <i class="ph ph-barcode"></i>
                                        Cetak Barcode
                                    </a>
                                    <button type="button"
                                        class="flex items-center gap-x-3.5 py-2 rounded-lg text-sm text-[#344054] focus:outline-none focus:bg-gray-100 "
                                        aria-haspopup="dialog" aria-expanded="false"
                                        aria-controls="hs-move-to-safe-modal-{{ $goodShowcase->id }}"
                                        data-hs-overlay="#hs-move-to-safe-modal-{{ $goodShowcase->id }}">
                                        <i class="ph ph-vault"></i>
                                        Pindahkan Ke Brankas
                                    </button>
                                    <a class="flex items-center gap-x-3.5 py-2 rounded-lg text-sm text-[#344054] focus:outline-none focus:bg-gray-100 "
                                        href="#">
                                        <i class="ph ph-pencil-line"></i>
                                        Edit
                                    </a>
                                    <button type="button"
                                        class="flex items-center gap-x-3.5 py-2 rounded-lg text-sm text-[#344054]  w-full focus:outline-none focus:bg-gray-100"
                                        aria-haspopup="dialog" aria-expanded="false"
                                        aria-controls="hs-delete-modal-{{ $goodShowcase->id }}"
                                        data-hs-overlay="#hs-delete-modal-{{ $goodShowcase->id }}">
                                        <i class="ph ph-trash"></i>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    {{-- modal pindah ke brankas --}}
                    @include('components.modal.goods-showcase.modal-move-to-safe')
                    {{-- modal edit --}}
                    {{-- modal hapus --}}
                    @include('components.modal.goods-showcase.modal-delete')
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
</x-layout>

@include('components.modal.goods-showcase.success-modal')