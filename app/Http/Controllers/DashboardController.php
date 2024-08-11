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
            $goodsData = $this->goodsSummary($request);

            return view('pages.dashboard', [
            
                'goodsInValues' => $goodsData['goodsInValues'],
                'goodsOutValues' => $goodsData['goodsOutValues'],
                'totalIn' => $goodsData['totalIn'],
                'totalOut' => $goodsData['totalOut'],
                'totalGoodsIn' => $goodsData['totalGoodsIn'],
                'totalGoodsOut' => $goodsData['totalGoodsOut'],
                'filter' => '',

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

    // start sale summary detail
    public function getChartSalesSummaryDetail(Request $request)
    {
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        // Gunakan tanggal saat ini jika tidak ada parameter tanggal
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfYear();
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfYear();

        $DetailSalesSummary = $this->processChartSalesSummaryDetail($startDate, $endDate);

        return response()->json($DetailSalesSummary);
    }

    private function processChartSalesSummaryDetail($startDate, $endDate)
    {
        try{
            $data = [];
        $labels = [];
        $totalSales = 0;

        if ($startDate->isSameYear($endDate)) {
            if ($startDate->isSameWeek($endDate)) {
                // Rentang dalam satu minggu
                $daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                $transactions = TransactionDetail::selectRaw('DAYOFWEEK(created_at) as day, SUM(harga_jual) as total')
                    ->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate)
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get();

                foreach ($daysOfWeek as $index => $day) {
                    $labels[] = $day;
                    $weeklyTotal = $transactions->firstWhere('day', $index + 1)->total ?? 0;
                    $data[] = $weeklyTotal;
                    $totalSales += $weeklyTotal;
                }
            } elseif ($startDate->isSameMonth($endDate)) {
                // Rentang dalam satu bulan
                $daysInMonth = $startDate->daysInMonth;
                $transactions = TransactionDetail::selectRaw('DAY(created_at) as day, SUM(harga_jual) as total')
                    ->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate)
                    ->groupBy('day')
                    ->orderBy('day')
                    ->get();

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $labels[] = "$day";
                    $dailyTotal = $transactions->firstWhere('day', $day)->total ?? 0;
                    $data[] = $dailyTotal;
                    $totalSales += $dailyTotal;
                }
            } else {
                // Rentang dalam satu tahun
                $transactions = TransactionDetail::selectRaw('MONTH(created_at) as month, SUM(harga_jual) as total')
                    ->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();

                for ($month = 1; $month <= 12; $month++) {
                    $monthName = Carbon::create()->month($month)->format('M'); // Nama bulan
                    $labels[] = $monthName;
                    $monthlyTotal = $transactions->firstWhere('month', $month)->total ?? 0;
                    $data[] = $monthlyTotal;
                    $totalSales += $monthlyTotal;
                }
            }
        } else {
            // Rentang melintasi beberapa tahun
            $years = range($startDate->year, $endDate->year);
            $transactions = TransactionDetail::selectRaw('YEAR(created_at) as year, SUM(harga_jual) as total')
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->groupBy('year')
                ->orderBy('year')
                ->get();

            foreach ($years as $year) {
                $labels[] = $year;
                $yearlyTotal = $transactions->firstWhere('year', $year)->total ?? 0;
                $data[] = $yearlyTotal;
                $totalSales += $yearlyTotal;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'totalSales' => $totalSales, // Sertakan total penjualan dalam response
        ];
        }catch(\Exception $e) {
        \Log::error('Error processing chart sales summary: ' . $e->getMessage());
        return response()->json(['error' => 'Server Error'], 500);
    }
    }
    // end sale summary detail

    // Start Sales Summary Chart
    public function getChartData(Request $request)
    {
        $filter = $request->input('filter', 'year'); // Default adalah 'year'
        $SalesSummary = $this->processChartSalesSummary($filter);

        return response()->json($SalesSummary);
    }

    private function processChartSalesSummary($filter)
    {
        $data = [];
        $labels = [];
        $totalSaleSalesSummary = 0;

        if ($filter == 'year') {
            // Ambil data per bulan dalam setahun
            $year = Carbon::now()->year;
            $transactions = TransactionDetail::selectRaw('MONTH(created_at) as month, SUM(harga_jual) as total')
                ->whereYear('created_at', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            for ($month = 1; $month <= 12; $month++) {
                $monthName = Carbon::create()->month($month)->format('M'); // Nama bulan
                $labels[] = $monthName;
                $monthlyTotal = $transactions->firstWhere('month', $month)->total ?? 0;
                $data[] = $monthlyTotal;
                $totalSaleSalesSummary += $monthlyTotal;
            }
        } elseif ($filter == 'month') {
            // Ambil data per hari dalam bulan ini
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
            $daysInMonth = Carbon::now()->daysInMonth;

            $transactions = TransactionDetail::selectRaw('DAY(created_at) as day, SUM(harga_jual) as total')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $labels[] = "$day";
                $dailyTotal = $transactions->firstWhere('day', $day)->total ?? 0;
                $data[] = $dailyTotal;
                $totalSaleSalesSummary += $dailyTotal;
            }
        } else {
            // Ambil data per hari dalam seminggu
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            $transactions = TransactionDetail::selectRaw('DAYOFWEEK(created_at) as day, SUM(harga_jual) as total')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            $daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            foreach ($daysOfWeek as $index => $day) {
                $labels[] = $day;
                $weeklyTotal = $transactions->firstWhere('day', $index + 2)->total ?? 0;
                $data[] = $weeklyTotal;
                $totalSaleSalesSummary += $weeklyTotal;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'totalSaleSalesSummary' => $totalSaleSalesSummary,  // Sertakan total penjualan dalam response
        ];
    }
    // End Sales Summary Chart

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
