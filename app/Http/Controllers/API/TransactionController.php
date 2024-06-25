<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Goods;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
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
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|size:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $transaction = Transaction::where('code', $request->code)->with(['customer', 'goods'])->first();

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'transaction not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction retrieved successfully',
            'data' => $transaction
        ]);
    }

    public function getGoodsByBarcode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $good = Goods::find($request->id);

        if (!$good) {
            return response()->json([
                'status' => 'error',
                'message' => 'Goods not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Goods retrieved successfully',
            'data' => $good
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
}
