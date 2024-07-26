@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
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
                pagingType: 'simple',
                "lengthMenu": [10, 25, 50, 75, 100],
                "language": {
                    "paginate": {
                        "previous": '<i class="ph-bold ph-caret-left w-4 h-4"></i>',
                        "next": '<i class="ph-bold ph-caret-right w-4 h-4"></i>'
                    },
                    "lengthMenu": "Baris per halaman _MENU_",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "info": "",
                    "infoFiltered": "",
                },
                "infoCallback": function( settings, start, end, max, total, pre ) {
                    // Menampilkan halaman _PAGE_ dari _PAGES_
                    var pageInfo = 'Menampilkan ' + end + ' Data ' + '{{ $title }}';

                    // Menampilkan _START_ hingga _END_ dari _TOTAL_ data
                    var dataRangeInfo = start + ' - ' + end + ' dari ' + total + ' entri';
                    
                    $('#dataTableInfo').html(pageInfo);
                    $('#dataTableInfoEntry').html(dataRangeInfo);

                    return '';
                },
                "drawCallback": function (settings) {
                    $('#dataTableLength').append($('.dataTables_length'));
                    $('#dataTablePagination').append($('.dataTables_paginate'));
                    $('.dataTables_paginate').addClass('flex items-center');
                    $('.dataTables_paginate .paginate_button').addClass('border rounded px-2 py-1 mx-1');
                    $('.dataTables_paginate .paginate_button.previous, .dataTables_paginate .paginate_button.next').addClass('border-0');
                    $('.dataTables_paginate .paginate_button.current').addClass('bg-gray-200');
                },
                "initComplete": function(settings, json) {
                    // Tambahkan class Tailwind ke dropdown
                    $('.dataTables_length select').addClass('form-select p-1 text-sm rounded border border-[#EBEBEF] ');
                }
            });

            $('#dataTablePagination').prepend($('.dataTables_info').addClass('mr-4'));

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

            // Handle click on individual checkbox to set "Select all" control if all checkboxes are checked
            $('#etalaseTable tbody').on('change', 'input[type="checkbox"]', function () {
                // If all checkboxes are checked
                if ($('input[type="checkbox"]:not(:checked)').length == 0) {
                    $('#select-all').prop('checked', true);
                } else {
                    $('#select-all').prop('checked', false);
                }
            });
        });
    </script>
@endpush