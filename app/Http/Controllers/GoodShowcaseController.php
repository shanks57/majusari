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
// use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class GoodShowcaseController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $request->get('paginate', 10);
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $query = Goods::where('availability', 1)
            ->where('safe_status', 0);

        // Filter berdasarkan input dari form
        if ($request->filled('code')) {
            $query->where('code', 'LIKE', '%' . $request->code . '%');
        }

        if ($request->filled('date_entry')) {
            $query->where('date_entry', 'LIKE', '%' . $request->date_entry . '%');
        }

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->filled('size')) {
            $query->where('size', 'LIKE', '%' . $request->size . '%');
        }

        if ($request->filled('rate')) {
            $query->where('rate', 'LIKE', '%' . $request->rate . '%');
        }

        if ($request->filled('goods_type')) {
            $query->whereHas('goodsType', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->goods_type . '%');
            });
        }

        if ($request->filled('ask_price')) {
            $query->where('ask_price', $request->ask_price);
        }

        // Sorting berdasarkan request
        if (in_array($sortField, ['code', 'date_entry', 'name', 'size', 'rate', 'ask_price'])) {
            $query->orderBy($sortField, $sortDirection);
        } elseif ($sortField === 'type_id') {
            $query->join('goods_types', 'goods.type_id', '=', 'goods_types.id')
                ->orderBy('goods_types.name', $sortDirection)
                ->select('goods.*'); // Pastikan memilih kolom dari tabel goods agar tidak ada konflik
        } else {
            $query->latest();
        }

        $goodShowcases = $query->paginate($paginate)->onEachSide(0);

        $types = GoodsType::where('status', 1)->get();
        $brands = Merk::where('status', 1)->get();
        $showcases = Showcase::all();
        $trays = Tray::select('id', 'code', 'showcase_id', 'capacity')->get();
        $goldRate = GoldRate::latest()->first()->new_price;

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

        return view('pages.goods-showcases', compact(
            'goodShowcases', 'title', 'types', 'brands', 'showcases', 'trays', 
            'occupiedPositions', 'lastKursPrice', 'latestAddedGoods', 
            'cardGoodsSummary', 'totalItemsInShowcase', 'totalWeightInShowcase', 
            'paginate','goldRate'
        ));
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
            'kurs_emas' => 'required|numeric|min:0',
            'ask_price' => 'required|numeric|min:0',
            'bid_price' => 'required|numeric|min:0',
            'type_id' => 'required|exists:goods_types,id',
            'tray_id' => 'required|exists:trays,id',
            'position' => 'required|string|max:255',
            'date_entry' => 'required|date',
        ]);

        try {
                // Handle the image upload
                $image = $request->file('camera_image') ?? $request->file('gallery_image');
                
                if ($uploadedFile) {
                    // === MODE FILE ===
                    $request->validate([
                        'camera_image' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:4096',
                        'gallery_image' => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:4096',
                    ]);

                    $ext = $uploadedFile->getClientOriginalExtension() ?: 'jpg';
                    $fileName = now()->timestamp.'_'.Str::random(8).'.'.$ext;

                    $img = Image::read($uploadedFile->getRealPath());
                } else {
                    // === MODE BASE64 ===
                    $b64 = $request->input('camera_image') ?? $request->input('gallery_image');

                    // Validasi dasar: harus data URL gambar
                    Validator::make(
                        ['img' => $b64],
                        ['img' => ['required','string','regex:/^data:image\/(png|jpe?g|webp|gif);base64,/i']]
                    )->validate();

                    // Batasi ukuran ~4 MB (file rule pakai KB; base64 kita hitung manual)
                    // Perkiraan ukuran byte dari base64: floor(len * 3 / 4) - padding
                    [$meta, $data] = explode(',', $b64, 2);
                    $len = strlen($data);
                    $padding = substr_count(substr($data, -2), '=');
                    $bytes = intdiv($len * 3, 4) - $padding;
                    if ($bytes > 4 * 1024 * 1024) {
                        return back()->withErrors(['camera_image' => 'Ukuran gambar melebihi 4MB.']);
                    }

                    $binary = base64_decode($data, true);
                    if ($binary === false) {
                        return back()->withErrors(['camera_image' => 'Data gambar tidak valid.']);
                    }

                    $ext = str_contains($meta, 'image/png') ? 'png' :
                        (str_contains($meta, 'image/webp') ? 'webp' :
                        (str_contains($meta, 'image/gif') ? 'gif' : 'jpg'));

                    $fileName = now()->timestamp.'_'.Str::random(8).'.'.$ext;

                    $img = Image::read($binary);
                }

                // 2) Proses & simpan
                // orient() untuk mengikuti EXIF (foto HP sering sideways)
                $img->orient();

                // Resize 400px sisi terpanjang, tetap proporsi (tanpa pecah)
                $img->resize(400, 400, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });

                // Simpan ke disk 'public' agar bisa diakses via /storage/...
                // Kualitas 50 (0-100) agar hemat ukuran
                $path = 'goods_images/'.$fileName;
                Storage::disk('public')->put($path, (string) $img->encode());

                // $publicPath simpan ke DB
                $publicPath = $path;

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
            $good->kurs_emas = $request->kurs_emas;
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
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

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada item yang dipilih.']);
        }

        Goods::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => 'Item berhasil dihapus.']);
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

        if ($request->has('rate') && $request->rate != '') {
            $query->where('rate', 'LIKE', '%' . $request->rate . '%');
        }

        if ($request->has('goods_type') && $request->goods_type != '') {
            $query->whereHas('goodsType', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->goods_type . '%');
            });
        }

        if ($request->has('ask_price') && $request->ask_price != '') {
            $query->where('ask_price', $request->ask_price);
        }

        // Sorting berdasarkan request
        $sortBy = $request->get('order_by', 'date_entry'); // Default sort by 'date_entry'
        $sortDirection = $request->get('sort', 'desc'); // Default descending

        if (in_array($sortBy, ['code', 'date_entry', 'name', 'size', 'rate', 'ask_price']) && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Ambil data berdasarkan filter
        $goodsShowcase = $query->get();

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

        if ($request->has('rate') && $request->rate != '') {
            $query->where('rate', 'LIKE', '%' . $request->rate . '%');
        }

        if ($request->has('goods_type') && $request->goods_type != '') {
            $query->whereHas('goodsType', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->goods_type . '%');
            });
        }

        if ($request->has('ask_price') && $request->ask_price != '') {
            $query->where('ask_price', $request->ask_price);
        }

        $sortBy = request()->get('order_by', 'date_entry'); // Default sort by 'date_entry'
        $sortDirection = request()->get('sort', 'desc'); // Default descending

        if (in_array($sortBy, ['code', 'date_entry', 'name', 'size', 'rate', 'ask_price']) && in_array($sortDirection, ['asc', 'desc'])) {
            $query->orderBy($sortBy, $sortDirection);
        }

        // Ambil data berdasarkan filter
        $goodsShowcase = $query->get();

        return view('/print-page/print-goods-showcase', compact('goodsShowcase'));
    }
}
