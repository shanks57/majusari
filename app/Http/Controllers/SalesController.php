<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = Transaction::with('details.goods')->orderBy('created_at', 'desc');

    //     // Ambil input tanggal dari request
    //     $dateStart = $request->input('date_start');
    //     $dateEnd = $request->input('date_end');

    //     // Jika tanggal mulai ada, tambahkan ke query
    //     if ($dateStart) {
    //         $query->whereDate('created_at', '>=', Carbon::parse($dateStart));
    //     }

    //     // Jika tanggal akhir ada, tambahkan ke query
    //     if ($dateEnd) {
    //         $query->whereDate('created_at', '<=', Carbon::parse($dateEnd));
    //     }

    //     // Ambil data penjualan
    //     $sales = $query->get(); 
    //     $totalItems = $sales->sum(function($sale) {
    //         return $sale->details->count();
    //     });

    //     $title = 'Penjualan';

    //     return view('pages.sales', compact('sales', 'title', 'totalItems'));
    // }

    public function index(Request $request)
    {
        $title = 'Penjualan';
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');
        $page = $request->get('page', 1);

        // Cache key unik berdasarkan tanggal & halaman
        $cacheKey = "sales:{$dateStart}:{$dateEnd}:page:{$page}";

        // Cache selama 5 menit (bisa disesuaikan)
        $sales = Cache::remember($cacheKey, 300, function () use ($dateStart, $dateEnd) {
            $query = Transaction::query()
                ->with([
                    'details.goods' => function ($q) {
                        $q->select('id', 'name', 'color', 'merk_id', 'code', 'image', 'rate', 'size', 'bid_rate')
                        ->with(['merk:id,company']); // eager load merk juga
                    },
                ]);

            if ($dateStart) {
                $query->whereDate('created_at', '>=', $dateStart);
            }
            if ($dateEnd) {
                $query->whereDate('created_at', '<=', $dateEnd);
            }

            return $query->latest('created_at')->paginate(20)->onEachSide(0);
        });

        // Hitung total item (cache juga)
        $totalItemsKey = "sales_total_items:{$dateStart}:{$dateEnd}";
        $totalItems = Cache::remember($totalItemsKey, 300, function () use ($sales) {
            return TransactionDetail::whereIn('transaction_id', $sales->pluck('id'))->count();
        });

        return view('pages.sales', compact('sales', 'title', 'totalItems'));
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
            session()->flash('good-type-additional-cost-form', $good->goodsType->additional_cost);
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

        $code = $request->input('nota');

        // Temukan transaksi berdasarkan kode
        $transaction = Transaction::with(['details.goods'])
            ->where('code', $code)
            ->first();

        if ($transaction) {
            // Simpan ID transaksi
            session()->flash('nota-good-id', $transaction->id);
            session()->flash('transaction-code', $transaction->code);

            // Simpan array detail barang
            $goodsDetails = [];
            foreach ($transaction->details as $detail) {
                $goodsDetails[] = [
                    'id' => $detail->goods->id,
                    'name' => $detail->goods->name,
                    'image' => $detail->goods->image,
                    'color' => $detail->goods->color,
                    'merk' => $detail->goods->merk->name,
                    'rate' => $detail->goods->rate,
                    'size' => $detail->goods->size,
                    'type' => $detail->goods->goodsType->name,
                    'showcase' => $detail->tray->showcase->name,
                    'tray' => $detail->tray->code,
                    'harga_jual' => $detail->harga_jual,
                ];
            }

            // Simpan array detail barang ke session
            session()->flash('nota-goods-details', $goodsDetails);

            return redirect()->route('sale.index', $transaction->id)
                ->with('nota-result', 'Transaksi ditemukan.');
        } else {
            return redirect()->back()->with('error', 'Kode penjualan tidak ditemukan.');
        }
    }

    public function printNota($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            $sales = TransactionDetail::with('goods')->where('transaction_id', $id)->get();

            return view('print-page.print-invoice', [
                'sales' => $sales,
                'transaction' => $transaction
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Barang tidak ditemukan
            return redirect()->route('sale.index')->with('error', 'Barang tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('sale.index')->with('error', 'Terjadi kesalahan saat menghasilkan barcode. Silakan coba lagi.');
        }
    }

    public function export(Request $request)
    {
        $query = Transaction::query();

        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        if ($dateStart) {
            $query->whereDate('created_at', '>=', Carbon::parse($dateStart));
        }
        if ($dateEnd) {
            $query->whereDate('created_at', '<=', Carbon::parse($dateEnd));
        }

        $sales = $query->with('details.goods')->get();

        $format = $request->input('format');

        switch ($format) {
            case 'pdf':
                return $this->exportToPDF($sales);
            case 'excel':
                return $this->exportToExcel($sales);
            case 'print':
                return view('print-page.print-sales', compact('sales'));
            default:
                return redirect()->back();
        }
    }

    public function exportToPDF($sales)
    {
        $pdf = PDF::loadView('pdf-page.sales-report', ['sales' => $sales])->setPaper('a4', 'landscape');
        $timestamp = now()->format('Y-m-d_H-i-s');
        return $pdf->download("Laporan-Penjualan_{$timestamp}.pdf");
    }

    public function exportToExcel($sales)
    {
        // Format data untuk ekspor
        $data = $sales->map(function ($sale) {
            $details = $sale->details;

            return [
                'nota' => $sale->code,
                'name' => $details->pluck('goods.name')->implode(', '),
                'category' => $details->pluck('goods.category')->implode(', '),
                'unit' => $details->pluck('goods.unit')->implode(', '),
                'type' => $details->pluck('goods.goodsType.name')->implode(', '),
                'color' => $details->pluck('goods.color')->implode(', '),
                'rate' => $details->pluck('goods.rate')->map(fn($rate) => number_format($rate, 0) . '%')->implode(', '),
                'size' => $details->pluck('goods.size')->map(fn($size) => number_format($size, 2) . 'gr')->implode(', '),
                'merk' => $details->pluck('goods.merk.name')->implode(', '),
                'ask_price' => $details->pluck('goods.ask_price')->implode(', '),
                'ask_rate' => $details->pluck('goods.ask_rate')->map(fn($ask_rate) => number_format($ask_rate, 0) . '%')->implode(', '),
                'bid_price' => $details->pluck('goods.bid_price')->implode(', '),
                'bid_rate' => $details->pluck('goods.bid_rate')->map(fn($bid_rate) => number_format($bid_rate, 0) . '%')->implode(', '),
                'harga_jual' => $details->pluck('harga_jual')->implode(', '),
                'date' => Carbon::parse($sale->date)->format('d/m/Y'),
            ];
        });

        // Gunakan export untuk menghasilkan file Excel
        $export = new SalesExport($data);
        $file = \Maatwebsite\Excel\Facades\Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);

        // Tambahkan elemen dinamis ke nama file
        $timestamp = now()->format('Y-m-d_H-i-s'); // Format tanggal dan waktu
        $filename = "Laporan-penjualan_{$timestamp}.xlsx";

        return Excel::download(new SalesExport($data), $filename);
    }
}
