<?php

namespace App\Http\Controllers;

use App\Exports\GoodsSafeExport;
use App\Models\GoldRate;
use App\Models\Goods;
use App\Models\GoodsType;
use App\Models\Merk;
use App\Models\Showcase;
use App\Models\Tray;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class GoodSafeController extends Controller
{
    public function index()
    {
        $goodsafes = Goods::where('availability', 1)
            ->where('safe_status', 1)
            ->latest()
            ->get();

        $types = GoodsType::where('status', 1)->get();
        $brands = Merk::where('status', 1)->get();
        $title = 'Brangkas';
        $showcases = Showcase::all();
        $lastKurs = GoldRate::latest('created_at')->first();
        $lastKursPrice = $lastKurs ? $lastKurs->new_price : 0;
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

        return view('pages.goods-safe', compact('goodsafes', 'showcases', 'trays','title', 'brands', 'types', 'occupiedPositions', 'lastKursPrice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:goods,code',
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
        ],[
            'code.unique' => 'Code barang barang sudah digunakan. Silakan pilih code barang barang lain.',
        ]);

        try {
            // Handle the image upload
            $imagePath = $request->file('image')->store('goods_images', 'public');

            // Create a new showcase entry
            $good = Goods::create([
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

            $goodShowcases = Goods::find($good->id);

            session()->flash('nameShowcase', $goodShowcases->name);
            session()->flash('imageShowcase', $goodShowcases->image);
            session()->flash('rateShowcase', $goodShowcases->rate);
            session()->flash('sizeShowcase', $goodShowcases->size);
            session()->flash('dateShowcase', $goodShowcases->date_entry);
            session()->flash('goodType', $goodShowcases->goodsType->name);
            session()->flash('merk', $goodShowcases->merk->name);

            session()->flash('success-store', 'Berhasil Menambah Data Barang Brangkas');
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
            'date_entry' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ],[
            'code.unique' => 'Code barang barang sudah digunakan. Silakan pilih code barang barang lain.',
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
            session()->flash('goodType', $goodShowcases->goodsType->name);
            session()->flash('merk', $goodShowcases->merk->name);

            session()->flash('success-store', 'Sukses Update Barang Brangkas');
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


        $typeId = $request->input('type_id');
        $trayId = $request->input('tray_id');

        $tray = Tray::find($trayId);

        if (!$tray) {
            return redirect()->route('goods.safe')->with('error', 'Tray not found');
        }

        $good->safe_status = 0;
        $good->type_id = $typeId;
        $good->tray_id = $trayId;
        $good->position = $request->input('position');;
        $good->save();

        return redirect()->route('goods.safe')->with('success', 'Berhasil Memindahkan Data Etalase');
    }

    public function destroy($id)
    {
        $goodShowcase = Goods::findOrFail($id);
        $goodShowcase->delete();

        return redirect()->route('goods.safe')->with('success', 'Berhasil Menghapus Data Barang di Brangkas');
    }

    public function printBarcode($id)
    {
        try {
            $goodShowcase = Goods::findOrFail($id);

            $barcodeGenerator = new \Milon\Barcode\DNS1D();
            $barcodeImage = $barcodeGenerator->getBarcodePNG($goodShowcase->code, 'C128');

            return view('print-page.safe-print-barcode', [
                'goodShowcase' => $goodShowcase,
                'barcode' => $barcodeImage,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Barang tidak ditemukan
            return redirect()->route('goods.showcase')->with('error', 'Barang tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->route('goods.showcase')->with('error', 'Terjadi kesalahan saat menghasilkan barcode1. Silakan coba lagi.');
        }
    }

    public function downloadPdf()
    {
        $goodsSafe = Goods::where('availability', 1)
            ->where('safe_status', 1)
            ->get();

        $pdf = PDF::loadView('/pdf-page/goods-safe', compact('goodsSafe'))
        ->setPaper('a4', 'landscape');

        return $pdf->download(now()->format('His').'-Laporan-Data-Barang-Brankas.pdf');
    }

    public function exportExcel()
    {
        $fileName = now()->format('His').'-Laporan-Data-Barang-Brankas.xlsx';
        return Excel::download(new GoodsSafeExport, $fileName);
    }

    public function print()
    {
        $goodsSafe = Goods::where('availability', 1)
            ->where('safe_status', 1)
            ->get();
                      
        return view('/print-page/print-goods-safe', compact('goodsSafe'));
    }
}
