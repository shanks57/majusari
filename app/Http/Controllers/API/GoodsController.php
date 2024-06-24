<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Goods;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class GoodsController extends Controller
{
    public function index()
    {
        try {
            $goods = Goods::paginate(request()->all);

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
            'entry_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type_id' => 'required|uuid|exists:goods_types,id',
            'tray_id' => 'required|uuid|exists:trays,id',
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
                'entry_date' => $request->entry_date,
                'image' => $imagePath,
                'type_id' => $request->type_id,
                'tray_id' => $request->tray_id,
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
            'entry_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type_id' => 'required|uuid|exists:goods_types,id',
            'tray_id' => 'required|uuid|exists:trays,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $goods = Goods::find($id);

            if (!$goods) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'goods not found'
                ], 404);
            }

            $imagePath = $goods->image;
            if ($request->hasFile('image')) {
                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('goods_images', 'public');
            }

            $goods->name = $request->name;
            $goods->category = $request->category;
            $goods->color = $request->color;
            $goods->rate = $request->rate;
            $goods->size = $request->size;
            $goods->merk_id = $request->merk_id;
            $goods->ask_rate = $request->ask_rate;
            $goods->bid_rate = $request->bid_rate;
            $goods->ask_price = $request->ask_price;
            $goods->bid_price = $request->bid_price;
            $goods->entry_date = $request->entry_date;
            $goods->image = $imagePath;
            $goods->type_id = $request->type_id;
            $goods->tray_id = $request->tray_id;
            $goods->save();

            return response()->json([
                'status' => 'success',
                'message' => 'goods updated successfully',
                'data' => $goods
            ], 200);
        } catch (\Exception $e) {
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
}
