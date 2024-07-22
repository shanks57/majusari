<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use Illuminate\Http\Request;

class GoodShowcaseController extends Controller
{
    public function index()
    {
        $goodShowcases = Goods::where('availability', 1)
            ->where('safe_status', 0)
            ->get();
        $title = 'Barang';
        return view('pages.goods-showcases', compact('goodShowcases', 'title'));
    }
}
