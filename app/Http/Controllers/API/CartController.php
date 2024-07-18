<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function getCart($userId)
    {
        $cartItems = Cart::where('user_id', $userId)->with('goods')->get();

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
                    'goods_id' => $cartItem->goods->id,
                    'goods_name' => $cartItem->goods->name,
                    'goods_size' => $cartItem->goods->size,
                    'goods_rate' => $cartItem->goods->rate,
                    'goods_ask_price' => $cartItem->goods->ask_price,
                    'goods_ask_rate' => $cartItem->goods->ask_rate,
                ];
            })
        ]);
    }

    public function add(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|uuid|exists:users,id',
                'goods_id' => 'required|uuid|exists:goods,id'
            ]);

            $goods = Goods::find($request->goods_id);

            if (!$goods || $goods->availability === false) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Goods not available'
                ], 400);
            }

            $cartItem = Cart::create([
                'id' => Str::uuid(),
                'user_id' => $request->user_id,
                'goods_id' => $request->goods_id
            ]);

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
}
