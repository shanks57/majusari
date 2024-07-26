<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use Illuminate\Http\Request;

class GoodSafeController extends Controller
{
    public function index()
    {
        $goodsafes = Goods::where('availability', 1)
            ->where('safe_status', 1)
            ->get();
        $title = 'Brangkas';
        return view('pages.goods-safe', compact('goodsafes', 'title'));
    }
}
