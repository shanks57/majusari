<?php

namespace App\Http\Controllers;

use App\Models\Tray;
use Illuminate\Http\Request;

class GoodTrayController extends Controller
{
    public function index()
    {
        $goodtrays = Tray::all();
        return view('pages.goods-trays', compact('goodtrays'));
    }

    public function find($id)
    {
        $tray = Tray::find($id);
        if (!$tray) {
            abort(404);
        }

        $goods = Tray::with('goods')->get();

        return view('pages.trays-show', compact('tray', 'goods'));
    }
}
