<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Models\Showcase;
use App\Models\Tray;
use Illuminate\Http\Request;

class GoodTrayController extends Controller
{
    public function index()
    {
        $showcases = Showcase::get();
        $goodtrays = Tray::with('showcase')->get();
        
        return view('pages.goods-trays', compact('goodtrays', 'showcases'));
    }

    public function find($id)
    {
        $tray = Tray::with('showcase')->find($id);
        if (!$tray) {
            abort(404);
        }
        
        $countGoods = Goods::where('tray_id', $id)
        ->where('availability', 1)
        ->where('safe_status', 0)
        ->count();

        $countWeight = Goods::where('tray_id', $id)
        ->where('availability', 1)
        ->where('safe_status', 0)
        ->get();

        $goods = Goods::where('tray_id', $id)
                    ->where('availability', 1)
                    ->where('safe_status', 0)
                    ->get();

        $totalWeight = $goods->sum('size');

        return view('pages.trays-show', compact('tray', 'goods', 'countGoods', 'totalWeight'));
    }
}
