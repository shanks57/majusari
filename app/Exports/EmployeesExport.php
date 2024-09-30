<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select('name', 'username', 'phone', 'debt_receipt', 'address', 'status', 'created_at')
            ->get()
            ->map(function ($user) {
                    return [
                        'name' => $user->name,
                        'username' => $user->username,
                        'phone' => $user->phone,
                        'debt_receipt' => $user->debt_receipt,
                        'address' => $user->address,
                        'status' => $user->status ? 'Aktif' : 'Tidak Aktif',
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
            'Username',
            'No Telp',
            'Bon Hutang',
            'Alamat',
            'Status',
            'Tangal'
        ];
    }
}
