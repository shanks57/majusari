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
        $tray = Tray::find($id);
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

        // Ambil barang yang memenuhi kriteria
        $goods = Goods::where('tray_id', $id)
                    ->where('availability', 1)
                    ->where('safe_status', 0)
                    ->get();

        // Jumlahkan berat barang
        $totalWeight = $goods->sum('size');

        return view('pages.trays-show', compact('tray', 'goods', 'countGoods', 'totalWeight'));
    }
}
