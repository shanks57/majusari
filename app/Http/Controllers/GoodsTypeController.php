<?php

namespace App\Http\Controllers;

use App\Models\GoodsType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GoodsTypeController extends Controller
{
    public function index()
    {
        $title = 'Jenis Barang';
        $types = GoodsType::all();
        return view('pages.master-types', compact('title','types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenisBarang' => 'required|string|max:255',
            'tambahanBiaya' => 'required|numeric',
        ]);

        $goodsType = new GoodsType();
        $goodsType->id = (string) Str::uuid();
        $goodsType->name = $request->input('jenisBarang');
        $goodsType->additional_cost = $request->input('tambahanBiaya');
        $goodsType->status = true; // or set based on your logic
        $goodsType->slug = Str::slug($goodsType->name);
        $goodsType->save();

        return response()->json(['message' => 'Data successfully saved']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenisBarang' => 'required|string|max:255',
            'tambahanBiaya' => 'required|numeric|min:0',
        ]);

        $goodsType = GoodsType::findOrFail($id);
        $goodsType->update([
            'name' => $request->jenisBarang,
            'additional_cost' => $request->tambahanBiaya,
        ]);

        return response()->json(['success' => true]);
    }

}
