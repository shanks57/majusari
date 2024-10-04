@section('title', 'Penjualan')
@include('components.datatables-sales')
<x-layout>
    <x-header title="Penjualan" subtitle="Data Penjualan">
        <x-slot name="secondary">
            <x-button-add url="{{ route('goods-types.index') }}" bgColor="bg-white" textColor="text-[#606060]"
                icon="ph ph-barcode" borderButton="border" borderColor="border-[#DFDFDF]"
                dataHsOverlay='#hs-check-nota-modal'>
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

        <form action="{{ route('sales.export') }}" method="GET">
            <div class="flex items-center justify-center w-full mx-auto">
                <div class="relative">
                    <input name="date_start" type="date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        placeholder="Tanggal Mulai">
                </div>
                <span class="mx-4 text-gray-500">-></span>
                <div class="relative">
                    <input name="date_end" type="date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                        placeholder="Tanggal Berakhir">
                </div>
            </div>

            <!-- Tombol Export -->
            <div class="inline-flex justify-center w-full mx-auto mt-4" role="group">
                <button type="submit" name="format" value="pdf"
                    class="p-3 font-normal text-white bg-gray-400 rounded-s-xl hover:bg-gray-500">PDF</button>
                <button type="submit" name="format" value="excel"
                    class="px-2.5 py-3 font-normal text-white bg-gray-400 hover:bg-gray-500">Excel</button>
                <button type="submit" name="format" value="print"
                    class="p-3 font-normal text-white bg-gray-400 rounded-e-xl hover:bg-gray-500">Print</button>
            </div>
        </form>


        <div class="mt-4 overflow-hidden overflow-x-auto border border-gray-200 rounded-t-lg shadow-lg">
           
            <table id="etalaseTable" class="min-w-full bg-white border border-gray-200 display">
                <thead>
                    <tr class="w-full bg-[#79799B] text-white text-sm leading-normal">
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th class="py-3 px-6 text-left !font-normal">Nota</th>
                        <th class="py-3 px-6 text-left !font-normal">Tgl Penjualan</th>
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
                    </tr>
                    @php
                    $lastDate = $currentDate;
                    @endphp
                    @endif

                    <tr class="py-3 main-row" data-sale-id="{{ $sale->id }}">
                        <td class="px-6 py-3 text-left">
                            <input type="checkbox" class="select-row">
                        </td>
                        <td class="px-6 py-3 font-bold text-left">
                            {{ $sale->code }}
                        </td>
                        <td class="px-6 py-3 text-transparent">
                            {{ $currentDate }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ $sale->user->name }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            <div class="hs-dropdown [--placement:bottom-right] relative inline-flex">
                                <button id="hs-dropright" type="button"
                                    class="px-3 py-1 text-[#464646] bg-[#F9F9F9] rounded-lg border border-[#DCDCDC]"
                                    aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                    <i class="ph ph-dots-three-outline-vertical"></i> Opsi
                                </button>
                                <div class="hs-dropdown-menu hidden w-48 transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 z-10 bg-white shadow-md rounded-xl px-3 py-1"
                                    role="menu" aria-orientation="vertical" aria-labelledby="hs-dropright">
                                    <a class="flex items-center gap-x-3.5 py-2 rounded-lg text-sm text-[#344054] focus:outline-none focus:bg-gray-100"
                                        href="{{ route('sale.printNota', $sale->id) }}">
                                        <i class="ph ph-printer"></i>
                                        Cetak Nota
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- start expand row --}}
                    @php
                    $detailCount = $sale->details->count();
                    @endphp                   
                    @foreach($sale->details as $index => $detail)
                    <tr class="hidden detail-row" data-ignore="true">
                        <td class="px-6 py-3">
                            <div class="flex items-center">
                                <td class="px-6 py-3 text-left">
                                    <button type="button" aria-haspopup="dialog" aria-expanded="false"
                                        aria-controls="hs-image-sale-modal-{{ $detail->id }}"
                                        data-hs-overlay="#hs-image-sale-modal-{{ $detail->id }}">
                                        <img src="storage/{{ $detail->goods->image }}" class="rounded-full size-10"
                                            alt="{{ $detail->goods->name }}">
                                    </button>
                                    @include('components.modal.image-sale')
                                </td>
                                <td class="px-6 py-3 font-semibold leading-6 text-left">
                                    <span class="px-2 py-1 border boreder-[#D0D5DD] border-s rounded-full">{{ $detail->goods->code }}
                                        - {{ $detail->goods->name }}</span>
                                </td>
                                <td class="px-6 py-3 text-left">{{ $detail->goods->size }}gr 
                                    <span class="bg-[#FFF6ED] text-[#C4320A] text-xs leading-6 rounded-xl px-2">
                                        {{ $detail->goods->rate }}%
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-left">
                                    <span><i class="ph ph-arrow-line-down-right text-[#C4320A]"></i>
                                        Bawah {{ 'Rp.' . number_format($detail->harga_jual, 0, ',', '.') }} 
                                        <span class="bg-[#FFF6ED] text-[#C4320A] text-xs leading-6 rounded-xl px-2">
                                            {{ $detail->goods->bid_rate }}%
                                        </span>
                                    </span>
                                </td>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    {{-- end expand row --}}
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
@include('components.modal.sales.check-nota')
@include('components.modal.sales.nota-result')
@include('components.modal.sales.success-cart')
@include('components.modal.error-modal')
