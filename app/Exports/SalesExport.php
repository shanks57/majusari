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
            $details = $sale->details;
            return [
                'nota' => $sale->code,
                'name' => $details->pluck('goods.name')->implode(', '),
                'category' => $details->pluck('goods.category')->implode(', '),
                'unit' => $details->pluck('goods.unit')->implode(', '),
                'type' => $details->pluck('goods.goodsType.name')->implode(', '),
                'color' => $details->pluck('goods.color')->implode(', '),
                'rate' => $details->pluck('goods.rate')->map(function($rate) {
                    return number_format($rate, 0) . '%';
                })->implode(', '),
                'size' => $details->pluck('goods.size')->map(function($size) {
                    return number_format($size, 2) . 'gr';
                })->implode(', '),
                'merk' => $details->pluck('goods.merk.name')->implode(', '),
                'ask_price' => $details->pluck('goods.ask_price')->implode(', '),
                'ask_rate' => $details->pluck('goods.ask_rate')->map(function($ask_rate) {
                    return number_format($ask_rate, 0) . '%';
                })->implode(', '),
                'bid_price' => $details->pluck('goods.bid_price')->implode(', '),
                'bid_rate' => $details->pluck('goods.bid_rate')->map(function($bid_rate) {
                    return number_format($bid_rate, 0) . '%';
                })->implode(', '),
                'harga_jual' => $details->pluck('harga_jual')->implode(', '),
                'date' => Carbon::parse($sale->date)->format('d/m/Y'),
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
        $sheet->getStyle('O')->getNumberFormat()->setFormatCode('Rp #,##0'); // Format untuk Uang Masuk

        return [];
    }
}
