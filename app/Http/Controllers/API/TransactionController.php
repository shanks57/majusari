<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Goods;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $transactions = Transaction::with(['details.goods', 'customer'])->paginate(15);

            return response()->json([
                $transactions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $transaction = Transaction::with(['details.goods', 'customer'])->findOrFail($id);

            if (!$transaction) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'transaction retrieved successfully',
                'data' => $transaction
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getByCode(Request $request)
    {
        $code = $request->query('nota');
        
        if (!$code) {
            return response()->json([
                'status' => 'error',
                'message' => 'Code parameter is required'
            ], 400);
        }

        $transaction = Transaction::with('details.goods')
            ->where('code', $code)
            ->first();

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $transaction->id,
                'code' => $transaction->code,
                'date' => $transaction->date,
                'customer_id' => $transaction->customer_id,
                'goods' => $transaction->details->map(function($detail) {
                    return [
                        'id' => $detail->goods->id,
                        'name' => $detail->goods->name,
                        'size' => $detail->goods->size,
                        'rate' => $detail->goods->rate,
                        'ask_price' => $detail->goods->ask_price,
                        'ask_rate' => $detail->goods->ask_rate,
                    ];
                })
            ]
        ]);
    }

    public function getGoodsByBarcode(Request $request)
    {
        $id = $request->query('barcode');

        if (!$id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Code parameter is required'
            ], 400);
        }

        $goods = Goods::where('id', $id)->first();

        if (!$goods) {
            return response()->json([
                'status' => 'error',
                'message' => 'Goods not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $goods->id,
                'code' => $goods->code,
                'name' => $goods->name,
                'size' => $goods->size,
                'rate' => $goods->rate,
                'ask_price' => $goods->ask_price,
                'ask_rate' => $goods->ask_rate,
            ]
        ]);

    }

    // Get all transaksi with goods names grouped by date
    public function indexWithGoodsGroupedByDate()
    {
        try {
            $transaksi = Transaction::with('details.goods')->get()->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

            $result = [];

            foreach ($transaksi as $date => $transactions) {
            $result[$date] = $transactions->flatMap(function ($transaction) {
                return $transaction->details->map(function ($detail) {
                    return [
                        'goods_id' => $detail->goods->id,
                        'goods_name' => $detail->goods->name,
                        'goods_size' => $detail->goods->size,
                        'goods_rate' => $detail->goods->rate,
                        'goods_ask_price' => $detail->goods->ask_price,
                        'goods_ask_rate' => $detail->goods->ask_rate,
                    ];
                });
            });
        }

            $perPage = 15;
            $currentPage = request()->query('page', 1);
            $pagedData = array_slice($result, ($currentPage - 1) * $perPage, $perPage, true);
            $paginatedResult = new \Illuminate\Pagination\LengthAwarePaginator(
                $pagedData,
                count($result),
                $perPage,
                $currentPage
            );
            $paginatedResult->setPath(URL::full());

            return response()->json($paginatedResult);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }   

    public function search(Request $request)
    {
        $query = $request->query('query');
        $perPage = $request->query('per_page', 15); // Default 15 items per page

        if (!$query) {
            return response()->json([
                'status' => 'error',
                'message' => 'Query parameter is required'
            ], 400);
        }

        $transactions = Transaction::with('details.goods')
            ->where('code', 'LIKE', "%{$query}%")
            ->orWhereHas('details.goods', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('size', 'LIKE', "%{$query}%")
                ->orWhere('rate', 'LIKE', "%{$query}%")
                ->orWhere('ask_price', 'LIKE', "%{$query}%")
                ->orWhere('ask_rate', 'LIKE', "%{$query}%");
            })
            ->get()
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
            });

        if ($transactions->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No transactions found'
            ], 404);
        }

        $result = [];

        foreach ($transactions as $date => $transactionGroup) {
            $result[$date] = $transactionGroup->flatMap(function ($transaction) {
                return $transaction->details->map(function ($detail) {
                    return [
                        'goods_id' => $detail->goods->id,
                        'goods_name' => $detail->goods->name,
                        'goods_size' => $detail->goods->size,
                        'goods_rate' => $detail->goods->rate,
                        'goods_ask_price' => $detail->goods->ask_price,
                        'goods_ask_rate' => $detail->goods->ask_rate,
                    ];
                });
            });
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagedData = array_slice($result, ($currentPage - 1) * $perPage, $perPage, true);
        $paginatedResult = new LengthAwarePaginator(
            $pagedData,
            count($result),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return response()->json($paginatedResult);
    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|uuid|exists:users,id',
            'name' => 'required_if:customer_id,null|string|max:255',
            'phone' => 'required_if:customer_id,null|string|max:15',
            'address' => 'required_if:customer_id,null|string|max:255',
            'user_id' => 'required|uuid|exists:users,id',
            'cart_items' => 'required|array',
            'cart_items.*.cart_id' => 'required|uuid|exists:carts,id',
            'cart_items.*.goods_id' => 'required|uuid|exists:goods,id',
            'cart_items.*.harga_jual' => 'required|numeric|min:0',
            'cart_items.*.tray_id' => 'nullable|uuid|exists:trays,id',
            'payment_method' => 'required|string|max:50',
            'date' => 'required|date'
        ]);

        DB::beginTransaction();

        try {
            // Cek jika customer baru atau lama
            $customerId = $request->input('customer_id');

            if (!$customerId) {
                // Tambah pelanggan baru jika diperlukan
                $customer = Customer::create([
                    'id' => Str::uuid(),
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                ]);
                $customerId = $customer->id;
            }

            // Buat entri transaksi baru
            $transaction = Transaction::create([
                'id' => Str::uuid(),
                'code' => str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'user_id' => $request->input('user_id'),
                'customer_id' => $customerId,
                'total' => 0, // Placeholder untuk total
                'payment_method' => $request->input('payment_method'),
                'date' => $request->input('date'),
            ]);

            $totalAmount = 0;
            $cartIds = [];

            foreach ($request->input('cart_items') as $cartItem) {
                // Dapatkan item cart
                $cart = Cart::findOrFail($cartItem['cart_id']);
                $cartIds[] = $cart->id;

                // Buat detail transaksi baru
                TransactionDetail::create([
                    'id' => Str::uuid(),
                    'nota' => str_pad(mt_rand(1, 99999), 8, '0', STR_PAD_LEFT),
                    'transaction_id' => $transaction->id,
                    'goods_id' => $cartItem['goods_id'],
                    'harga_jual' => $cartItem['harga_jual'],
                    'tray_id' => $cartItem['tray_id'],
                ]);

                // Update barang di tabel goods
                Goods::where('id', $cartItem['goods_id'])->update([
                    'position' => null,
                    'tray_id' => null,
                ]);

                $totalAmount += $cartItem['harga_jual'];
            }

            // Update total transaksi
            $transaction->update(['total' => $totalAmount]);

            // Hapus semua barang dari cart (soft delete)
            Cart::whereIn('id', $cartIds)->delete();

            // Komit transaksi
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaction created successfully',
                'data' => $transaction->load('details.goods')
            ], 200);

        } catch (\Exception $e) {
            // Rollback jika terjadi error
            DB::rollBack();

            // Catat exception ke file log
            Log::error('Checkout error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
        ]);

        $startDate = $request->input('date_start');
        $endDate = $request->input('date_end');

        // Ambil data transaksi beserta detailnya
        $sales = Transaction::with('details.goods.goodsType', 'details.goods.merk')
            ->when($startDate, function ($query) use ($startDate) {
                $query->where('date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->where('date', '<=', $endDate);
            })
            ->get();

        // Format data untuk ekspor
        $data = $sales->map(function ($sale) {
            $details = $sale->details;

            return [
                'nota' => $sale->code,
                'name' => $details->pluck('goods.name')->implode(', '),
                'category' => $details->pluck('goods.category')->implode(', '),
                'unit' => $details->pluck('goods.unit')->implode(', '),
                'type' => $details->pluck('goods.goodsType.name')->implode(', '),
                'color' => $details->pluck('goods.color')->implode(', '),
                'rate' => $details->pluck('goods.rate')->map(fn($rate) => number_format($rate, 0) . '%')->implode(', '),
                'size' => $details->pluck('goods.size')->map(fn($size) => number_format($size, 2) . 'gr')->implode(', '),
                'merk' => $details->pluck('goods.merk.name')->implode(', '),
                'ask_price' => $details->pluck('goods.ask_price')->implode(', '),
                'ask_rate' => $details->pluck('goods.ask_rate')->map(fn($ask_rate) => number_format($ask_rate, 0) . '%')->implode(', '),
                'bid_price' => $details->pluck('goods.bid_price')->implode(', '),
                'bid_rate' => $details->pluck('goods.bid_rate')->map(fn($bid_rate) => number_format($bid_rate, 0) . '%')->implode(', '),
                'harga_jual' => $details->pluck('harga_jual')->implode(', '),
                'date' => Carbon::parse($sale->date)->format('d/m/Y'),
            ];
        });

        // Gunakan export untuk menghasilkan file Excel
        $export = new SalesExport($data);
        $file = \Maatwebsite\Excel\Facades\Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);

        // Tambahkan elemen dinamis ke nama file
        $timestamp = now()->format('Y-m-d_H-i-s'); // Format tanggal dan waktu
        $filename = "Laporan-penjualan_{$timestamp}.xlsx";

        // Kustomisasi header response
        return response($file, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => (new ResponseHeaderBag())->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $filename
            ),
            'Cache-Control' => 'no-store, no-cache',
            'Pragma' => 'no-cache',
        ]);
    }

    public function exportPDF(Request $request)
    {
        $request->validate([
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
        ]);
        // Validasi parameter filter tanggal
        $request->validate([
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date|after_or_equal:date_start',
        ]);

        // Ambil parameter filter tanggal dari request
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        // Buat query dasar
        $query = Transaction::query();

        // Tambahkan relasi detail barang
        $query->with('details.goods');

        // Tambahkan filter tanggal jika parameter ada
        if ($dateStart) {
            $query->whereDate('created_at', '>=', Carbon::parse($dateStart));
        }

        if ($dateEnd) {
            $query->whereDate('created_at', '<=', Carbon::parse($dateEnd));
        }

        // Eksekusi query untuk mendapatkan data
        $sales = $query->get();

        // Buat PDF menggunakan dompdf
        $pdf = PDF::loadView('pdf-page.sales-report', ['sales' => $sales])
            ->setPaper('a4', 'landscape');

        // Tambahkan elemen dinamis ke nama file
        $timestamp = now()->format('Y-m-d_H-i-s'); // Format tanggal dan waktu
        $filename = "Laporan-Penjualan_{$timestamp}.pdf";

        // Kustomisasi header response
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => (new ResponseHeaderBag())->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $filename
            ),
            'Cache-Control' => 'no-store, no-cache',
            'Pragma' => 'no-cache',
        ]);
    }

    public function printNota($id)
    {
        try {
            // Ambil transaksi berdasarkan ID
            $transaction = Transaction::findOrFail($id);

            // Ambil detail transaksi
            $sales = TransactionDetail::with('goods')->where('transaction_id', $id)->get();

            // Render PDF menggunakan view yang ada
            $pdf = PDF::loadView('print-page.print-invoice-api', [
                'sales' => $sales,
                'transaction' => $transaction
            ])->setPaper('a4', 'portrait')->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultPaperSize' => 'a4',
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_left' => 0,
                'margin_right' => 0,
                'css_float' => true,
            ]); // Set ukuran dan orientasi kertas

            // Tambahkan elemen dinamis untuk nama file
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "Nota-Transaksi_{$timestamp}.pdf";

            // Return response dengan header
            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => (new ResponseHeaderBag())->makeDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    $filename
                ),
                'Cache-Control' => 'no-store, no-cache',
                'Pragma' => 'no-cache',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika transaksi tidak ditemukan
            return response()->json(['error' => 'Transaction not found'], 404);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan umum
            return response()->json(['error' => 'An error occurred while generating the PDF'], 500);
        }
    }

    public function searchNota(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nota' => 'required|string'
        ]);

        $code = $validated['nota'];

        // Temukan transaksi berdasarkan kode
        $transaction = Transaction::with(['details.goods', 'details.tray.showcase', 'details.goods.goodsType', 'details.goods.merk'])
            ->where('code', $code)
            ->first();

        if ($transaction) {
            // Siapkan array detail barang
            $goodsDetails = [];
            foreach ($transaction->details as $detail) {
                $goodsDetails[] = [
                    'id' => $detail->goods->id,
                    'name' => $detail->goods->name,
                    'image' => $detail->goods->image,
                    'color' => $detail->goods->color,
                    'merk' => $detail->goods->merk->name ?? null,
                    'rate' => $detail->goods->rate,
                    'size' => $detail->goods->size,
                    'type' => $detail->goods->goodsType->name ?? null,
                    'showcase' => $detail->tray->showcase->name ?? null,
                    'tray' => $detail->tray->code ?? null,
                    'harga_jual' => $detail->harga_jual,
                ];
            }

            // Return data dalam format JSON
            return response()->json([
                'success' => true,
                'message' => 'Transaksi ditemukan.',
                'transaction' => [
                    'id' => $transaction->id,
                    'code' => $transaction->code,
                    'date' => $transaction->date,
                    'customer_name' => $transaction->customer->name,
                    'customer_address' => $transaction->customer->address,
                    'employement' => $transaction->user->name,
                ],
                'goodsDetails' => $goodsDetails,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kode penjualan tidak ditemukan.'
            ], 404);
        }
    }

    public function filterSalesByDate(Request $request)
    {
        // Buat query dasar
        $query = Transaction::with('details.goods')->orderBy('created_at', 'desc');

        // Ambil input tanggal dari request
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');

        // Filter berdasarkan tanggal mulai jika tersedia
        if ($dateStart) {
            $query->whereDate('created_at', '>=', Carbon::parse($dateStart));
        }

        // Filter berdasarkan tanggal akhir jika tersedia
        if ($dateEnd) {
            $query->whereDate('created_at', '<=', Carbon::parse($dateEnd));
        }

        // Eksekusi query untuk mendapatkan data
        $sales = $query->get();

        // Hitung total item di semua transaksi
        $totalItems = $sales->sum(function ($sale) {
            return $sale->details->count();
        });

        // Kembalikan data dalam format JSON
        return response()->json([
            'success' => true,
            'message' => 'Data transaksi berhasil difilter.',
            'sales' => $sales,
            'total_items' => $totalItems,
        ], 200);
    }

}
