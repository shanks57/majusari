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

    protected $sales;

    // Constructor menerima parameter data sales
    public function __construct($sales)
    {
        $this->sales = $sales;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
        public function collection()
    {
        return $this->sales->map(function ($sale) {
                return [
                    'nota' => $sale->nota,
                    'name' => $sale->goods->name,
                    'category' => $sale->goods->category,
                    'unit' => $sale->goods->unit,
                    'type' => $sale->goods->goodsType->name,
                    'color' => $sale->goods->color,
                    'rate' => number_format($sale->goods->rate, 0) . '%', 
                    'size' => number_format($sale->goods->size, 2) . 'gr',
                    'merk' => $sale->goods->merk->name,
                    'ask_price' => $sale->goods->ask_price,
                    'ask_rate' => number_format($sale->goods->ask_rate, 0) . '%',
                    'bid_price' => $sale->goods->bid_price,
                    'bid_rate' => number_format($sale->goods->bid_rate, 0) . '%',
                    'harga_jual' => $sale->harga_jual,
                    'date' => Carbon::parse($sale->transaction->date)->format('d/m/Y'),
                ];
            });
    }

    /**
     * Menambahkan heading pada file Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nota Penjualan',
            'Nama Barang',
            'Kategori',
            'Satuan',
            'Jenis',
            'Warna',
            'Kadar',
            'Berat',
            'Merk',
            'Harga Jual',
            'Nilai Tukar Atas',
            'Harga Bawah',
            'Nilai Tukar Bawah',
            'Uang Masuk',
            'Tanggal Masuk',
        ];
    }

   public function styles(Worksheet $sheet)
    {
        // Format kolom K (Harga Jual) sebagai IDR
        $sheet->getStyle('K')->getNumberFormat()->setFormatCode('Rp #,##0'); // Format untuk Harga Jual
        // Format kolom M (Harga Bawah) sebagai IDR
        $sheet->getStyle('M')->getNumberFormat()->setFormatCode('Rp #,##0'); // Format untuk Harga Bawah
        $sheet->getStyle('O')->getNumberFormat()->setFormatCode('Rp #,##0'); // Format untuk Harga Bawah

        return [];
    }
}
