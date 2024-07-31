<?php

namespace App\Http\Controllers;

use App\Models\Goods;
use App\Models\GoodsType;
use App\Models\Merk;
use App\Models\Showcase;
use App\Models\Tray;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GoodSafeController extends Controller
{
    public function index()
    {
        $goodsafes = Goods::where('availability', 1)
            ->where('safe_status', 1)
            ->get();

        $types = GoodsType::all();;
        $brands = Merk::all();
        $title = 'Brangkas';
        $showcases = Showcase::all();
        $trays = Tray::with('goods')->get()->map(function ($tray) {
            $tray->remaining_capacity = $tray->capacity - $tray->goods->count();
            return $tray;
        });

        return view('pages.goods-safe', compact('goodsafes', 'showcases', 'trays','title', 'brands', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
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
            'date_entry' => 'required|date',
        ]);

        try {
            // Handle the image upload
            $imagePath = $request->file('image')->store('goods_images', 'public');

            // Create a new showcase entry
            Goods::create([
                'id' => (string) Str::uuid(),
                'code' => $request->code,
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
                'availability' => 1,
                'safe_status' => 1,
                'date_entry' => $request->date_entry,
            ]);

            session()->flash('success', 'Berhasil Menambah Data Barang Brangkas');
            return redirect()->route('goods.safe');
        } catch (\Exception $e) {

            // Redirect back with an error message
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menambah data barang. Silakan coba lagi.']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'rate' => 'required|numeric',
            'size' => 'required|numeric',
            'dimensions' => 'required|string|max:255',
            'merk_id' => 'required|exists:merks,id',
            'ask_rate' => 'required|numeric',
            'bid_rate' => 'required|numeric',
            'ask_price' => 'required|numeric',
            'bid_price' => 'required|numeric',
            'type_id' => 'required|exists:goods_types,id',
            'date_entry' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $showcase = Goods::findOrFail($id);

            // Update showcase data except the image
            $showcase->update($request->except('image'));

            if ($request->hasFile('image')) {
                // Delete the existing image if exists
                if ($showcase->image) {
                    $existingImagePath = public_path('storage/' . $showcase->image);
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }

                // Upload new image
                $imagePath = $request->file('image')->store('goods_images', 'public');
                $showcase->image = $imagePath;
                $showcase->save();
            }

            session()->flash('success', 'Sukses Update Barang Brangkas');
            return redirect()->route('goods.safe');
        } catch (\Exception $e) {

            // Redirect back with an error message
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data showcase. Silakan coba lagi.']);
        }
    }

    public function moveToShowcase($id, Request $request)
    {
        $good = Goods::find($id);

        if (!$good) {
            return redirect()->route('goods.safe')->with('error', 'Item not found');
        }

        $trayId = $request->input('tray-select');

        $tray = Tray::find($trayId);

        if (!$tray) {
            return redirect()->route('goods.safe')->with('error', 'Tray not found');
        }

        $currentGoodsCount = Goods::where('tray_id', $trayId)->count();
        $remainingCapacity = $tray->capacity - $currentGoodsCount;

        if ($remainingCapacity <= 0) {
            return redirect()->route('goods.safe')->with('error', 'Tray capacity is full');
        }

        $good->safe_status = 0;
        $good->tray_id = $trayId;
        $good->save();

        return redirect()->route('goods.safe')->with('success', 'Berhasil Memindahkan Data Etalase');
    }

    public function destroy($id)
    {
        $goodShowcase = Goods::findOrFail($id);
        $goodShowcase->delete();

        return redirect()->route('goods.safe')->with('success', 'Berhasil Menghapus Data Barang di Brangkas');
    }

}
