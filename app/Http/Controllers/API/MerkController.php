<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Merk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class MerkController extends Controller
{
    public function index()
    {
        try {
            $merks = Merk::paginate(request()->all);

            return response()->json([
                $merks
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve merk data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            // 'slug' => 'required|string|unique:merks',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $merk = Merk::create([
                'id' => Str::uuid(),
                'company' => $request->company,
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'status' => $request->status ?? true,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Merk created successfully',
                'data' => $merk
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create merk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $merk = Merk::find($id);

            if (!$merk) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Merk not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Merk retrieved successfully',
                'data' => $merk
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve merk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            // 'slug' => 'required|string|unique:merks,slug,'.$id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $merk = Merk::find($id);

            if (!$merk) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Merk not found'
                ], 404);
            }

            $merk->company = $request->company;
            $merk->name = $request->name;
            $merk->slug = Str::slug($request->name);
            $merk->status = $request->status ?? true;
            $merk->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Merk updated successfully',
                'data' => $merk
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update merk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $merk = Merk::find($id);

            if (!$merk) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Merk not found'
                ], 404);
            }

            $merk->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Merk deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete merk',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
