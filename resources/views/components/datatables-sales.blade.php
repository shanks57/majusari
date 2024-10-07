@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<style>
    .dataTables_filter,
    .dataTables_info {
        display: none;
    }

    #etalaseTable_wrapper .dataTables_paginate {
        display: none;
    }

</style>
@endpush

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        var table = $('#etalaseTable').DataTable({
            "order": [],
            "ordering": false,
            pagingType: 'simple',
            "lengthMenu": [50, 100, 200, 300, 500],
            "language": {
                "paginate": {
                    "previous": '<i class="w-4 h-4 ph-bold ph-caret-left"></i>',
                    "next": '<i class="w-4 h-4 ph-bold ph-caret-right"></i>'
                },
                "lengthMenu": "Baris per halaman _MENU_",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "infoEmpty": "Tidak ada data yang tersedia",
                "info": "",
                "infoFiltered": "",
            },
            "infoCallback": function (settings, start, end, max, total, pre) {
                // Hitung total entri tanpa baris yang memiliki data-ignore="true"
                var totalRows = $('#etalaseTable tbody tr').not('[data-ignore="true"]').length; 
                
                var adjustedStart = Math.max(start, 1); // Untuk menyesuaikan agar start tidak kurang dari 1
                var adjustedEnd = Math.min(end, totalRows); // Mengambil nilai end yang disesuaikan
                var dataRangeInfo = adjustedStart + ' - ' + adjustedEnd + ' dari ' + totalRows + ' data'; // Menggunakan totalRows yang dihitung manual

                // Menampilkan jumlah data yang sedang ditampilkan dan total data (tanpa data-ignore)
                $('#dataTableInfo').html('Menampilkan ' + adjustedStart + ' - ' + adjustedEnd + ' Data ' + '{{ $title }}' + ' dari ' + totalRows + ' data');
                $('#dataTableInfoEntry').html(dataRangeInfo);

                return ''; // Jika tidak ingin mengembalikan string bawaan dari DataTables
            },

            "drawCallback": function (settings) {
                $('#dataTableLength').append($('.dataTables_length'));
                $('#dataTablePagination').append($('.dataTables_paginate'));
                $('.dataTables_paginate').addClass('flex items-center');
                $('.dataTables_paginate .paginate_button').addClass(
                    'border rounded px-2 py-1 mx-1');
                $('.dataTables_paginate .paginate_button.previous, .dataTables_paginate .paginate_button.next')
                    .addClass('border-0');
                $('.dataTables_paginate .paginate_button.current').addClass('bg-gray-200');
            },
            "initComplete": function (settings, json) {
                // Tambahkan class Tailwind ke dropdown
                $('.dataTables_length select').addClass(
                    'py-2 px-2.5 pe-6 border-gray-200 rounded-lg text-xs disabled:opacity-50 disabled:pointer-events-none '
                );
            }
        });

        $('#dataTablePagination').prepend($('.dataTables_info').addClass('mr-4 flex items-center'));

        table.on('draw.dt', function () {
            window.HSStaticMethods.autoInit();
        });

            $('#searchEtalase').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Handle click on "Select all" control
            $('#select-all').on('click', function () {
                // Check/uncheck all checkboxes in the table
                var rows = table.rows({
                    'search': 'applied'
                }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

        // Expand and collapse detail rows
        $('#etalaseTable tbody').on('click', '.main-row', function () {
            var saleId = $(this).data('sale-id');
            var detailRows = $(this).nextUntil('.main-row');

            detailRows.toggleClass('hidden');
        });
    });
</script>
@endpush