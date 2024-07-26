<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        $sales = TransactionDetail::orderBy('created_at', 'desc')
            ->get();
        $title = 'Penjualan';
        return view('pages.sales', compact('sales', 'title'));
    }
}
