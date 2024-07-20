@section('title', 'Pelanggan')
@include('components.datatables')

<x-layout>
    <x-header title="Pelanggan">
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
                        <th class="py-3 px-6 text-left !font-normal">Nama Pelanggan</th>
                        <th class="py-3 px-6 text-left !font-normal">No Telp</th>
                        <th class="py-3 px-6 text-left !font-normal">Alamat</th>

                        <th class="py-3 px-6 text-center !font-normal"></th>
                    </tr>
                </thead>
                <tbody class="text-sm font-light text-gray-600">
                    @foreach ($customers as $customer)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-6 py-3 text-left">
                            <input type="checkbox" class="select-row">
                        </td>
                        <td class="px-6 py-3 text-left">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3 text-left">{{ $customer->name }}</td>
                        <td class="px-6 py-3 text-left">{{ $customer->phone }}</td>
                        <td class="px-6 py-3 text-left">{{ $customer->address }}</td>

                        <td class="px-6 py-3 text-center">
                           
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between mt-4 mb-16 px-">
            <div id="dataTableInfo" class="text-gray-600"></div>
            <div class="flex items-center space-x-8">
                <div id="dataTableLength" class="flex items-center"></div>
                <div id="dataTableInfoEntry" class="text-gray-600"></div>
                <div id="dataTablePagination" class="flex items-center px-4"></div>
            </div>
        </div>
    </div>
</x-layout>
