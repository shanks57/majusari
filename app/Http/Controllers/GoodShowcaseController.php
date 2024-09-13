<?php

namespace App\Http\Controllers;

use App\Models\GoldRate;
use App\Models\Goods;
use App\Models\GoodsType;
use App\Models\Merk;
use App\Models\Showcase;
use App\Models\Tray;
use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Str;

class GoodShowcaseController extends Controller
{
    public function index()
    {
        $goodShowcases = Goods::where('availability', 1)
            ->where('safe_status', 0)
            ->get();
        $types = GoodsType::all();;
        $brands = Merk::all();
        $showcases = Showcase::all();

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

        $title = 'Barang';
        $lastKurs = GoldRate::latest('created_at')->first();
        $lastKursPrice = $lastKurs ? $lastKurs->new_price : 0;

        $latestAddedGoods = Goods::latest('created_at')->first();

        return view('pages.goods-showcases', compact('goodShowcases', 'title', 'types', 'brands', 'showcases', 'trays', 'occupiedPositions', 'lastKursPrice', 'latestAddedGoods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
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
                'unit' => $request->unit,
                'code' => $request->name . "" . time(),
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
            session()->flash('success-store', 'Berhasil Menambah Data Barang Etalase');
            return redirect()->route('goods.showcase');
        } catch (\Exception $e) {

            // Redirect back with an error message
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menambah data barang. Silakan coba lagi.']);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
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

            $goodShowcases = Goods::find($showcase->id);

            session()->flash('nameShowcase', $goodShowcases->name);
            session()->flash('imageShowcase', $goodShowcases->image);
            session()->flash('rateShowcase', $goodShowcases->rate);
            session()->flash('sizeShowcase', $goodShowcases->size);
            session()->flash('dateShowcase', $goodShowcases->date_entry);
            session()->flash('showcase', $goodShowcases->tray->showcase->name);
            session()->flash('goodType', $goodShowcases->goodsType->name);
            session()->flash('trayCode', $goodShowcases->tray->code);
            session()->flash('merk', $goodShowcases->merk->name);

            session()->flash('success-store', 'Sukses Update Barang Etalase');
            return redirect()->route('goods.showcase');
        } catch (\Exception $e) {

            // Redirect back with an error message
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data showcase. Silakan coba lagi.']);
        }
    }

    public function moveToSafe($id)
    {
        try {
            $good = Goods::find($id);

            if (!$good) {
                return redirect()->route('goods.showcases')->with('error', 'Item not found');
            }

            $good->safe_status = 1;
            $good->tray_id = null;
            $good->position = null;
            $good->save();

            return redirect()->route('goods.showcase')->with('success', 'Berhasil Memindahkan Data Brankas');
        } catch (\Exception $e) {
            // Redirect back with an error message
            return redirect()->route('goods.showcases')->with('error', 'Terjadi kesalahan saat memindahkan data ke brankas. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        try {
            $goodShowcase = Goods::findOrFail($id);

            $goodShowcase->delete();

            return redirect()->route('goods.showcase')->with('success', 'Berhasil Menghapus Data Barang di Etalase');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Barang tidak ditemukan
            return redirect()->route('goods.showcase')->with('error', 'Barang tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('goods.showcase')->with('error', 'Terjadi kesalahan saat menghapus data barang di etalase. Silakan coba lagi.');
        }
    }

    public function printBarcode($id)
    {
        try {
            $goodShowcase = Goods::findOrFail($id);

            $barcodeGenerator = new \Milon\Barcode\DNS1D();
            $barcodeImage = $barcodeGenerator->getBarcodePNG($goodShowcase->code, 'C128');

            return view('print-page.showcase-print-barcode', [
                'goodShowcase' => $goodShowcase,
                'barcode' => $barcodeImage,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Barang tidak ditemukan
            return redirect()->route('goods.showcase')->with('error', 'Barang tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('goods.showcase')->with('error', 'Terjadi kesalahan saat menghasilkan barcode. Silakan coba lagi.');
        }
    }
}
