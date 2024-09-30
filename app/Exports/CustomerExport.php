<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Customer::select('name', 'phone', 'address', 'created_at')
            ->get()
            ->map(function ($user) {
                    return [
                        'name' => $user->name,
                        'phone' => $user->phone,
                        'address' => $user->address,
                        'created_at' => $user->created_at->format('d/m/Y'),
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
            'Name',
            'No Telp',
            'Alamat',
            'Tangal'
        ];
    }
}
