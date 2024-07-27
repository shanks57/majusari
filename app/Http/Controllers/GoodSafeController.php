<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Models\Showcase;
use App\Models\Tray;
use Illuminate\Http\Request;

class GoodSafeController extends Controller
{
    public function index()
    {
        $goodsafes = Goods::where('availability', 1)
            ->where('safe_status', 1)
            ->get();
            
        $title = 'Brangkas';
        $showcases = Showcase::all();
        $trays = Tray::with('goods')->get()->map(function ($tray) {
            $tray->remaining_capacity = $tray->capacity - $tray->goods->count();
            return $tray;
        });

        return view('pages.goods-safe', compact('goodsafes', 'showcases', 'trays','title'));
    }

    public function moveToShowcase($id, Request $request)
    {
        $good = Goods::find($id);

        if (!$good) {
            return redirect()->route('goods.safe')->with('error', 'Item not found');
        }

        $trayId = $request->input('tray-select');

        $tray = Tray::find($trayId);

        if (!$tray) {
            return redirect()->route('goods.safe')->with('error', 'Tray not found');
        }

        $currentGoodsCount = Goods::where('tray_id', $trayId)->count();
        $remainingCapacity = $tray->capacity - $currentGoodsCount;

        if ($remainingCapacity <= 0) {
            return redirect()->route('goods.safe')->with('error', 'Tray capacity is full');
        }

        $good->safe_status = 0;
        $good->tray_id = $trayId;
        $good->save();

        return redirect()->route('goods.safe')->with('success', 'Berhasil Memindahkan Data Etalase');
    }

    public function destroy($id)
    {
        $goodShowcase = Goods::findOrFail($id);
        $goodShowcase->delete();

        return redirect()->route('goods.safe')->with('success', 'Berhasil Menghapus Data Barang di Brangkas');
    }

}
