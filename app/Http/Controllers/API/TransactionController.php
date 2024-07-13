<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Goods;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $transactions = Transaction::with(['details.goods', 'customer'])->paginate(15);

            return response()->json([
                $transactions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $transaction = Transaction::with(['details.goods', 'customer'])->findOrFail($id);

            if (!$transaction) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'transaction retrieved successfully',
                'data' => $transaction
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getByCode(Request $request)
    {
        $code = $request->query('nota');
        
        if (!$code) {
            return response()->json([
                'status' => 'error',
                'message' => 'Code parameter is required'
            ], 400);
        }

        $transaction = Transaction::with('details.goods')
            ->where('code', $code)
            ->first();

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $transaction->id,
                'code' => $transaction->code,
                'date' => $transaction->date,
                'customer_id' => $transaction->customer_id,
                'goods' => $transaction->details->map(function($detail) {
                    return [
                        'id' => $detail->goods->id,
                        'name' => $detail->goods->name,
                        'size' => $detail->goods->size,
                        'rate' => $detail->goods->rate,
                        'ask_price' => $detail->goods->ask_price,
                        'ask_rate' => $detail->goods->ask_rate,
                    ];
                })
            ]
        ]);
    }

    public function getGoodsByBarcode(Request $request)
    {
        $id = $request->query('barcode');

        if (!$id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Code parameter is required'
            ], 400);
        }

        $goods = Goods::where('id', $id)->first();

        if (!$goods) {
            return response()->json([
                'status' => 'error',
                'message' => 'Goods not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $goods->id,
                'code' => $goods->code,
                'name' => $goods->name,
                'size' => $goods->size,
                'rate' => $goods->rate,
                'ask_price' => $goods->ask_price,
                'ask_rate' => $goods->ask_rate,
            ]
        ]);

    }

    // Get all transaksi with goods names grouped by date
    public function indexWithGoodsGroupedByDate()
    {
        try {
            $transaksi = Transaction::with('details.goods')->get()->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

            $result = [];

            foreach ($transaksi as $date => $transactions) {
            $result[$date] = $transactions->flatMap(function ($transaction) {
                return $transaction->details->map(function ($detail) {
                    return [
                        'goods_id' => $detail->goods->id,
                        'goods_name' => $detail->goods->name,
                        'goods_size' => $detail->goods->size,
                        'goods_rate' => $detail->goods->rate,
                        'goods_ask_price' => $detail->goods->ask_price,
                        'goods_ask_rate' => $detail->goods->ask_rate,
                    ];
                });
            });
        }

            $perPage = 15;
            $currentPage = request()->query('page', 1);
            $pagedData = array_slice($result, ($currentPage - 1) * $perPage, $perPage, true);
            $paginatedResult = new \Illuminate\Pagination\LengthAwarePaginator(
                $pagedData,
                count($result),
                $perPage,
                $currentPage
            );
            $paginatedResult->setPath(URL::full());

            return response()->json($paginatedResult);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }   

    public function search(Request $request)
    {
        $query = $request->query('query');
        $perPage = $request->query('per_page', 15); // Default 15 items per page

        if (!$query) {
            return response()->json([
                'status' => 'error',
                'message' => 'Query parameter is required'
            ], 400);
        }

        $transactions = Transaction::with('details.goods')
            ->where('code', 'LIKE', "%{$query}%")
            ->orWhereHas('details.goods', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('size', 'LIKE', "%{$query}%")
                ->orWhere('rate', 'LIKE', "%{$query}%")
                ->orWhere('ask_price', 'LIKE', "%{$query}%")
                ->orWhere('ask_rate', 'LIKE', "%{$query}%");
            })
            ->get()
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
            });

        if ($transactions->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No transactions found'
            ], 404);
        }

        $result = [];

        foreach ($transactions as $date => $transactionGroup) {
            $result[$date] = $transactionGroup->flatMap(function ($transaction) {
                return $transaction->details->map(function ($detail) {
                    return [
                        'goods_id' => $detail->goods->id,
                        'goods_name' => $detail->goods->name,
                        'goods_size' => $detail->goods->size,
                        'goods_rate' => $detail->goods->rate,
                        'goods_ask_price' => $detail->goods->ask_price,
                        'goods_ask_rate' => $detail->goods->ask_rate,
                    ];
                });
            });
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagedData = array_slice($result, ($currentPage - 1) * $perPage, $perPage, true);
        $paginatedResult = new LengthAwarePaginator(
            $pagedData,
            count($result),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return response()->json($paginatedResult);
    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|uuid|exists:customers,id',
            'user_id' => 'required|integer|exists:users,id',
            'cart_ids' => 'required|array',
            'cart_ids.*' => 'uuid|exists:carts,id'
        ]);

        try {
            // Buat transaksi baru
            $transaction = Transaction::create([
                'id' => Str::uuid(),
                'code' => str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'date' => Carbon::now(),
                'user_id' => $request->user_id,
                'customer_id' => $request->customer_id,
                'total' => 0 // Placeholder untuk total
            ]);

            // Ambil barang dari cart berdasarkan cart_ids
            $cartItems = Cart::whereIn('id', $request->cart_ids)->get();

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No items found in the selected carts'
                ], 400);
            }

            $totalAmount = 0;

            // Tambahkan detail transaksi dan hitung total penjualan
            foreach ($cartItems as $item) {
                $goods = Goods::find($item->goods_id);
                if ($goods) {
                    $totalAmount += $goods->ask_price;

                    TransactionDetail::create([
                        'id' => Str::uuid(),
                        'transaction_id' => $transaction->id,
                        'goods_id' => $item->goods_id,
                    ]);
                }
            }

            // Update total transaksi
            $transaction->update(['total' => $totalAmount]);

            // Hapus barang dari cart yang dipilih
            Cart::whereIn('id', $request->cart_ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaction created successfully',
                'data' => $transaction->load('details.goods')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
