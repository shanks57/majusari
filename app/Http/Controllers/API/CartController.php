<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Goods;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{

    public function searchCode($code)
    {
        $good = Goods::where('code', $code)
                    ->where('availability', 1)
                    ->where('safe_status', 0)
                    ->first();

        // Cek apakah barang ditemukan
        if ($good) {
            // Mengembalikan respons JSON dengan data barang
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $good->id,
                    'name' => $good->name,
                    'ask_price' => $good->ask_price,
                    'color' => $good->color,
                    'merk' => $good->merk->name,
                    'rate' => $good->rate,
                    'size' => $good->size,
                    'type' => $good->goodsType->name,
                    'showcase' => $good->tray->showcase->name ?? null,
                    'tray' => $good->tray->code ?? null,
                    'tray_id' => $good->tray->id ?? null,
                    'image' => $good->image,
                ]
            ]);
        } else {
            // Mengembalikan respons JSON jika barang tidak ditemukan
            return response()->json([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan.'
            ], 404);
        }
    }

    public function getCart($userId)
    {
        $cartItems = Cart::where('user_id', $userId)
            ->with('goods')
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No items found in cart'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $cartItems->map(function($cartItem) {
                return [
                    'id' => $cartItem->id,
                    'user_id' => $cartItem->user_id,
                    'goods_id' => $cartItem->goods_id,
                    'new_selling_price' => $cartItem->new_selling_price,
                    'status_price' => $cartItem->status_price,
                    'complaint' => $cartItem->complaint,
                    'tray_id' => $cartItem->tray_id,
                    'created_at' => $cartItem->created_at,
                    // Menyertakan semua kolom dari relasi goods
                    'goods' => $cartItem->goods->toArray(),
                ];
            })
        ]);
    }

    public function add(Request $request)
    {
        try {
            // Validasi input dari request
            $request->validate([
                'user_id' => 'required|uuid|exists:users,id',
                'goods_id' => 'required|uuid|exists:goods,id',
                'new_selling_price' => 'required|numeric|min:1',
            ]);

            $goods = Goods::find($request->goods_id);
            $trayId = $goods->tray_id;
            $newSellingPrice = $request->input('new_selling_price');

            // Cek apakah barang ada dan tersedia
            if (!$goods || $goods->availability === false) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Goods not available'
                ], 400);
            }

            // Tentukan status harga
            $statusPrice = $newSellingPrice < $goods->ask_price ? 0 : 1;

            // Buat item baru dalam cart
            $cartItem = Cart::create([
                'id' => Str::uuid(),
                'user_id' => $request->user_id,
                'goods_id' => $request->goods_id,
                'tray_id' => $trayId,
                'status_price' => $statusPrice,
                'new_selling_price' => $newSellingPrice,
            ]);

            // Update status barang
            $goods->update(['availability' => false]);

            return response()->json([
                'status' => 'success',
                'message' => 'Goods added to cart',
                'data' => $cartItem->load('goods')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add goods to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function remove($cartId)
    {
        $cartItem = Cart::find($cartId);

        if (!$cartItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart item not found'
            ], 404);
        }

        $goods = Goods::find($cartItem->goods_id);

        $cartItem->delete();

        if ($goods) {
            $goods->update(['availability' => true]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Goods removed from cart'
        ]);
    }

    public function updateSellingPrice(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'new_selling_price' => 'required|numeric|min:0',
        ]);

        try {
            // Temukan cart berdasarkan ID
            $cart = Cart::findOrFail($id);

            // Periksa apakah barang (goods) terkait ditemukan
            if (!$cart->goods) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Barang tidak ditemukan untuk cart ini.'
                ], 404);
            }

            // Dapatkan harga ask dari barang
            $askPrice = $cart->goods->ask_price;

            // Update harga jual baru di cart
            $cart->new_selling_price = $request->input('new_selling_price');

            // Update status harga berdasarkan harga jual baru dan harga ask
            if ($cart->new_selling_price >= $askPrice) {
                $cart->status_price = 1;  // Harga sesuai atau lebih tinggi dari ask price
            } else {
                $cart->status_price = 0;  // Harga di bawah ask price
            }

            // Simpan perubahan ke database
            $cart->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Harga jual berhasil diupdate.',
                'data' => $cart
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart tidak ditemukan.'
            ], 404);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengupdate database.',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addComplaint(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'complaint' => 'required|string|max:255',
        ]);

        try {
            // Temukan cart berdasarkan ID
            $cart = Cart::findOrFail($id);

            // Tambahkan keluhan ke cart dan update status harga
            $cart->complaint = $request->input('complaint');
            $cart->status_price = 2; // Misalnya, 2 adalah status untuk keluhan

            // Simpan perubahan ke database
            $cart->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Keluhan berhasil ditambahkan.',
                'data' => $cart
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cart tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


}
