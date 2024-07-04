<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SafeStorageController extends Controller
{
        public function index()
    {
        try {
            $goods = Goods::where('safe_status', true)
            ->where('availability', true)
            ->paginate();

            return response()->json([
                $goods
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve goods',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'rate' => 'required|integer',
            'size' => 'required|string|max:255',
            'merk_id' => 'required|uuid|exists:merks,id',
            'ask_rate' => 'required|integer',
            'bid_rate' => 'required|integer',
            'ask_price' => 'required|integer',
            'bid_price' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type_id' => 'required|uuid|exists:goods_types,id',
            'tray_id' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('goods_images', 'public');
            }

            $goods = Goods::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'category' => $request->category,
                'color' => $request->color,
                'rate' => $request->rate,
                'size' => $request->size,
                'merk_id' => $request->merk_id,
                'ask_rate' => $request->ask_rate,
                'bid_rate' => $request->bid_rate,
                'ask_price' => $request->ask_price,
                'bid_price' => $request->bid_price,
                'image' => $imagePath,
                'type_id' => $request->type_id,
                'tray_id' => null,
                'safe_status' => true
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'goods created successfully',
                'data' => $goods
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create goods',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $goods = goods::find($id);

            if (!$goods) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'goods not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'goods retrieved successfully',
                'data' => $goods
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve goods',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi request
            $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'color' => 'required|string|max:255',
                'rate' => 'required|numeric',
                'size' => 'required|numeric',
                'merk_id' => 'required|uuid|exists:merks,id',
                'ask_rate' => 'required|numeric',
                'bid_rate' => 'required|numeric',
                'ask_price' => 'required|numeric',
                'bid_price' => 'required|numeric',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                'type_id' => 'required|uuid|exists:goods_types,id',
                'tray_id' => 'nullable',
                'safe_status' => 'required|boolean'
            ]);

            // Cari data goods berdasarkan ID
            $goods = Goods::find($id);

            if (!$goods) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Goods not found'
                ], 404);
            }

            // Update fields menggunakan mass assignment
            $goods->update([
                'name' => $request->name,
                'category' => $request->category,
                'color' => $request->color,
                'rate' => $request->rate,
                'size' => $request->size,
                'merk_id' => $request->merk_id,
                'ask_rate' => $request->ask_rate,
                'bid_rate' => $request->bid_rate,
                'ask_price' => $request->ask_price,
                'bid_price' => $request->bid_price,
                'type_id' => $request->type_id,
                'tray_id' => $request->tray_id,
                'safe_status' => $request->safe_status,
            ]);

            // Proses untuk pengelolaan gambar jika ada perubahan
            $imagePath = $goods->image;
            if ($request->hasFile('image')) {
                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('goods_images', 'public');
                $goods->update(['image' => $imagePath]);
            }

            // Mengembalikan respons berhasil
            return response()->json([
                'status' => 'success',
                'message' => 'Goods updated successfully',
                'data' => $goods
            ], 200);
        } catch (\Exception $e) {
            // Mengembalikan respons jika terjadi kesalahan
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update goods',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $goods = goods::find($id);

            if (!$goods) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'goods not found'
                ], 404);
            }

            if ($goods->image) {
                Storage::disk('public')->delete($goods->image);
            }

            $goods->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'goods deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete goods',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        $query = $request->query('query');

        if (!$query) {
            return response()->json([
                'status' => 'error',
                'message' => 'Query parameter is required'
            ], 400);
        }

        $goods = Goods::with(['goodsType', 'merk'])
            ->where('safe_status', true)
            ->where('availability', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('rate', 'LIKE', "%{$query}%")
                    ->orWhere('size', 'LIKE', "%{$query}%")
                    ->orWhere('created_at', 'LIKE', "%{$query}%")
                    ->orWhereHas('goodsType', function($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%");
                    })
                    ->orWhereHas('merk', function($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%");
                    });
            })
            ->paginate();

        return response()->json($goods);
    }
}
