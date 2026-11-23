<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunGame;
use App\Models\PesananJoki;
use App\Models\TransaksiAkun;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    public function update(Request $request, string $type, int $id): JsonResponse
    {
        abort_unless(in_array($type, ['akun', 'joki'], true), 404);

        $validated = $request->validate([
            'status' => 'required|in:pending,proses,menunggu_verifikasi,selesai,batal',
        ]);

        $order = $type === 'akun'
            ? TransaksiAkun::findOrFail($id)
            : PesananJoki::findOrFail($id);

        $statusLama = $order->status;

        $order->update(['status' => $validated['status']]);

        if ($validated['status'] === 'batal' && $statusLama !== 'batal' && $type === 'akun') {
            $order->pembayarans()->where('status', 'pending')->update(['status' => 'batal']);
            $this->kembalikanStok($order);
        }

        $statusRaw = strtolower($order->status);

        $category = match (true) {
            in_array($statusRaw, ['selesai', 'completed', 'paid', 'berhasil']) => 'completed',
            in_array($statusRaw, ['hold', 'on_hold', 'menunggu_verifikasi']) => 'on_hold',
            in_array($statusRaw, ['batal', 'cancel', 'cancelled']) => 'cancelled',
            default => 'on_going',
        };

        return response()->json([
            'status_raw' => $statusRaw,
            'category' => $category,
            'status_label' => match ($category) {
                'completed' => 'Good Delivery',
                'on_hold' => 'On Hold',
                'cancelled' => 'Dibatalkan',
                default => ucfirst($statusRaw),
            },
        ]);
    }

    private function kembalikanStok(TransaksiAkun $transaksi): void
    {
        $akun = AkunGame::whereKey($transaksi->akun_game_id)->lockForUpdate()->first();

        if (! $akun) {
            return;
        }

        if ($akun->multiple_quantity) {
            $akun->update([
                'quantity' => (int) $akun->quantity + 1,
                'status' => 'tersedia',
            ]);
        } else {
            $akun->update(['status' => 'tersedia']);
        }
    }
}
