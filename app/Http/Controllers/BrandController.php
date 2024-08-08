<?php

namespace App\Http\Controllers;

use App\Models\Merk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Merk::orderBy('updated_at', 'desc')->get();
        $title = 'Merk Barang';
        return view('pages.master-brands', compact('brands', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:merks,name',
            ],[
                'name.unique' => 'Merk sudah tersimpan di database. Silakan pilih merk lain.',
            ]
        );

        try {
            $merk = new Merk();
            $merk->id = (string) Str::uuid();
            $merk->company = $request->input('company');
            $merk->name = $request->input('name');
            $merk->status = true;
            $merk->slug = Str::slug($merk->name);
            
            $merk->save();
            
            session()->flash('success', 'Berhasil Menambah Data Merek Barang');
            return redirect()->route('master-brands');
           
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:merks,name,' . $id,
            'status' => 'boolean',
            ],[
                'name.unique' => 'Merk sudah tersimpan di database. Silakan pilih merk lain.',
            ]
        );

        try {
            $merk = Merk::findOrFail($id);
            
            $merk->company = $request->input('company');
            $merk->name = $request->input('name');
            $merk->status = $request->boolean('status');
            $merk->slug = Str::slug($merk->name);

            $merk->save();
            session()->flash('success', 'Berhasil Memperbarui Data Merek Barang.');
            return redirect()->route('master-brands');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('master-brands')->withErrors(['error' => 'Data Merek Barang tidak ditemukan.']);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        try {
            $type = Merk::findOrFail($id);

            $type->delete();
            session()->flash('success', 'Data berhasil dihapus.');
            return redirect()->route('master-brands');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.']);
        }
    }
}
