<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;

class GoodsController extends Controller
{
    public function index()
    {
        try {
            $goods = Goods::where('safe_status', false)
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
            'unit' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'size' => 'required|numeric|min:0',
            'dimensions' => 'required|integer',
            'merk_id' => 'required|uuid|exists:merks,id',
            'ask_rate' => 'required|integer',
            'bid_rate' => 'required|integer',
            'ask_price' => 'required|integer',
            'bid_price' => 'required|integer',
            'date_entry' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type_id' => 'required|uuid|exists:goods_types,id',
            'tray_id' => 'required|uuid|exists:trays,id',
            'position' => 'required|integer',
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
                'unit' => $request->unit,
                'code' => $request->name . "" . time(),
                'name' => $request->name,
                'category' => $request->category,
                'color' => $request->color,
                'rate' => $request->rate,
                'size' => $request->size,
                'dimensions' => $request->dimensions,
                'merk_id' => $request->merk_id,
                'ask_rate' => $request->ask_rate,
                'bid_rate' => $request->bid_rate,
                'ask_price' => $request->ask_price,
                'bid_price' => $request->bid_price,
                'image' => $imagePath,
                'type_id' => $request->type_id,
                'tray_id' => $request->tray_id,
                'position' => $request->position,
                'date_entry' => $request->date_entry,
                'safe_status' => false
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
                'unit' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'color' => 'required|string|max:255',
                'rate' => 'required|numeric|min:0',
                'size' => 'required|numeric|min:0',
                'dimensions' => 'required|integer',
                'merk_id' => 'required|uuid|exists:merks,id',
                'ask_rate' => 'required|numeric',
                'bid_rate' => 'required|numeric',
                'ask_price' => 'required|numeric',
                'bid_price' => 'required|numeric',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
                'type_id' => 'required|uuid|exists:goods_types,id',
                'tray_id' => 'nullable',
                'position' => 'nullable',
                'date_entry' => 'required'
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
                'unit' => $request->unit,
                'category' => $request->category,
                'color' => $request->color,
                'rate' => $request->rate,
                'size' => $request->size,
                'dimensions' => $request->dimensions,
                'merk_id' => $request->merk_id,
                'ask_rate' => $request->ask_rate,
                'bid_rate' => $request->bid_rate,
                'ask_price' => $request->ask_price,
                'bid_price' => $request->bid_price,
                'type_id' => $request->type_id,
                'tray_id' => $request->tray_id,
                'position' => $request->position,
                'date_entry' => $request->date_entry,
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
            ->where('safe_status', false)
            ->where('availability', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('rate', 'LIKE', "%{$query}%")
                    ->orWhere('size', 'LIKE', "%{$query}%")
                    ->orWhere('created_at', 'LIKE', "%{$query}%")
                    ->orWhereHas('goodsType', function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%");
                    })
                    ->orWhereHas('merk', function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%");
                    });
            })
            ->paginate();

        return response()->json($goods);
    }

    public function showImage($id)
    {
        try {
            $goods = Goods::find($id);

            if (!$goods) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Goods not found'
                ], 404);
            }

            $imageUrl = $goods->image ? Storage::url($goods->image) : null;

            if (!$imageUrl) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Image not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'image_url' => $imageUrl
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generateBarcode($id)
    {
        try {
            // Cari data goods berdasarkan ID
            $goods = Goods::find($id);

            if (!$goods) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Goods not found'
                ], 404);
            }

            // Generate barcode menggunakan ID
            $barCode = new DNS1D();
            $barcodeImage = $barCode->getBarcodePNG($goods->code, 'C128');
            // $barcodePNG = base64_decode($barcodeImage);

            // Mengembalikan gambar barcode sebagai respons
            return response()->json([
                'status' => 'success',
                'barcode' => $barcodeImage
            ], 200);
        } catch (\Exception $e) {
            // Mengembalikan respons jika terjadi kesalahan
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate barcode',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
