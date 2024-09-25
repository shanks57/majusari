<?php

namespace App\Http\Controllers;

use App\Models\GoldRate;
use App\Models\Goods;
use App\Models\GoodsType;
use App\Models\Merk;
use App\Models\Showcase;
use App\Models\Tray;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GoodTrayController extends Controller
{
    public function index()
    {
        $showcases = Showcase::get();
        $goodtrays = Tray::with('showcase')
            ->orderBy('created_at', 'asc')
            ->get();

        
        return view('pages.goods-trays', compact('goodtrays', 'showcases'));
    }

    public function find($id)
    {
        $tray = Tray::with('showcase')->find($id);
        if (!$tray) {
            abort(404);
        }
        
        $countGoods = Goods::where('tray_id', $id)
        ->where('availability', 1)
        ->where('safe_status', 0)
        ->count();

        $countWeight = Goods::where('tray_id', $id)
        ->where('availability', 1)
        ->where('safe_status', 0)
        ->get();

        $goods = Goods::where('tray_id', $id)
            ->where('availability', 1)
            ->where('safe_status', 0)
            ->orderBy('position')
            ->get();

        $totalWeight = $goods->sum('size');
        $trayCapacity = $tray->capacity; 

        $trays = Tray::select('id', 'code', 'showcase_id', 'capacity')->get();

        $occupiedPositions = Goods::select('tray_id', 'position')
            ->where('availability', 1)
            ->where('safe_status', 0)
            ->get()
            ->groupBy('tray_id')
            ->map(function ($goods) {
                return $goods->pluck('position')->toArray();
            })
            ->toArray();

            $types = GoodsType::all();
            $showcases = Showcase::all();
            $brands = Merk::where('status', 1)->get();

        $lastKurs = GoldRate::latest('created_at')->first();
        $lastKursPrice = $lastKurs->new_price;

        return view('pages.trays-show', compact('tray', 'goods', 'countGoods', 'totalWeight', 'trayCapacity', 'trays', 'occupiedPositions', 'types', 'showcases', 'brands','lastKursPrice'));
    }

    public function moveToSafe($id)
    {
        try {
            $good = Goods::find($id);

            if (!$good) {
                return redirect()->back()->withErrors(['error' => 'Barang Tidak Ditemukan.']);
            }

            $good->safe_status = 1;
            $good->tray_id = null;
            $good->position = null;
            $good->save();

            return redirect()->back()->with('success', 'Berhasil Memindahkan Data Brankas.');
        } catch (\Exception $e) {
            // Redirect back with an error message
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memindahkan data ke brankas. Silakan coba lagi.']);
            
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'size' => 'required|string|max:255',
            'dimensions' => 'required|numeric|min:0',
            'merk_id' => 'required|exists:merks,id',
            'ask_rate' => 'required|numeric|min:0',
            'bid_rate' => 'required|numeric|min:0',
            'ask_price' => 'required|numeric|min:0',
            'bid_price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type_id' => 'required|exists:goods_types,id',
            'tray_id' => 'required|exists:trays,id',
            'position' => 'required|string|max:255',
            'date_entry' => 'required|date',
        ]);

        try {
            // Handle the image upload
            $imagePath = $request->file('image')->store('goods_images', 'public');

            // Create a new showcase entry
            $good = Goods::create([
                'id' => (string) Str::uuid(),
                'code' => $request->name . "" . time(),
                'unit' => $request->unit,
                'name' => $request->name,
                'category' => $request->category,
                'color' => $request->color,
                'rate' => $request->rate,
                'size' => $request->size,
                'dimensions' => $request->dimensions,
                'merk_id' => $request->merk_id,
                'ask_rate' => $request->ask_rate,
                'bid_rate' => $request->bid_rate,
                'ask_price' => $request->ask_price,
                'bid_price' => $request->bid_price,
                'image' => $imagePath,
                'type_id' => $request->type_id,
                'tray_id' => $request->tray_id,
                'position' => $request->position,
                'availability' => 1,
                'safe_status' => 0,
                'date_entry' => $request->date_entry,
            ]);

            $goodShowcases = Goods::find($good->id);

            session()->flash('nameShowcase', $goodShowcases->name);
            session()->flash('imageShowcase', $goodShowcases->image);
            session()->flash('rateShowcase', $goodShowcases->rate);
            session()->flash('sizeShowcase', $goodShowcases->size);
            session()->flash('dateShowcase', $goodShowcases->date_entry);
            session()->flash('showcase', $goodShowcases->tray->showcase->name);
            session()->flash('goodType', $goodShowcases->goodsType->name);
            session()->flash('trayCode', $goodShowcases->tray->code);
            session()->flash('merk', $goodShowcases->merk->name);
            return redirect()->back()->with('success-store', 'Berhasil Menambah Data Barang Etalase.');
        } catch (\Exception $e) {

            // Redirect back with an error message
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menambah data barang. Silakan coba lagi.']);
        }
    }

    public function updateSlotBaki(Request $request)
    {
        // Validasi input
        $request->validate([
            'capacity' => 'required|numeric|min:1|max:300',
        ]);

        // Ambil data tray berdasarkan ID dari request
        $tray = Tray::find($request->tray_id); // Misal $tray_id dikirim dalam form

        if (!$tray) {
            return back()->withErrors(['error' => 'Tray tidak ditemukan']);
        }

        // Tambahkan kapasitas baru
        $newCapacity = $tray->capacity + $request->capacity;

        // Cek apakah kapasitas melebihi batas maksimum
        if ($newCapacity > 300) {
            return redirect()->back()->with('error', 'Kapasitas melebihi batas maksimum 300 slot.');
        }

        // Update kapasitas baki
        $tray->capacity = $newCapacity;
        $tray->save();

        return redirect()->back()->with('success', 'Kapasitas baki berhasil ditambahkan');
    }

    public function deleteTrayAndGoods($id)
    {
        // Cari tray berdasarkan ID
        $tray = Tray::findOrFail($id);

        // Hapus semua goods yang memiliki tray_id sama, availability = 1, dan safe_status = 0
        Goods::where('tray_id', $id)
            ->where('availability', 1)
            ->where('safe_status', 0)
            ->delete();

        // Hapus tray
        $tray->delete();

        // Redirect atau return response sesuai kebutuhan
        return redirect()->route('/goods/tray')->with('success', 'Tray dan barang terkait berhasil dihapus.');
    }

}
