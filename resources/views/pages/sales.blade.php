@section('title', 'Penjualan')
@include('components.datatables-sales')
<x-layout>
    <x-header title="Penjualan" subtitle="Data Penjualan">
        <x-slot name="secondary">
            <x-button-add url="{{ route('goods-types.index') }}" bgColor="bg-white" textColor="text-[#606060]"
                icon="ph ph-barcode" borderButton="border" borderColor="border-[#DFDFDF]"
                dataHsOverlay='#hs-add-modal1'>
                Cek Nota
            </x-button-add>
        </x-slot>
        <x-button-add>
            Penjualan baru
        </x-button-add>
    </x-header>

    <div class="container py-4 mx-auto">
        <div class="relative w-full mx-auto mb-4">
            <input type="text" id="searchEtalase"
                class="w-full p-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:border-[#79799B] text-sm"
                placeholder="Cari di etalase">
            <i class="ph ph-magnifying-glass absolute left-3 top-3 text-[#2D2F30]"></i>
        </div>
        <div class="overflow-hidden border border-gray-200 rounded-t-lg shadow-lg">
            <table id="etalaseTable" class="min-w-full bg-white border border-gray-200 display">
                <thead>
                    <tr class="w-full bg-[#79799B] text-white text-sm leading-normal">
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th class="py-3 px-6 text-left !font-normal">No</th>
                        <th class="py-3 px-6 text-left !font-normal">Tgl Penjualan</th>
                        <th class="py-3 px-6 text-left !font-normal">Gambar</th>
                        <th class="py-3 px-6 text-left !font-normal">ID & Nama Barang</th>
                        <th class="py-3 px-6 text-left !font-normal">Berat & Kadar</th>
                        <th class="py-3 px-6 text-left !font-normal">Harga Jual & Nilai Tukar</th>
                        <th class="py-3 px-6 text-left !font-normal">Pegawai</th>
                        <th class="py-3 px-6 text-center !font-normal"></th>
                    </tr>
                </thead>
                <tbody class="text-sm font-light text-gray-600">
                    @php
                    $lastDate = null;
                    @endphp
                    @foreach ($sales as $sale)
                    @php
                    $currentDate = \Carbon\Carbon::parse($sale->created_at)->translatedFormat('d F Y');
                    @endphp
                    @if ($currentDate !== $lastDate)
                    <tr class="py-3" data-ignore="true">
                        <td class="bg-[#F9F5FF]"></td>
                        <td class="bg-[#F9F5FF]"></td>
                        <td class="px-6 py-3 font-bold bg-[#F9F5FF]">{{ $currentDate }}</td>
                        <td class="bg-[#F9F5FF]"></td>
                        <td class="bg-[#F9F5FF]"></td>
                        <td class="bg-[#F9F5FF]"></td>
                        <td class="bg-[#F9F5FF]"></td>
                        <td class="bg-[#F9F5FF]"></td>
                        <td class="bg-[#F9F5FF]"></td>
                    </tr>
                    @php
                    $lastDate = $currentDate;
                    @endphp
                    @endif
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-6 py-3 text-left">
                            <input type="checkbox" class="select-row">
                        </td>
                        <td class="px-6 py-3 text-left">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3 text-left text-transparent">
                            {{ $currentDate }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            <button type="button" aria-haspopup="dialog" aria-expanded="false"
                                aria-controls="hs-image-sale-modal-{{ $sale->id }}"
                                data-hs-overlay="#hs-image-sale-modal-{{ $sale->id }}">
                                <img src="storage/{{ $sale->goods->image }}" class="rounded-full size-10"
                                    alt="{{ $sale->goods->name }}">
                            </button>

                            {{-- modal image sale --}}
                            @include('components.modal.image-sale')
                            
                        </td>
                        <td class="px-6 py-3 font-semibold leading-6 text-left">
                            <span
                                class="px-2 py-1 border boreder-[#D0D5DD] border-s rounded-full">{{ $sale->nota }}
                                - {{ $sale->goods->name }}</span>
                        </td>
                        <td class="px-6 py-3 text-left">{{ $sale->goods->size }}gr <span
                                class="bg-[#FFF6ED] text-[#C4320A] text-xs leading-6 rounded-xl px-2">{{ $sale->goods->rate }}%</span>
                        </td>
                        <td class="px-6 py-3 text-left"><span><i class="ph ph-arrow-line-down-right text-[#C4320A]"></i>
                                Bawah {{ 'Rp.' . number_format($sale->goods->bid_price, 0, ',', '.') }} <span
                                    class="bg-[#FFF6ED] text-[#C4320A] text-xs leading-6 rounded-xl px-2">{{ $sale->goods->bid_rate }}%</span></span>
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ $sale->transaction->user->name }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            <button
                                class="px-3 py-1 text-[#464646] bg-[#F9F9F9] rounded-lg boreder-s border border-[#DCDCDC]">
                                <i class="ph ph-dots-three-outline-vertical"></i> Opsi
                            </button>
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
</x-layout>

@include('components.modal.sales.new-sale')
@include('components.modal.sales.modal-form')
@include('components.modal.sales.success-cart')
@include('components.modal.error-modal')