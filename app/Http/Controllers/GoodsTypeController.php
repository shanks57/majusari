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
        $types = GoodsType::orderBy('updated_at', 'desc')->get();
        return view('pages.master-types', compact('title','types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenisBarang' => 'required|string|max:255|unique:goods_types,name',
            'tambahanBiaya' => 'required|numeric|min:0',
        ],[
            'jenisBarang.unique' => 'Jenis barang sudah digunakan. Silakan pilih nama jenis barang lain.',
        ]);

        try {
            $goodsType = new GoodsType();
            $goodsType->id = (string) Str::uuid();
            $goodsType->name = $request->input('jenisBarang');
            $goodsType->additional_cost = $request->input('tambahanBiaya');
            $goodsType->status = true;
            $goodsType->slug = Str::slug($goodsType->name);
            
            $goodsType->save();

            return redirect()->route('master.types')->with('success', 'Berhasil Menambah Data Tipe Barang');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenisBarang' => 'required|string|max:255|unique:goods_types,name,'. $id,
            'tambahanBiaya' => 'required|numeric|min:0',
            'status' => 'boolean',
        ],[
            'jenisBarang.unique' => 'Jenis barang sudah digunakan. Silakan pilih nama jenis barang lain.',
        ]);

        try {
            $goodsType = GoodsType::findOrFail($id);
            
            $goodsType->name = $request->input('jenisBarang');
            $goodsType->additional_cost = $request->input('tambahanBiaya');
            $goodsType->status = $request->boolean('status');
            $goodsType->slug = Str::slug($goodsType->name);

            $goodsType->save();

            return redirect()->route('master.types')->with('success', 'Berhasil Memperbarui Data Tipe Barang');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('master.types')->withErrors(['error' => 'Data Tipe Barang tidak ditemukan.']);
        } catch (\Exception $e) {

            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        try {
            $type = GoodsType::findOrFail($id);

            $type->delete();

            return redirect()->route('master.types')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.']);
        }
    }

}
