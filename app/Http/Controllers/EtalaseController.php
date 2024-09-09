<?php

namespace App\Http\Controllers;

use App\Models\GoldRate;
use App\Models\GoodsType;
use Illuminate\Http\Request;
use App\Models\Showcase;
use App\Models\Tray;
use Illuminate\Support\Str;

class EtalaseController extends Controller
{
    public function index()
    {
        $etalases = Showcase::withCount('trays')
        ->with(['trays' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->orderBy('updated_at', 'desc')
        ->get();

        $goodsTypes = GoodsType::all();
        $title = 'Etalase';
        return view('pages.master-showcases', compact('etalases', 'title', 'goodsTypes'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|unique:showcases,code|max:255',
                'name' => 'required|string|max:255|unique:showcases,name',
                'type_id' => 'required|exists:goods_types,id',
                'trays' => 'required|array|min:1',
                'trays.*.codeTray' => 'required|string|max:255',
                'trays.*.capacity' => 'required|integer|min:1',
            ],[
                'code.unique' => 'Kode Etalase sudah digunakan. Silakan pilih kode lain.',
                'name.unique' => 'Nama Etalase sudah digunakan. Silakan pilih nama lain.',
            ]);

            $showcase = Showcase::create([
                'id' => (string) Str::uuid(),
                'code' => $request->code,
                'name' => $request->name,
                'type_id' => $request->type_id,
            ]);

            foreach ($request->trays as $trayData) {

                Tray::create([
                    'id' => (string) Str::uuid(),
                    'showcase_id' => $showcase->id,
                    'code' => $trayData['codeTray'],
                    'capacity' => $trayData['capacity'],
                ]);
            }

            session()->flash('success', 'Showcase dan baki berhasil ditambahkan.');
            return redirect()->route('master.showcase');
            
        } catch (\Illuminate\Database\QueryException $e) {
            session()->flash('error', 'Terjadi kesalahan saat menyimpan ke database. Silakan coba lagi.');
            return redirect()->back()->withInput();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan yang tidak terduga: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function addTrays(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:trays,code',
            'capacity' => 'required|integer|min:1',
        ],[
            'code.unique' => 'Kode baki sudah digunakan. Silakan pilih kode lain.',
        ]);

        try {
            Tray::create([
                'id' => (string) Str::uuid(),
                'showcase_id' => $request->input('showcase_id'),
                'code' => $request->input('code'),
                'capacity' => $request->input('capacity'),
            ]);

            session()->flash('success', 'Baki berhasil ditambahkan.');
            return redirect()->route('master.showcase');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $etalase = Showcase::findOrFail($id);

            $etalase->trays()->delete();
            $etalase->delete();
            session()->flash('success', 'Data berhasil dihapus.');
            return redirect()->route('master.showcase');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus data. Silakan coba lagi.']);
        }
    }

}

