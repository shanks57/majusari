<?php

namespace App\Exports;

use App\Models\Goods;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GoodsShowcaseExportApi implements FromCollection, WithStyles, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Goods::where('availability', 1)
            ->where('safe_status', 0)
            ->get()
            ->map(function ($goods) {
                return [
                    'code' => $goods->code,
                    'name' => $goods->name,
                    'category' => $goods->category,
                    'unit' => $goods->unit,
                    'type' => $goods->goodsType->name,
                    'color' => $goods->color,
                    'rate' => number_format($goods->rate, 0) . '%', 
                    'dimensions' => $goods->dimensions,
                    'size' => number_format($goods->size, 2) . 'gr',
                    'merk' => $goods->merk->name,
                    'ask_price' => $goods->ask_price,
                    'ask_rate' => number_format($goods->ask_rate, 0) . '%',
                    'bid_price' => $goods->bid_price,
                    'bid_rate' => number_format($goods->bid_rate, 0) . '%',
                    'showcase' => $goods->tray->showcase->code,
                    'tray' => $goods->tray->code,
                    'position' => $goods->position,
                    'date_entry' => Carbon::parse($goods->date_entry)->format('d/m/Y'),
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
            'Kode barang Etalase',
            'Nama Kode Etalase',
            'Kategori',
            'Satuan',
            'Jenis',
            'Warna',
            'Kadar',
            'Size',
            'Berat',
            'Merk',
            'Harga Jual',
            'Nilai Tukar Atas',
            'Harga Bawah',
            'Nilai Tukar Bawah',
            'Etalase',
            'Baki',
            'Posisi',
            'Tanggal Masuk',
        ];
    }

   public function styles(Worksheet $sheet)
    {
        // Format kolom K (Harga Jual) sebagai IDR
        $sheet->getStyle('K')->getNumberFormat()->setFormatCode('Rp #,##0'); // Format untuk Harga Jual
        // Format kolom M (Harga Bawah) sebagai IDR
        $sheet->getStyle('M')->getNumberFormat()->setFormatCode('Rp #,##0'); // Format untuk Harga Bawah

        return [];
    }

}