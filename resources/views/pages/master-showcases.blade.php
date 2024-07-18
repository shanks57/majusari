<x-layout>
    <x-header title="Etalase">
    </x-header>

    <div class="container mx-auto py-4">
        <div class="mb-4">
            <input type="text" id="searchEtalase" class="border border-gray-300 rounded p-2 w-full"
                placeholder="Cari di etalase">
        </div>
        <div class="overflow-hidden rounded-xl shadow-lg">
            <table id="etalaseTable" class="min-w-full bg-white border border-gray-200 rounded">
                <thead>
                    <tr class="w-full bg-[#79799B] text-white  text-sm leading-normal">
                        <th class="py-3 px-6 text-left">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th class="py-3 px-6 text-left !font-normal">No</th>
                        <th class="py-3 px-6 text-left !font-normal">Kode Etalase</th>
                        <th class="py-3 px-6 text-left !font-normal">Nama Etalase</th>
                        <th class="py-3 px-6 text-left !font-normal">Jenis Barang</th>
                        <th class="py-3 px-6 text-left !font-normal">Baki</th>
                        <th class="py-3 px-6 text-center !font-normal"></th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($etalases as $etalase)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left">
                                <input type="checkbox" class="select-row">
                            </td>
                            <td class="py-3 px-6 text-left">{{ $loop->iteration }}</td>
                            <td class="py-3 px-6 text-left">{{ $etalase->code }}</td>
                            <td class="py-3 px-6 text-left">{{ $etalase->name }}</td>
                            <td class="py-3 px-6 text-left">{{ $etalase->jenis }}</td>
                            <td class="py-3 px-6 text-left">{{ $etalase->baki }}</td>
                            <td class="py-3 px-6 text-center">
                                <button class="bg-orange-500 text-white px-3 py-1 rounded">
                                    <i class="ph ph-folder-open"></i>
                                    Tambah Baki</button>
                                <button class="bg-purple-500 text-white px-3 py-1 rounded">
                                    <i class="ph ph-folder-plus"></i>Kelola Baki</button>
                                <button class="bg-red-500 text-white px-3 py-1 rounded">
                                    <i class="ph ph-trash"></i>Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex justify-between items-center">
            <div id="dataTableInfo" class="text-gray-600"></div>
            <div id="dataTableLength" class="flex items-center"></div>
            <div id="dataTablePagination" class="flex items-center"></div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#etalaseTable').DataTable({
                "dom": "<'top'>rt<'bottom'lip><'clear'>",
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ entri per halaman",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total entri)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    },
                }
                "drawCallback": function(settings) {
                    $('#dataTableLength').empty().append($('.dataTables_length'));
                    $('#dataTableInfo').empty().append($('.dataTables_info'));
                    $('#dataTablePagination').empty().append($('.dataTables_paginate'));
                }
            });

            $('#searchEtalase').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Handle click on "Select all" control
            $('#select-all').on('click', function() {
                // Check/uncheck all checkboxes in the table
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            // Handle click on individual checkbox to set "Select all" control if all checkboxes are checked
            $('#etalaseTable tbody').on('change', 'input[type="checkbox"]', function() {
                // If all checkboxes are checked
                if ($('input[type="checkbox"]:not(:checked)').length == 0) {
                    $('#select-all').prop('checked', true);
                } else {
                    $('#select-all').prop('checked', false);
                }
            });
        });
    </script>
</x-layout>
