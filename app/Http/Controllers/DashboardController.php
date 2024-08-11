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
            return response()->json([
            'message' => $e->getMessage()
        ], 500);
        }
    }

    public function getWeightChartData(Request $request)
    {
        $filter = $request->input('filter', 'year');
        try {
            $goodsSummary = $this->processWeightChartSummary($filter);
            return response()->json($goodsSummary);
        } catch (\Exception $e) {
            // Tangani pengecualian dan kirimkan pesan kesalahan
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function processWeightChartSummary($filter)
    {
        $dataIn = [];
        $dataOut = [];
        $labels = [];
        $totalGoodsIn = 0;
        $totalGoodsOut = 0;

        if ($filter == 'year') {
            // Ambil data per bulan dalam setahun
            $year = Carbon::now()->year;
            $goodsIn = Goods::selectRaw('MONTH(created_at) as month, SUM(size) as total')
                ->whereYear('created_at', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $goodsOut = TransactionDetail::selectRaw('MONTH(transactions.date) as month, SUM(goods.size) as total')
                ->join('goods', 'transaction_details.goods_id', '=', 'goods.id')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->whereYear('transactions.date', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            for ($month = 1; $month <= 12; $month++) {
                $monthName = Carbon::create()->month($month)->format('M'); // Nama bulan
                $labels[] = $monthName;
                $monthlyGoodsIn = $goodsIn->firstWhere('month', $month)->total ?? 0;
                $monthlyGoodsOut = $goodsOut->firstWhere('month', $month)->total ?? 0;
                $dataIn[] = $monthlyGoodsIn;
                $dataOut[] = $monthlyGoodsOut;
                $totalGoodsIn += $monthlyGoodsIn;
                $totalGoodsOut += $monthlyGoodsOut;
            }
        } elseif ($filter == 'month') {
            // Ambil data per hari dalam bulan ini
            $month = Carbon::now()->month;
            $year = Carbon::now()->year;
            $daysInMonth = Carbon::now()->daysInMonth;

            $goodsIn = Goods::selectRaw('DAY(created_at) as day, SUM(size) as total')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            $goodsOut = TransactionDetail::selectRaw('DAY(transactions.date) as day, SUM(goods.size) as total')
                ->join('goods', 'transaction_details.goods_id', '=', 'goods.id')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->whereYear('transactions.date', $year)
                ->whereMonth('transactions.date', $month)
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $labels[] = "$day";
                $dailyGoodsIn = $goodsIn->firstWhere('day', $day)->total ?? 0;
                $dailyGoodsOut = $goodsOut->firstWhere('day', $day)->total ?? 0;
                $dataIn[] = $dailyGoodsIn;
                $dataOut[] = $dailyGoodsOut;
                $totalGoodsIn += $dailyGoodsIn;
                $totalGoodsOut += $dailyGoodsOut;
            }
        } else {
            // Ambil data per hari dalam seminggu
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $goodsIn = Goods::selectRaw('DAYOFWEEK(created_at) as day, SUM(size) as total')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            $goodsOut = TransactionDetail::selectRaw('DAYOFWEEK(transactions.date) as day, SUM(goods.size) as total')
                ->join('goods', 'transaction_details.goods_id', '=', 'goods.id')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->whereBetween('transactions.date', [$startOfWeek, $endOfWeek])
                ->groupBy('day')
                ->orderBy('day')
                ->get();

            $daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            foreach ($daysOfWeek as $index => $day) {
                $labels[] = $day;
                $weeklyGoodsIn = $goodsIn->firstWhere('day', $index + 1)->total ?? 0;
                $weeklyGoodsOut = $goodsOut->firstWhere('day', $index + 1)->total ?? 0;
                $dataIn[] = $weeklyGoodsIn;
                $dataOut[] = $weeklyGoodsOut;
                $totalGoodsIn += $weeklyGoodsIn;
                $totalGoodsOut += $weeklyGoodsOut;
            }
        }

        return [
            'labels' => $labels,
            'goodsInData' => $dataIn,
            'goodsOutData' => $dataOut,
            'totalGoodsIn' => $totalGoodsIn . ' gr',
            'totalGoodsOut' => $totalGoodsOut . ' gr',
        ];
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
                    $transactions = TransactionDetail::selectRaw('DAYOFWEEK(transactions.date) as day, SUM(harga_jual) as total')
                        ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id') // Join with transactions table
                        ->whereDate('transactions.date', '>=', $startDate)
                        ->whereDate('transactions.date', '<=', $endDate)
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
                    $transactions = TransactionDetail::selectRaw('DAY(transactions.date) as day, SUM(harga_jual) as total')
                        ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id') // Join with transactions table
                        ->whereDate('transactions.date', '>=', $startDate)
                        ->whereDate('transactions.date', '<=', $endDate)
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
                    $transactions = TransactionDetail::selectRaw('MONTH(transactions.date) as month, SUM(harga_jual) as total')
                        ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id') // Join with transactions table
                        ->whereDate('transactions.date', '>=', $startDate)
                        ->whereDate('transactions.date', '<=', $endDate)
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
                $transactions = TransactionDetail::selectRaw('YEAR(transactions.date) as year, SUM(harga_jual) as total')
                    ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id') // Join with transactions table
                    ->whereDate('transactions.date', '>=', $startDate)
                    ->whereDate('transactions.date', '<=', $endDate)
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
        } catch(\Exception $e) {
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
            $transactions = TransactionDetail::selectRaw('MONTH(transactions.date) as month, SUM(harga_jual) as total')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id') // Join with transactions table
                ->whereYear('transactions.date', $year)
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

            $transactions = TransactionDetail::selectRaw('DAY(transactions.date) as day, SUM(harga_jual) as total')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id') // Join with transactions table
                ->whereYear('transactions.date', $year)
                ->whereMonth('transactions.date', $month)
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
            $transactions = TransactionDetail::selectRaw('DAYOFWEEK(transactions.date) as day, SUM(harga_jual) as total')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id') // Join with transactions table
                ->whereBetween('transactions.date', [$startOfWeek, $endOfWeek])
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
            'totalSaleSalesSummary' => $totalSaleSalesSummary,
        ];
    }
    // End Sales Summary Chart

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
