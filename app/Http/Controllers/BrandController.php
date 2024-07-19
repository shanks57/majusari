<?php

namespace App\Http\Controllers;

use App\Models\Merk;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Merk::all();
        return view('pages.master-brands', compact('brands'));
    }
}
