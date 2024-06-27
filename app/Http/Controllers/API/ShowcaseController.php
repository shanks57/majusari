<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Showcase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ShowcaseController extends Controller
{
    public function index()
    {
        try {
            $showcases = Showcase::paginate(request()->all);

            return response()->json([
                $showcases
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve showcases',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:showcases,code',
            'name' => 'required|string|max:255',
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
            $showcase = Showcase::create([
                'id' => Str::uuid(),
                'code' => $request->code,
                'name' => $request->name,
                'type_id' => $request->type_id,
                'tray_id' => $request->tray_id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Showcase created successfully',
                'data' => $showcase
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create showcase',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $showcase = Showcase::find($id);

            if (!$showcase) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Showcase not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Showcase retrieved successfully',
                'data' => $showcase
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve showcase',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:showcases,code,'.$id,
            'name' => 'required|string|max:255',
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
            $showcase = Showcase::find($id);

            if (!$showcase) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Showcase not found'
                ], 404);
            }

            $showcase->code = $request->code;
            $showcase->name = $request->name;
            $showcase->type_id = $request->type_id;
            $showcase->tray_id = $request->tray_id;
            $showcase->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Showcase updated successfully',
                'data' => $showcase
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update showcase',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $showcase = Showcase::find($id);

            if (!$showcase) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Showcase not found'
                ], 404);
            }

            $showcase->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Showcase deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete showcase',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Search showcase by code, name, or goods type name
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

        $showcases = Showcase::with('goodsType')
            ->where('code', 'LIKE', "%{$query}%")
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->orWhereHas('goodsType', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->paginate($perPage);

        if ($showcases->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No showcases found'
            ], 404);
        }

        $data = $showcases->map(function($showcase) {
            return [
                'id' => $showcase->id,
                'code' => $showcase->code,
                'name' => $showcase->name,
                'goods_type' => [
                    'id' => $showcase->goodsType->id,
                    'name' => $showcase->goodsType->name,
                ]
            ];
        });

        return response()->json([
            'current_page' => $showcases->currentPage(),
            'data' => $data,
            'first_page_url' => $showcases->url(1),
            'from' => $showcases->firstItem(),
            'last_page' => $showcases->lastPage(),
            'last_page_url' => $showcases->url($showcases->lastPage()),
            'next_page_url' => $showcases->nextPageUrl(),
            'path' => $showcases->path(),
            'per_page' => $showcases->perPage(),
            'prev_page_url' => $showcases->previousPageUrl(),
            'to' => $showcases->lastItem(),
            'total' => $showcases->total()
        ]);
    }
}
