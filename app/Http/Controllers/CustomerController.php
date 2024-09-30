<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Models\Customer;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $title = 'Pelanggan';
        return view('pages.master-customers', compact('customers', 'title'));
    }

    public function downloadPdf()
    {
        $customers = Customer::select('name', 'phone', 'address', 'created_at')->get();

        $pdf = PDF::loadView('/pdf-page/customer', compact('customers'));

        return $pdf->download(now()->format('His').'-Laporan-Data-Pelanggan.pdf');
    }

    public function exportExcel()
    {
        $fileName = now()->format('His').'-Laporan-Data-Pelanggan.xlsx';
        return Excel::download(new CustomerExport, $fileName);
    }

    public function print()
    {
        $customers = Customer::select('name', 'phone', 'address', 'created_at')->get();
                      
        return view('/print-page/print-customer', compact('customers'));
    }
}
