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
            $salesData = $this->salesSummary($request);
            $goodsData = $this->goodsSummary($request);

            return view('pages.dashboard', [
                
                'monthlyItemsSold' => $salesData['monthlyItemsSold'],
                'totalSales' => $salesData['totalSales'],
                'filter' => $salesData['filter'],

                'goodsInValues' => $goodsData['goodsInValues'],
                'goodsOutValues' => $goodsData['goodsOutValues'],
                'totalIn' => $goodsData['totalIn'],
                'totalOut' => $goodsData['totalOut'],
                'totalGoodsIn' => $goodsData['totalGoodsIn'],
                'totalGoodsOut' => $goodsData['totalGoodsOut'],
                // 'filter' => $goodsData['filter'],

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
            return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

    public function goodsSummary(Request $request)
    {
        // Mendapatkan filter waktu
        $filter = $request->input('filter', 'tahun-ini');

        // Mendapatkan tanggal saat ini
        $now = Carbon::now();

        // Menginisialisasi variabel untuk query
        $startDate = $now->startOfYear();
        $endDate = $now->endOfYear();

        // Mengatur rentang tanggal berdasarkan filter yang dipilih
        switch ($filter) {
            case 'bulan-ini':
                $startDate = $now->startOfMonth();
                $endDate = $now->endOfMonth();
                break;
            case 'minggu-ini':
                $startDate = $now->startOfWeek();
                $endDate = $now->endOfWeek();
                break;
            case 'hari-ini':
                $startDate = $now->startOfDay();
                $endDate = $now->endOfDay();
                break;
            default: // 'year'
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
        }

        // Query untuk total barang masuk
        $goodsInData = DB::table('goods')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(ask_price) as total_in'), DB::raw('COUNT(id) as total_goods_in'))
            ->where('availability', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Query untuk total barang keluar
        $goodsOutData = DB::table('transaction_details')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(harga_jual) as total_out'), DB::raw('COUNT(id) as total_goods_out'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $goodsInValues = array_fill(0, 12, 0);
        $goodsOutValues = array_fill(0, 12, 0);
        $totalGoodsIn = array_fill(0, 12, 0);
        $totalGoodsOut = array_fill(0, 12, 0);

        foreach ($goodsInData as $data) {
            $goodsInValues[$data->month - 1] = $data->total_in;
            $totalGoodsIn[$data->month - 1] = $data->total_goods_in;
        }

        foreach ($goodsOutData as $data) {
            $goodsOutValues[$data->month - 1] = $data->total_out;
            $totalGoodsOut[$data->month - 1] = $data->total_goods_out;
        }

        // Menghitung total barang masuk dan keluar
        $totalIn = array_sum($goodsInValues);
        $totalOut = array_sum($goodsOutValues);

        // Mengembalikan data sebagai array
        return compact('goodsInValues', 'goodsOutValues', 'totalIn', 'totalOut', 'totalGoodsIn', 'totalGoodsOut');
    }

    public function salesSummary(Request $request)
    {
        // Mendapatkan filter waktu
        $filter = $request->input('filter', 'tahun-ini');

        // Mendapatkan tanggal saat ini
        $now = Carbon::now();

        // Menginisialisasi variabel untuk query
        $startDate = $now->startOfYear();
        $endDate = $now->endOfYear();

        // Mengatur rentang tanggal berdasarkan filter yang dipilih
        switch ($filter) {
            case 'bulan-ini':
                $startDate = $now->startOfMonth();
                $endDate = $now->endOfMonth();
                break;
            case 'minggu-ini':
                $startDate = $now->startOfWeek();
                $endDate = $now->endOfWeek();
                break;
            case 'hari-ini':
                $startDate = $now->startOfDay();
                $endDate = $now->endOfDay();
                break;
            default: // 'year'
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
        }

        // Mengambil jumlah penjualan (barang) dari database
        $itemSalesData = DB::table('transaction_details')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(id) as total_items_sold'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Mengambil total penjualan (nilai) dari database
        $salesData = DB::table('transaction_details')
            ->select(DB::raw('SUM(harga_jual) as total_sales'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->first();

        // Menyiapkan data untuk grafik berdasarkan jumlah barang terjual
        $monthlyItemsSold = array_fill(0, 12, 0);
        foreach ($itemSalesData as $data) {
            $monthlyItemsSold[$data->month - 1] = $data->total_items_sold;
        }

        // Menghitung total penjualan
        $totalSales = $salesData->total_sales ?? 0;

        // Mengembalikan data sebagai array
        return compact('monthlyItemsSold', 'totalSales', 'filter');
    }

    public function updateKurs(Request $request)
    {
        $request->validate([
            'new_price' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999999',
                'regex:/^\d{1,13}$/'
            ]
        ], [
            'new_price.regex' => 'Harga baru harus berupa angka dengan panjang maksimum 13 digit.',
            'new_price.min' => 'Harga baru tidak boleh kurang dari 0.',
            'new_price.max' => 'Harga baru tidak boleh lebih dari 13 digit.',
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
