<?php

namespace App\Http\Controllers;

use App\Exports\GoodsShowcaseExport;
use App\Models\GoldRate;
use App\Models\Goods;
use App\Models\GoodsType;
use App\Models\Merk;
use App\Models\Showcase;
use App\Models\Tray;
use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\Laravel\Facades\Image;

class GoodShowcaseController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $request->get('paginate', 10);

        $query = Goods::where('availability', 1)
            ->where('safe_status', 0);

        // Filter berdasarkan input dari form
        if ($request->has('code') && $request->code != '') {
            $query->where('code', 'LIKE', '%' . $request->code . '%');
        }

        if ($request->has('date_entry') && $request->date_entry != '') {
            $query->where('date_entry', 'LIKE', '%' . $request->date_entry . '%');
        }

        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->has('size') && $request->size != '') {
            $query->where('size', 'LIKE', '%' . $request->size . '%');
        }

        if ($request->has('goods_type') && $request->goods_type != '') {
            $query->whereHas('goodsType', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->goods_type . '%');
            });
        }

        if ($request->has('ask_price') && $request->ask_price != '') {
            $query->where('ask_price', $request->ask_price);
        }

        $goodShowcases = $query->latest()
        ->paginate($paginate)
        ->onEachSide(0);

        $types = GoodsType::where('status', 1)->get();
        $brands = Merk::where('status', 1)->get();

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

        $latestAddedGoods = Goods::where('availability', 1)
            ->where('safe_status', 0)
            ->latest('created_at')
            ->first();

        $cardGoodsSummary = Goods::select('rate')
            ->selectRaw('SUM(size) as total_weight')
            ->selectRaw('COUNT(*) as total_items')
            ->where('availability', 1)
            ->where('safe_status', 0)
            ->groupBy('rate')
            ->get();

        $goodsInShowcase = Goods::where('availability', true)
            ->where('safe_status', false)
            ->get();
        $totalItemsInShowcase = $goodsInShowcase->count();
        $totalWeightInShowcase = $goodsInShowcase->sum('size');

        return view('pages.goods-showcases', compact('goodShowcases', 'title', 'types', 'brands', 'showcases', 'trays', 'occupiedPositions', 'lastKursPrice', 'latestAddedGoods', 'cardGoodsSummary', 'totalItemsInShowcase', 'totalWeightInShowcase','paginate'));
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
            'type_id' => 'required|exists:goods_types,id',
            'tray_id' => 'required|exists:trays,id',
            'position' => 'required|string|max:255',
            'date_entry' => 'required|date',
            'camera_image' => 'nullable|image|max:2048', // Maksimal 2MB
            'gallery_image' => 'nullable|image|max:2048', // Maksimal 2MB
        ]);

        try {
            // Handle the image upload
            $image = $request->file('camera_image') ?? $request->file('gallery_image');
            
            if ($image) {
                // Tentukan nama file dengan timestamp dan nama asli file
                $fileName = time() . '_' . $image->getClientOriginalName();

                // Tentukan path penyimpanan
                $filePath = storage_path('app/public/goods_images/' . $fileName);

                // Buat direktori jika belum ada
                if (!file_exists(storage_path('app/public/goods_images'))) {
                    mkdir(storage_path('app/public/goods_images'), 0755, true);
                }

                // Resize dan simpan gambar langsung ke goods_images
                $img = Image::read($image->path());
                $img->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($filePath, 50); // Simpan dengan kualitas kompresi 85

                // Kembalikan path untuk penyimpanan di database
                $publicPath = 'goods_images/' . $fileName;
            }

            $good = new Goods();

            $good->id = (string) Str::uuid();
            $good->unit = $request->unit;
            $good->name = $request->name;
            $good->category = $request->category;
            $good->color = $request->color;
            $good->rate = $request->rate;
            $good->size = $request->size;
            $good->dimensions = $request->dimensions;
            $good->merk_id = $request->merk_id;
            $good->ask_rate = $request->ask_rate;
            $good->bid_rate = $request->bid_rate;
            $good->ask_price = $request->ask_price;
            $good->bid_price = $request->bid_price;
            $good->image = $publicPath;
            $good->type_id = $request->type_id;
            $good->tray_id = $request->tray_id;
            $good->position = $request->position;
            $good->availability = 1;
            $good->safe_status = 0;
            $good->date_entry = $request->date_entry;

            $good->save();
            $good->refresh();

            $good->code = $good->serial_number;
            $good->save();

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

    public function downloadPdf(Request $request)
    {
        // dd($request->all());
        // Sama seperti di index, terapkan filter
        $query = Goods::where('availability', 1)
            ->where('safe_status', 0);

        if ($request->has('code') && $request->code != '') {
            $query->where('code', 'LIKE', '%' . $request->code . '%');
        }

        if ($request->has('date_entry') && $request->date_entry != '') {
            $query->where('date_entry', 'LIKE', '%' . $request->date_entry . '%');
        }

        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->has('size') && $request->size != '') {
            $query->where('size', 'LIKE', '%' . $request->size . '%');
        }

        if ($request->has('goods_type') && $request->goods_type != '') {
            $query->whereHas('goodsType', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->goods_type . '%');
            });
        }

        if ($request->has('ask_price') && $request->ask_price != '') {
            $query->where('ask_price', $request->ask_price);
        }

        // Ambil data berdasarkan filter
        $goodsShowcase = $query->get();
        // dd($goodsShowcase);

        // Generate PDF
        $pdf = PDF::loadView('/pdf-page/goods-showcase', compact('goodsShowcase'))
            ->setPaper('a4', 'landscape');

        // Unduh PDF
        return $pdf->download(now()->format('His') . '-Laporan-Data-Barang-Etalase.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->all();
        $fileName = now()->format('His') . '-Laporan-Data-Barang-Etalase.xlsx';
        return Excel::download(new GoodsShowcaseExport($filters), $fileName);
    }

    public function print(Request $request)
    {
        $query = Goods::where('availability', 1)
            ->where('safe_status', 0);

        if ($request->has('code') && $request->code != '') {
            $query->where('code', 'LIKE', '%' . $request->code . '%');
        }

        if ($request->has('date_entry') && $request->date_entry != '') {
            $query->where('date_entry', 'LIKE', '%' . $request->date_entry . '%');
        }

        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->has('size') && $request->size != '') {
            $query->where('size', 'LIKE', '%' . $request->size . '%');
        }

        if ($request->has('goods_type') && $request->goods_type != '') {
            $query->whereHas('goodsType', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->goods_type . '%');
            });
        }

        if ($request->has('ask_price') && $request->ask_price != '') {
            $query->where('ask_price', $request->ask_price);
        }

        // Ambil data berdasarkan filter
        $goodsShowcase = $query->get();

        return view('/print-page/print-goods-showcase', compact('goodsShowcase'));
    }
}
