<?php

namespace App\Exports;

use App\Models\Goods;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GoodsShowcaseExport implements FromCollection, WithStyles, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Goods::where('availability', 1)->where('safe_status', 0);

        // Terapkan filter berdasarkan input yang diterima
        if (!empty($this->filters['code'])) {
            $query->where('code', 'LIKE', '%' . $this->filters['code'] . '%');
        }
        if (!empty($this->filters['name'])) {
            $query->where('name', 'LIKE', '%' . $this->filters['name'] . '%');
        }
        if (!empty($this->filters['date_entry'])) {
            $query->where('date_entry', 'LIKE', '%' . $this->filters['date_entry'] . '%');
        }
        if (!empty($this->filters['goods_type'])) {
            $query->whereHas('goodsType', function ($q) {
                $q->where('name', 'LIKE', '%' . $this->filters['goods_type'] . '%');
            });
        }

        return $query->get()->map(function ($goods) {
            return [
                'code' => $goods->code,
                'name' => $goods->name,
                'category' => $goods->category,
                'unit' => $goods->unit,
                'type' => $goods->goodsType->name ?? null,
                'color' => $goods->color,
                'rate' => number_format($goods->rate, 0) . '%',
                'dimensions' => $goods->dimensions,
                'size' => number_format($goods->size, 2) . 'gr',
                'merk' => $goods->merk->name ?? null,
                'ask_price' => $goods->ask_price,
                'ask_rate' => number_format($goods->ask_rate, 0) . '%',
                'bid_price' => $goods->bid_price,
                'bid_rate' => number_format($goods->bid_rate, 0) . '%',
                'showcase' => $goods->tray->showcase->code ?? null,
                'tray' => $goods->tray->code ?? null,
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
