<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use Illuminate\Http\Request;
use Milon\Barcode\DNS2D;

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

    public function moveToSafe($id)
    {
        $good = Goods::find($id);

        if (!$good) {
            return redirect()->route('goods.showcases')->with('error', 'Item not found');
        }

        $good->safe_status = 1;
        $good->tray_id = NULL;
        $good->save();

        return redirect()->route('goods.showcase')->with('success', 'Berhasil Memindahkan Data Brankas');
    }

    public function destroy($id)
    {
        // Find the item by ID and delete it
        $goodShowcase = Goods::findOrFail($id);
        $goodShowcase->delete();

        // Redirect back with a success message
        return redirect()->route('goods.showcase')->with('success', 'Berhasil Menghapus Data Barang di Etalase');
    }

    public function printBarcode($id)
    {
        $goodShowcase = Goods::findOrFail($id);

        // Generate barcode
        $qrCode = new DNS2D();
        $qrCodeImage = $qrCode->getBarcodePNG($id, 'QRCODE');

        return view('print-page.print-barcode', [
            'goodShowcase' => $goodShowcase,
            'barcode' => $qrCodeImage,
        ]);
    }
}
