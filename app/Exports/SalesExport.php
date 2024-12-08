<?php

namespace App\Exports;

use App\Models\TransactionDetail;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class SalesExport implements FromCollection, WithStyles, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Nota',
            'Nama Barang',
            'Kategori',
            'Satuan',
            'Tipe',
            'Warna',
            'Rate (%)',
            'Ukuran (gr)',
            'Merk',
            'Harga Jual Awal',
            'Rate Awal (%)',
            'Harga Penawaran',
            'Rate Penawaran (%)',
            'Harga Jual',
            'Tanggal Transaksi',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Format kolom K (Harga Jual) sebagai IDR
        $sheet->getStyle('K')->getNumberFormat()->setFormatCode('Rp #,##0'); // Format untuk Harga Jual
        // Format kolom M (Harga Bawah) sebagai IDR
        $sheet->getStyle('M')->getNumberFormat()->setFormatCode('Rp #,##0'); // Format untuk Harga Bawah
        $sheet->getStyle('O')->getNumberFormat()->setFormatCode('Rp #,##0'); // Format untuk Uang Masuk

        return [];
    }
}
