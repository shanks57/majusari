<?php

namespace App\Http\Controllers;

use App\Models\GoodsType;
use Illuminate\Http\Request;

class GoodsTypeController extends Controller
{
    public function index()
    {
        $types = GoodsType::all();
        return view('pages.master-types', compact('types'));
    }
}
