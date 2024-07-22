@section('title', 'Penjualan')
@include('components.datatables-sales')
<x-layout>
    <x-header title="Penjualan">
        <x-slot name="secondary">
            <x-button-add url="{{ route('goods-types.index') }}" bgColor="bg-white" textColor="text-[#606060]"
                icon="ph ph-barcode" borderButton="border" borderColor="border-[#DFDFDF]">
                Cek Nota
            </x-button-add>
        </x-slot>
        <x-button-add url="{{ route('goods-types.index') }}">
            Tambah Barang Etalase
        </x-button-add>
    </x-header>

    <div class="container py-4 mx-auto">
        <div class="mb-4 relative w-full mx-auto">
            <input type="text" id="searchEtalase"
                class="w-full p-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:border-[#79799B]"
                placeholder="Cari di etalase">
            <i class="ph ph-magnifying-glass absolute left-3 top-3 text-[#2D2F30]"></i>
        </div>
        <div class="overflow-hidden shadow-lg rounded-t-lg border border-gray-200">
            <table id="etalaseTable" class="display min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="w-full bg-[#79799B] text-white text-sm leading-normal">
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th class="py-3 px-6 text-left !font-normal">No</th>
                        <th class="py-3 px-6 text-left !font-normal">Tgl Penjualan</th>
                        <th class="py-3 px-6 text-left !font-normal">ID & Nama Barang</th>
                        <th class="py-3 px-6 text-left !font-normal">Berat & Kadar</th>
                        <th class="py-3 px-6 text-left !font-normal">Harga Jual & Nilai Tukar</th>
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
                        <td class="px-6 py-3 text-left text-transparent">{{ $currentDate }}</td>
                        <td class="px-6 py-3 text-left">{{ $sale->transaction->code }} - {{ $sale->goods->name }}</td>
                        <td class="px-6 py-3 text-left">{{ $sale->goods->size }} <span
                                class="bg-[#FFF6ED] text-[#C4320A] text-xs leading-6 rounded-xl px-2">{{ $sale->goods->rate }}%</span>
                        </td>
                        <td class="px-6 py-3 text-left"><span><i class="ph ph-arrow-line-down-right text-[#C4320A]"></i>
                                Bawah {{ 'Rp.' . number_format($sale->goods->bid_price, 0, ',', '.') }} <span
                                    class="bg-[#FFF6ED] text-[#C4320A] text-xs leading-6 rounded-xl px-2">{{ $sale->goods->bid_rate }}%</span></span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <button class="px-3 py-1 text-[#464646] bg-[#F9F9F9] rounded border border-[#DCDCDC]">
                                <i class="ph ph-dots-three-vertical"></i> Opsi
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
