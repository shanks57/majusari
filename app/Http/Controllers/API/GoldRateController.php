<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GoldRate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GoldRateController extends Controller
{
    public function index()
    {
        try {
             $goldRates = GoldRate::orderBy('created_at', 'desc')->paginate(10);
            return response()->json([
                $goldRates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve gold rates',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'new_price' => 'required|numeric',
        ]);

        try {
            $goldRate = GoldRate::create([
                'id' => Str::uuid(),
                'new_price' => $request->new_price,
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $goldRate
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create gold rate',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

