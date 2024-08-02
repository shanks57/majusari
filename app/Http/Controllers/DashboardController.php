<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\GoldRate;
use App\Models\Goods;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Stats for customers
            $customers = Customer::count();

            // Stats for goods in showcase
            $goodsInShowcase = Goods::where('availability', true)
                                    ->where('safe_status', false)
                                    ->get();
            $totalItemsInShowcase = $goodsInShowcase->count();
            $totalWeightInShowcase = $goodsInShowcase->sum('size');

            // Stats for goods in safe storage
            $goodsInSafeStorage = Goods::where('availability', true)
                                       ->where('safe_status', true)
                                       ->get();
            $totalItemsInSafeStorage = $goodsInSafeStorage->count();
            $totalWeightInSafeStorage = $goodsInSafeStorage->sum('size');

            // Stats for sales
            $transactions = Transaction::with('details.goods')
                                       ->get();
            $totalItemsSold = 0;
            $totalWeightSold = 0;
            foreach ($transactions as $transaction) {
                foreach ($transaction->details as $detail) {
                    $totalItemsSold++;
                    $totalWeightSold += $detail->goods->size;
                }
            }

             $goldRates = GoldRate::orderBy('created_at', 'desc')->paginate(5);

            return view('pages.dashboard', [
                'goldRates' => $goldRates,
                'customer_stats' => [
                    'total_items' => $customers,
                ],
                'goods_in_showcase_stats' => [
                    'total_items' => $totalItemsInShowcase,
                    'total_weight' => $totalWeightInShowcase,
                ],
                'goods_in_safe_storage_stats' => [
                    'total_items' => $totalItemsInSafeStorage,
                    'total_weight' => $totalWeightInSafeStorage,
                ],
                'transaction_stats' => [
                    'total_items_sold' => $totalItemsSold,
                    'total_weight_sold' => $totalWeightSold,
                ],
            ]);
        } catch (\Exception $e) {
            
        }
    }

    public function updateKurs(Request $request)
    {
        $request->validate([
            'new_price' => 'required|numeric',
        ]);

        try {
            $goldRate = GoldRate::create([
                'id' => Str::uuid(),
                'new_price' => $request->new_price,
            ]);

             session()->flash('success', 'Berhasil update harga emas terbaru');
            return redirect()->route('dashboard-page');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']);
        }
    }
}
