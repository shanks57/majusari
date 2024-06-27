<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $transactions = Transaction::with(['customer', 'goods'])->paginate(15);

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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'goods_id' => 'required|exists:goods,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try{
            $transaction = Transaction::create([
            'id' => Str::uuid(),
            'code' => str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'date' => Carbon::now(),
            'customer_id' => $request->customer_id,
            'goods_id' => $request->goods_id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Transaction created successfully',
                'data' => $transaction
            ], 201);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $transaction = Transaction::with(['customer', 'goods'])->findOrFail($id);

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

        $transaction = Transaction::with('goods')
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
                'goods' => [
                    'id' => $transaction->goods->id,
                    'name' => $transaction->goods->name,
                    'size' => $transaction->goods->size,
                    'rate' => $transaction->goods->rate,
                    'ask_price' => $transaction->goods->ask_price,
                    'ask_rate' => $transaction->goods->ask_rate,
                ]
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
        $transaksi = Transaction::with('goods')->get()->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
        });

        $result = [];

        foreach ($transaksi as $date => $transactions) {
            $result[$date] = $transactions->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'code' => $transaction->code,
                    'goods_id' => $transaction->goods->id,
                    'goods_name' => $transaction->goods->name,
                    'goods_size' => $transaction->goods->size,
                    'goods_rate' => $transaction->goods->rate,
                    'goods_ask_price' => $transaction->goods->ask_price,
                    'goods_ask_rate' => $transaction->goods->ask_rate,
                ];
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
    }

    // Search transactions by multiple columns with pagination
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

        $transactions = Transaction::with('goods')
            ->where('code', 'LIKE', "%{$query}%")
            ->orWhereHas('goods', function($q) use ($query) {
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
            $result[$date] = $transactionGroup->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'code' => $transaction->code,
                    'goods_id' => $transaction->goods->id,
                    'goods_name' => $transaction->goods->name,
                    'goods_size' => $transaction->goods->size,
                    'goods_rate' => $transaction->goods->rate,
                    'goods_ask_price' => $transaction->goods->ask_price,
                    'goods_ask_rate' => $transaction->goods->ask_rate,
                ];
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
}
