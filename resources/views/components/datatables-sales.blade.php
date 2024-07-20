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
            "columnDefs": [{
                "orderable": false,
                "targets": [0, 2]
            }],
            pagingType: 'simple',
            "lengthMenu": [10, 25, 50, 75, 100],
            "language": {
                "paginate": {
                    "previous": '<i class="ph-bold ph-caret-left"></i>',
                    "next": '<i class="ph-bold ph-caret-right"></i>'
                },
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "infoEmpty": "Tidak ada data yang tersedia",
                "info": "",
                "infoFiltered": "",
            },
            "infoCallback": function (settings, start, end, max, total, pre) {
                var totalRows = $('#etalaseTable tbody tr').not('[data-ignore="true"]').length;
                var adjustedStart = Math.max(start, 1);
                var adjustedEnd = Math.min(end, totalRows);
                var dataRangeInfo = adjustedStart + ' - ' + adjustedEnd + ' dari ' + totalRows +
                    ' data';
                $('#dataTableInfo').html('Menampilkan halaman ' + (Math.floor(settings
                        ._iDisplayStart / settings._iDisplayLength) + 1) + ' dari ' + Math
                    .ceil(totalRows / settings._iDisplayLength));
                $('#dataTableInfoEntry').html(dataRangeInfo);
                return '';
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
            }
        });

        $('#dataTablePagination').prepend($('.dataTables_info').addClass('mr-4'));

        $('#searchEtalase').on('keyup', function () {
            table.search(this.value).draw();
        });

        $('#select-all').on('click', function () {
            var rows = table.rows({
                'search': 'applied'
            }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        $('#etalaseTable tbody').on('change', 'input[type="checkbox"]', function () {
            if ($('input[type="checkbox"]:not(:checked)').length == 0) {
                $('#select-all').prop('checked', true);
            } else {
                $('#select-all').prop('checked', false);
            }
        });
    });
</script>
@endpush