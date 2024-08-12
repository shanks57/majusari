<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotification()
    {
        try {
            // Ambil notifikasi dari cart dengan keluhan, termasuk yang sudah dihapus
            $notifications = Cart::withTrashed()
                ->whereNotNull('complaint')
                ->orderBy('updated_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'title' => 'Pemberitahuan',
                'data' => $notifications->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'user_id' => $notification->user_id,
                        'goods_id' => $notification->goods_id,
                        'new_selling_price' => $notification->new_selling_price,
                        'status_price' => $notification->status_price,
                        'complaint' => $notification->complaint,
                        'tray_id' => $notification->tray_id,
                        'created_at' => $notification->created_at,
                        'updated_at' => $notification->updated_at,
                    ];
                })
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyPriceStatus($notifId, Request $request)
    {
        // Validasi input
        $request->validate([
            'action' => 'required|string|in:reject,agree',
        ]);

        try {
            // Temukan notifikasi berdasarkan ID
            $notification = Cart::findOrFail($notifId);

            // Set status_price sesuai dengan tindakan yang dipilih
            if ($request->action === 'reject') {
                $notification->status_price = 0; // Misalnya, 0 untuk penolakan
                $message = 'Harga yang diajukan ditolak.';
            } else if ($request->action === 'agree') {
                $notification->status_price = 1; // Misalnya, 1 untuk persetujuan
                $message = 'Harga berhasil disetujui.';
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tindakan tidak valid.'
                ], 400);
            }

            // Simpan perubahan ke database
            $notification->save();

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $notification
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Notifikasi tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

}
