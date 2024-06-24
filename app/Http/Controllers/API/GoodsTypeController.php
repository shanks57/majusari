<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GoodsType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class GoodsTypeController extends Controller
{
    public function index()
    {
        try {
            $goodsTypes = GoodsType::all();

            return response()->json([
                'status' => 'success',
                'message' => 'Goods types retrieved successfully',
                'data' => $goodsTypes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve goods types',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'additional_cost' => 'integer|nullable',
            'status' => 'boolean|nullable',
            'slug' => 'string|unique:goods_types,slug|',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $goodsType = GoodsType::create([
                'id' => Str::uuid(),
                'name' => $request->name,
                'additional_cost' => $request->additional_cost ?? 0,
                'status' => $request->status ?? true,
                'slug' => $request->slug ?? Str::slug($request->name),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Goods type created successfully',
                'data' => $goodsType
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create goods type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $goodsType = GoodsType::find($id);

            if (!$goodsType) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Goods type not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Goods type retrieved successfully',
                'data' => $goodsType
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve goods type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'additional_cost' => 'integer|nullable',
            'status' => 'boolean|nullable',
            'slug' => 'string|unique:goods_types,slug,'.$id.'|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $goodsType = GoodsType::find($id);

            if (!$goodsType) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Goods type not found'
                ], 404);
            }

            $goodsType->name = $request->name;
            $goodsType->additional_cost = $request->additional_cost ?? 0;
            $goodsType->status = $request->status ?? true;
            $goodsType->slug = $request->slug ?? Str::slug($request->name);
            $goodsType->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Goods type updated successfully',
                'data' => $goodsType
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update goods type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $goodsType = GoodsType::find($id);

            if (!$goodsType) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Goods type not found'
                ], 404);
            }

            $goodsType->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Goods type deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete goods type',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
