<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;


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
        $goodShowcase = Goods::findOrFail($id);
        $goodShowcase->delete();

        return redirect()->route('goods.showcase')->with('success', 'Berhasil Menghapus Data Barang di Etalase');
    }

    public function printBarcode($id)
    {
        $goodShowcase = Goods::findOrFail($id);

        // Membuat instance dari DNS1D untuk barcode 1D
        $barcodeGenerator = new DNS1D();
        
        // Menghasilkan barcode dalam format C39 (Code 39)
        $barcodeImage = $barcodeGenerator->getBarcodePNG($goodShowcase->code, 'C128');

        return view('print-page.print-barcode', [
            'goodShowcase' => $goodShowcase,
            'barcode' => $barcodeImage,
        ]);
    }

}
