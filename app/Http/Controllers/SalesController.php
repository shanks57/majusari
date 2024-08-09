<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            session()->flash('good-price-form', $good->ask_price);
            session()->flash('good-color-form', $good->color);
            session()->flash('good-merk-form', $good->merk->name);
            session()->flash('good-rate-form', $good->rate);
            session()->flash('good-size-form', $good->size);
            session()->flash('good-type-form', $good->goodsType->name);
            session()->flash('good-showcase-form', $good->tray->showcase->name);
            session()->flash('good-tray-form', $good->tray->code);
            session()->flash('good-tray-id-form', $good->tray->id);
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
        $goodsId = $request->input('goods_id');
        $sellingPrice = $request->input('new_selling_price');
        $askPrice = $request->input('ask_price');
        $trayId = $request->input('tray_id');

        $status_price = $sellingPrice < $askPrice ? 0 : 1;

        Cart::create([
            'id' => $id,
            'user_id' => Auth::user()->id,
            'tray_id' => $trayId,
            'goods_id' => $goodsId,
            'status_price' => $status_price,
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

    public function insertToCartInChart(Request $request)
    {
        $request->validate([
            'goods_id' => 'required|exists:goods,id',
            'new_selling_price' => 'required|numeric|min:1',
        ]);

        $id = (string) Str::uuid();
        $userId = 'c861067e-8c00-4ad8-8c1c-53cf5924675d';
        $goodsId = $request->input('goods_id');
        $sellingPrice = $request->input('new_selling_price');
        $askPrice = $request->input('ask_price');
        $trayId = $request->input('tray_id');

        $status_price = $sellingPrice < $askPrice ? 0 : 1;

        Cart::create([
            'id' => $id,
            'user_id' => Auth::user()->id,
            'goods_id' => $goodsId,
            'tray_id' => $trayId,
            'status_price' => $status_price,
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
        return redirect()->route('pages.cart');
    }

    public function cart()
    {
        $userId = auth()->id();
        $carts = Cart::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        $title = 'Cart';
        $customers = Customer::all();
        return view('pages.cart', compact('carts', 'title', 'customers'));
    }

    public function destroy($id)
    {
        try {
            $cartItem = Cart::findOrFail($id);

            $goodsId = $cartItem->goods_id;

            $cartItem->forceDelete();

            $goods = Goods::find($goodsId);
            if ($goods) {
                $goods->availability = 1;
                $goods->save();
            }

            return redirect()->route('pages.cart')->with('success', 'Data Keranjang berhasil dihapus dari keranjang.');

        } catch (\Exception $e) {

            return redirect()->route('pages.cart')->with('error', 'Terjadi kesalahan saat menghapus data dari keranjang.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'new_selling_price' => 'required|numeric|min:0',
        ]);

        try {
            $cart = Cart::findOrFail($id);

            if (!$cart->goods) {
                return redirect()->back()->withErrors(['Barang tidak ditemukan untuk cart ini.']);
            }

            $askPrice = $cart->goods->ask_price;

            $cart->new_selling_price = $request->input('new_selling_price');

            if ($cart->new_selling_price >= $askPrice) {
                $cart->status_price = 1;
            } else {
                $cart->status_price = 0;
            }

            $cart->save();

            return redirect()->back()->with('success', 'Harga jual berhasil diupdate.');

        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withErrors(['Cart tidak ditemukan.']);
        } catch (QueryException $e) {
            return redirect()->back()->withErrors(['Terjadi kesalahan saat mengupdate database.']);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function addComplaint(Request $request, $id)
    {
        $request->validate([
            'complaint' => 'required|string|max:255',
        ]);

        try {
            $cart = Cart::findOrFail($id);

            $cart->complaint = $request->input('complaint');
            $cart->status_price = 2;

            $cart->save();

            return redirect()->back()->with('success-complaint', 'Berhasil Mengirimkan Persetujuan.');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->withErrors(['Cart tidak ditemukan.']);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function getNotification()
    {
        $notifications = Cart::withTrashed()
            ->whereNotNull('complaint')
            ->orderBy('updated_at', 'desc')
            ->get();
        $title = 'Pemberitahuan';
        return view('pages.notification', compact('notifications'));
    }

    public function rejectPrice($notifId, Request $request)
    {
        $notification = Cart::findOrFail($notifId);
        
        $notification->status_price = $request->status_price;
        
        $notification->save();
        
        return redirect()->back()->with('success', 'Harga yang diajukan ditolak.');
    }

    public function agreePrice($notifId, Request $request)
    {
        $notification = Cart::findOrFail($notifId);
        
        $notification->status_price = $request->status_price;
        
        $notification->save();
        
        return redirect()->back()->with('success', 'Harga berhasil disetujui.');
    }

    public function checkout(Request $request)
    {
        // dd($request->all());
        // Memulai transaksi database
        DB::beginTransaction();

        try {
            // Cek jika customer baru atau lama
            $customerId = $request->input('old_customer_id');

            if (!$customerId) {
                // Tambah pelanggan baru jika diperlukan
                $customer = Customer::create([
                    'id' => Str::uuid(),
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                ]);
                $customerId = $customer->id;
            }

            // Buat entri transaksi baru
            $transaction = Transaction::create([
                'id' => Str::uuid(),
                'code' => str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'user_id' => $request->input('user_id'),
                'customer_id' => $customerId,
                'total' => $request->input('total'),
                'payment_method' => $request->input('payment_method'),
                'date' => $request->input('date'),
            ]);

            // Iterasi melalui setiap barang di cart
            $cartItems = $request->input('cart_items');
            $cartIds = [];

            foreach ($cartItems as $cartItem) {
                // Dapatkan item cart
                $cart = Cart::findOrFail($cartItem['cart_id']);
                $cartIds[] = $cart->id;

                // Buat detail transaksi baru
                $transactionDetail = TransactionDetail::create([
                    'id' => Str::uuid(),
                    'nota' => str_pad(mt_rand(1, 99999), 8, '0', STR_PAD_LEFT),
                    'transaction_id' => $transaction->id,
                    'goods_id' => $cartItem['goods_id'],
                    'harga_jual' => $cartItem['harga_jual'],
                    'tray_id' => $cartItem['tray_id'],
                ]);

                // Tambahkan detail transaksi ke array
                $transactionDetails[] = $transactionDetail;

                // Update barang di tabel goods
                Goods::where('id', $cartItem['goods_id'])->update([
                    'position' => null,
                    'tray_id' => null,
                ]);
            }

            // Hapus semua barang dari cart (soft delete)
            Cart::whereIn('id', $cartIds)->delete();

            // Komit transaksi
            DB::commit();

            session()->flash('transaction_details', $transactionDetails);
            return redirect()->route('pages.cart')->with('success-checkout', 'Checkout berhasil!');

        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();

            // Catat exception ke file log
            Log::error('Checkout error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(), // Menyertakan data request jika perlu
            ]);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function searchNota(Request $request)
    {
        $request->validate([
            'nota' => 'required|string'
        ]);

        $nota = $request->input('nota');
        
        $transaction = TransactionDetail::where('nota', $nota)->first();

        if ($transaction) {
            
            session()->flash('nota-good-name', $transaction->goods->name);
            session()->flash('nota-penjualan', $transaction->nota);
            session()->flash('nota-goods-image', $transaction->goods->image);
            session()->flash('nota-good-color', $transaction->goods->color);
            session()->flash('nota-good-merk', $transaction->goods->merk->name);
            session()->flash('nota-good-rate', $transaction->goods->rate);
            session()->flash('nota-good-size', $transaction->goods->size);
            session()->flash('nota-good-type', $transaction->goods->goodsType->name);
            session()->flash('nota-good-showcase', $transaction->tray->showcase->name);
            session()->flash('nota-good-tray', $transaction->tray->code);
            session()->flash('nota-harga-jual', $transaction->harga_jual);

            return redirect()->route('sale.index', $transaction->id)
                             ->with('nota-result', 'Transaksi ditemukan.');
        } else {
            return redirect()->back()->with('error', 'Kode penjualan tidak ditemukan.');
        }
    }

    public function printNota($id)
    {
        try {
            $sale = TransactionDetail::findOrFail($id);

            return view('print-page.print-invoice', [
                'sale' => $sale,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Barang tidak ditemukan
            return redirect()->route('sale.index')->with('error', 'Barang tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('sale.index')->with('error', 'Terjadi kesalahan saat menghasilkan barcode. Silakan coba lagi.');
        }
    }

}
