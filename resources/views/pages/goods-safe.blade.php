@section('title', 'Brankas')
@include('components.datatables')
<x-layout>
    <x-header title="Brankas">
    </x-header>
    <div class="container py-4 mx-auto">
        <div class="mb-4">
            <input type="text" id="searchEtalase"
                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-[#79799B]"
                placeholder="Cari di etalase">
        </div>
        <div class="overflow-hidden shadow-lg rounded-xl">
            <table id="etalaseTable" class="min-w-full bg-white border border-gray-200 rounded">
                <thead>
                    <tr class="w-full bg-[#79799B] text-white  text-sm leading-normal">
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th class="py-3 px-6 text-left !font-normal">No</th>
                        <th class="py-3 px-6 text-left !font-normal">Tanggal Masuk</th>
                        <th class="py-3 px-6 text-left !font-normal">Barang & Merek</th>
                        <th class="py-3 px-6 text-left !font-normal">Berat & Kadar</th>
                        <th class="py-3 px-6 text-left !font-normal">Kategori</th>
                        <th class="py-3 px-6 text-left !font-normal">Harga Jual & Nilai Tukar</th>
                        <th class="py-3 px-6 text-center !font-normal"></th>
                    </tr>
                </thead>
                <tbody class="text-sm font-light text-gray-600">
                    @foreach ($goodsafes as $goodsafe)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-6 py-3 text-left">
                            <input type="checkbox" class="select-row">
                        </td>
                        <td class="px-6 py-3 text-left">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3 text-left">
                            {{ \Carbon\Carbon::parse($goodsafe->created_at)->translatedFormat('d F Y') }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ $goodsafe->name }} - {{ $goodsafe->merk->name }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            {{ $goodsafe->size }} <span
                                class="bg-[#FFF6ED] text-[#C4320A] text-xs leading-6 rounded-xl px-2">{{ $goodsafe->rate }}%</span>
                        </td>
                        <td class="px-6 py-3 text-left">{{ $goodsafe->goodsType->name }}</td>
                        <td class="flex flex-col px-6 py-3 text-left">
                            <span><i class="ph ph-arrow-line-up-right text-[#027A48]"></i> Jual
                                {{ 'Rp.' . number_format($goodsafe->ask_price, 0, ',', '.') }}
                                <span
                                    class="bg-[#ECFDF3] text-[#027A48] text-xs leading-6 rounded-xl px-2">{{ $goodsafe->ask_rate }}%
                                </span>
                            </span>
                            <span><i class="ph ph-arrow-line-down-right text-[#C4320A]"></i> Bawah
                                {{ 'Rp.' . number_format($goodsafe->bid_price, 0, ',', '.') }}
                                <span
                                    class="bg-[#FFF6ED] text-[#C4320A] text-xs leading-6 rounded-xl px-2">{{ $goodsafe->bid_rate }}%
                                </span>
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <button class="px-3 py-1 text-[#464646] bg-[#F9F9F9] rounded border border-[#DCDCDC]">
                                <i class="ph ph-dots-three-vertical"></i></i> Opsi</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between mt-4 mb-16">
            <div id="dataTableInfo" class="text-gray-600"></div>
            <div id="dataTableLength" class="flex items-center"></div>
            <div id="dataTableInfoEntry" class="text-gray-600"></div>
            <div id="dataTablePagination" class="flex items-center"></div>
        </div>
    </div>
</x-layout>
