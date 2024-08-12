<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            'customer_id' => 'nullable|uuid|exists:users,id',
            'name' => 'required_if:customer_id,null|string|max:255',
            'phone' => 'required_if:customer_id,null|string|max:15',
            'address' => 'required_if:customer_id,null|string|max:255',
            'user_id' => 'required|uuid|exists:users,id',
            'cart_items' => 'required|array',
            'cart_items.*.cart_id' => 'required|uuid|exists:carts,id',
            'cart_items.*.goods_id' => 'required|uuid|exists:goods,id',
            'cart_items.*.harga_jual' => 'required|numeric|min:0',
            'cart_items.*.tray_id' => 'nullable|uuid|exists:trays,id',
            'payment_method' => 'required|string|max:50',
            'date' => 'required|date'
        ]);

        DB::beginTransaction();

        try {
            // Cek jika customer baru atau lama
            $customerId = $request->input('customer_id');

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
                'total' => 0, // Placeholder untuk total
                'payment_method' => $request->input('payment_method'),
                'date' => $request->input('date'),
            ]);

            $totalAmount = 0;
            $cartIds = [];

            foreach ($request->input('cart_items') as $cartItem) {
                // Dapatkan item cart
                $cart = Cart::findOrFail($cartItem['cart_id']);
                $cartIds[] = $cart->id;

                // Buat detail transaksi baru
                TransactionDetail::create([
                    'id' => Str::uuid(),
                    'nota' => str_pad(mt_rand(1, 99999), 8, '0', STR_PAD_LEFT),
                    'transaction_id' => $transaction->id,
                    'goods_id' => $cartItem['goods_id'],
                    'harga_jual' => $cartItem['harga_jual'],
                    'tray_id' => $cartItem['tray_id'],
                ]);

                // Update barang di tabel goods
                Goods::where('id', $cartItem['goods_id'])->update([
                    'position' => null,
                    'tray_id' => null,
                ]);

                $totalAmount += $cartItem['harga_jual'];
            }

            // Update total transaksi
            $transaction->update(['total' => $totalAmount]);

            // Hapus semua barang dari cart (soft delete)
            Cart::whereIn('id', $cartIds)->delete();

            // Komit transaksi
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaction created successfully',
                'data' => $transaction->load('details.goods')
            ], 200);

        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();

            // Catat exception ke file log
            Log::error('Checkout error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function searchNota(Request $request)
    {
        $request->validate([
            'nota' => 'required|string'
        ]);

        $nota = $request->input('nota');
        
        $transaction = TransactionDetail::where('nota', $nota)->with(['goods', 'tray', 'goods.merk', 'goods.goodsType', 'tray.showcase'])->first();

        if ($transaction) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'goods_name' => $transaction->goods->name,
                    'nota' => $transaction->nota,
                    'goods_image' => $transaction->goods->image,
                    'goods_color' => $transaction->goods->color,
                    'goods_merk' => $transaction->goods->merk->name,
                    'goods_rate' => $transaction->goods->rate,
                    'goods_size' => $transaction->goods->size,
                    'goods_type' => $transaction->goods->goodsType->name,
                    'showcase_name' => $transaction->tray->showcase->name,
                    'tray_code' => $transaction->tray->code,
                    'harga_jual' => $transaction->harga_jual
                ]
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode penjualan tidak ditemukan.'
            ], 404);
        }
    }
}
