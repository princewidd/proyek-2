<?php

namespace App\Services;

use App\Models\AkunGame;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public static function aktif(): bool
    {
        return (bool) config('midtrans.server_key') && (bool) config('midtrans.client_key');
    }

    public static function boot(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }

    public static function snapToken(Pembayaran $pembayaran): ?string
    {
        if (! self::aktif()) {
            return null;
        }

        self::boot();

        $user = $pembayaran->transaksiAkun?->user ?? $pembayaran->pesananJoki?->user;

        $namaItem = $pembayaran->transaksiAkun?->akunGame?->judul
            ?? (($pembayaran->pesananJoki?->paketJoki?->nama_paket ?? '').' '.($pembayaran->pesananJoki?->paketJoki?->game ?? ''));
        $namaItem = trim($namaItem) ?: 'Pembayaran Pesanan';
        $namaItem = mb_substr($namaItem, 0, 50);

        $params = [
            'transaction_details' => [
                'order_id' => $pembayaran->kode_pembayaran,
                'gross_amount' => (int) $pembayaran->jumlah,
            ],
            'item_details' => [
                [
                    'id' => (string) ($pembayaran->transaksi_akun_id ?? $pembayaran->pesanan_joki_id),
                    'price' => (int) $pembayaran->jumlah,
                    'quantity' => 1,
                    'name' => $namaItem,
                ],
            ],
            'customer_details' => [
                'first_name' => $user?->name ?? 'Pelanggan',
                'email' => $user?->email ?? 'pelanggan@zeroshop.test',
            ],
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (\Throwable $e) {
            Log::error('Midtrans snap token gagal: '.$e->getMessage());

            return null;
        }
    }

    public static function sinkronStatus(Pembayaran $pembayaran): Pembayaran
    {
        if (! self::aktif() || ! $pembayaran->midtrans_order_id) {
            return $pembayaran;
        }

        self::boot();

        try {
            $status = Transaction::status($pembayaran->midtrans_order_id);
            $status = is_array($status) ? (object) $status : $status;

            self::terapkanStatus(
                $pembayaran,
                $status->transaction_status ?? null,
                $status->fraud_status ?? null
            );

            return $pembayaran;
        } catch (\Throwable $e) {
            return $pembayaran;
        }
    }

    public static function terapkanStatus(Pembayaran $pembayaran, ?string $transactionStatus, ?string $fraudStatus = null): void
    {
        if ($transactionStatus === null) {
            return;
        }

        $statusLama = $pembayaran->status;

        $pembayaran->midtrans_status = $transactionStatus;

        if ($transactionStatus === 'settlement'
            || ($transactionStatus === 'capture' && $fraudStatus !== 'challenge')) {
            $pembayaran->status = 'paid';
            $pembayaran->transaksiAkun?->update(['status' => 'proses']);
            $pembayaran->pesananJoki?->update(['status' => 'proses']);
        } elseif (in_array($transactionStatus, ['cancel', 'deny'], true)) {
            $pembayaran->status = 'gagal';
        } elseif ($transactionStatus === 'expire') {
            $pembayaran->status = 'kadaluarsa';
        }

        $pembayaran->save();

        if ($statusLama === 'pending'
            && in_array($transactionStatus, ['cancel', 'deny', 'expire'], true)) {
            self::kembalikanStok($pembayaran);
        }
    }

    private static function kembalikanStok(Pembayaran $pembayaran): void
    {
        $akun = $pembayaran->transaksiAkun?->akunGame;

        if (! $akun) {
            return;
        }

        DB::transaction(function () use ($akun) {
            $akun = AkunGame::whereKey($akun->id)->lockForUpdate()->first();

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
        });
    }
}
