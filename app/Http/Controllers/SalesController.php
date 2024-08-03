<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Goods;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SalesController extends Controller
{
    public function index()
    {
        $sales = TransactionDetail::orderBy('created_at', 'desc')
            ->get();
        $title = 'Penjualan';
        return view('pages.sales', compact('sales', 'title'));
    }

    public function searchCode(Request $request)
    {
        // Validate the request input
        $request->validate([
            'code' => 'required|string'
        ]);

        $good = Goods::where('code', $request->input('code'))
                 ->where('availability', 1)
                 ->where('safe_status', 0)
                 ->first();

        // Check if a good was found
        if ($good) {
            // Flash the good data to the session
            session()->flash('good-name-form', $good->name);
            session()->flash('good-id-form', $good->id);
            session()->flash('good-color-form', $good->color);
            session()->flash('good-merk-form', $good->merk->name);
            session()->flash('good-rate-form', $good->rate);
            session()->flash('good-size-form', $good->size);
            session()->flash('good-type-form', $good->goodsType->name);
            session()->flash('good-showcase-form', $good->tray->showcase->name);
            session()->flash('good-tray-form', $good->tray->code);
            session()->flash('good-image-form', $good->image);

            // Optionally, redirect with a success message
            return redirect()->back()->with('modal-form', 'Barang ditemukan.');
        } else {
            // Flash an error message if not found
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }
    }

    public function insertToCart(Request $request)
    {
        $request->validate([
            'goods_id' => 'required|exists:goods,id',
            'new_selling_price' => 'required|numeric|min:1',
        ]);

        $id = (string) Str::uuid();
        $userId = 'c861067e-8c00-4ad8-8c1c-53cf5924675d';
        $goodsId = $request->input('goods_id');
        $sellingPrice = $request->input('new_selling_price');

        Cart::create([
            'id' => $id,
            'user_id' => Auth::user()->id,
            'goods_id' => $goodsId,
            'new_selling_price' => $sellingPrice,
        ]);
    
        $good = Goods::find($goodsId);

        $good->update(['availability' => 0]);

        session()->flash('good-name-cart', $good->name);
        session()->flash('good-id-cart', $good->id);
        session()->flash('good-color-cart', $good->color);
        session()->flash('good-merk-cart', $good->merk->name);
        session()->flash('good-rate-cart', $good->rate);
        session()->flash('good-size-cart', $good->size);
        session()->flash('good-type-cart', $good->goodsType->name);
        session()->flash('good-showcase-cart', $good->tray->showcase->name);
        session()->flash('good-tray-cart', $good->tray->code);
        session()->flash('good-image-cart', $good->image);
        session()->flash('new_selling_price', $sellingPrice);

        session()->flash('success-cart', 'Berhasil Menambahkan Data');
        return redirect()->route('sale.index');
    }

}
