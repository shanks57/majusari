<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function getStats()
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

            return response()->json([
                'status' => 'success',
                'data' => [
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
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSalesSummary(Request $request)
    {
        try {
            $period = $request->input('period', 'year');

            // Menyesuaikan tanggal berdasarkan periode
            switch ($period) {
                case 'month':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    $dateFormat = 'DAY(created_at)';
                    $dateAlias = 'day';
                    break;
                case 'week':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    $dateFormat = 'DAYOFWEEK(created_at)';
                    $dateAlias = 'day_of_week';
                    break;
                default: // 'year'
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    $dateFormat = 'MONTH(created_at)';
                    $dateAlias = 'month';
                    break;
            }

            // Mendapatkan ringkasan penjualan
            $salesSummary = DB::table('transactions')
                ->select(
                    DB::raw('SUM(total) as total_sales'),
                    DB::raw("$dateFormat as $dateAlias")
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy($dateAlias)
                ->get();

            // Konversi data ke format yang diinginkan
            $formattedSalesData = $salesSummary->map(function ($item) use ($dateAlias, $period) {
                $dateValue = $item->{$dateAlias};

                if ($period == 'week') {
                    // Konversi nilai hari dalam minggu ke nama hari
                    $dateName = Carbon::getDays()[$dateValue - 1];
                } elseif ($period == 'month') {
                    $dateName = $dateValue;
                } else {
                    $dateName = Carbon::create()->month($dateValue)->shortMonthName;
                }

                return [
                    $dateAlias => $dateName,
                    'total_sales' => $item->total_sales
                ];
            });

            $totalSales = $salesSummary->sum('total_sales');

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_sales' => $totalSales,
                    'sales_data' => $formattedSalesData
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve sales summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
