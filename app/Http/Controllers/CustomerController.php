<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $title = 'Pelanggan';
        return view('pages.master-customers', compact('customers', 'title'));
    }
}
