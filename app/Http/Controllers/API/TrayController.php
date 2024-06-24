<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tray;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TrayController extends Controller
{
    public function index()
    {
        try {
            $trays = Tray::all();

            return response()->json([
                'status' => 'success',
                'message' => 'Trays retrieved successfully',
                'data' => $trays
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve trays',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:trays,code',
            'weight' => 'required|integer',
            'capacity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $tray = Tray::create([
                'id' => Str::uuid(),
                'code' => $request->code,
                'weight' => $request->weight,
                'slug' => strtoupper(Str::slug($request->code)),
                'capacity' => $request->capacity,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Tray created successfully',
                'data' => $tray
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create tray',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $tray = Tray::find($id);

            if (!$tray) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tray not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Tray retrieved successfully',
                'data' => $tray
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve tray',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:trays,code,'.$id,
            'weight' => 'required|integer',
            'capacity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $tray = Tray::find($id);

            if (!$tray) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tray not found'
                ], 404);
            }

            $tray->code = $request->code;
            $tray->weight = $request->weight;
            $tray->capacity = $request->capacity;
            $tray->slug = strtoupper(Str::slug($request->code));
            $tray->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Tray updated successfully',
                'data' => $tray
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update tray',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $tray = Tray::find($id);

            if (!$tray) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tray not found'
                ], 404);
            }

            $tray->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Tray deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete tray',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
