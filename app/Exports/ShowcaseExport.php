<?php

namespace App\Exports;

use App\Models\Showcase;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShowcaseExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Showcase::withCount('trays')
            ->get()
            ->map(function ($showcase) {
                return [
                    'code' => $showcase->code,
                    'name' => $showcase->name,
                    'type_id' => $showcase->goodsType->name,
                    'trays_count' => $showcase->trays_count,
                    'created_at' => $showcase->created_at->format('d/m/Y'),
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
            'Kode Etalase',
            'Nama Etalase',
            'Jenis Barang',
            'Jumlah Baki',
            'Tangal'
        ];
    }
}
