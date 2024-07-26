<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Showcase;

class EtalaseController extends Controller
{
    public function index()
    {
        $etalases = Showcase::all();
        $title = 'Etalase';
        return view('pages.master-showcases', compact('etalases', 'title'));
    }
}

