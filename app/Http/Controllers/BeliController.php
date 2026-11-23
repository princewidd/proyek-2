<?php

namespace App\Http\Controllers;

use App\Models\AkunGame;
use App\Models\PaketJoki;
use App\Models\Pembayaran;
use App\Models\PesananJoki;
use App\Models\TransaksiAkun;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BeliController extends Controller
{
    public function store(Request $request, AkunGame $akunGame)
    {
        $data = $request->validate([
            'metode' => ['required', 'in:qris,bank'],
        ]);

        $pembayaran = DB::transaction(function () use ($request, $akunGame, $data) {
            $akun = AkunGame::whereKey($akunGame->id)->lockForUpdate()->firstOrFail();

            abort_unless($akun->status === 'tersedia', 404);

            $stok = $akun->multiple_quantity ? (int) $akun->quantity : 1;

            abort_if($stok < 1, 404);

            $sisa = $stok - 1;

            $akun->update([
                'quantity' => $akun->multiple_quantity ? $sisa : $akun->quantity,
                'status' => $sisa > 0 ? 'tersedia' : 'terjual',
            ]);

            $transaksi = TransaksiAkun::create([
                'user_id' => $request->user()->id,
                'akun_game_id' => $akun->id,
                'harga_final' => $akun->harga,
                'status' => 'pending',
            ]);

            return Pembayaran::create([
                'transaksi_akun_id' => $transaksi->id,
                'kode_pembayaran' => 'PAY-'.strtoupper(Str::random(8)),
                'metode' => $data['metode'],
                'jumlah' => $akun->harga,
                'status' => 'pending',
            ]);
        });

        return redirect()
            ->route('pembayaran.show', $pembayaran)
            ->with('sukses', 'Pesanan berhasil dibuat, selesaikan pembayaran.');
    }

    public function storeJoki(Request $request)
    {
        $data = $request->validate([
            'paket_joki_id' => ['required', 'exists:paket_joki,id'],
            'metode' => ['required', 'in:qris,bank'],
            'email_akun' => ['required', 'email'],
            'password_akun' => ['required', 'string'],
            'catatan' => ['nullable', 'string'],
        ]);

        $paketJoki = PaketJoki::findOrFail($data['paket_joki_id']);

        $pesanan = PesananJoki::create([
            'user_id' => $request->user()->id,
            'paket_joki_id' => $paketJoki->id,
            'email_akun' => $data['email_akun'],
            'password_akun' => $data['password_akun'],
            'catatan' => $data['catatan'] ?? null,
            'status' => 'pending',
        ]);

        $pembayaran = Pembayaran::create([
            'pesanan_joki_id' => $pesanan->id,
            'kode_pembayaran' => 'PAY-'.strtoupper(Str::random(8)),
            'metode' => $data['metode'],
            'jumlah' => $paketJoki->harga,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('pembayaran.show', $pembayaran)
            ->with('sukses', 'Pesanan joki berhasil dibuat, selesaikan pembayaran.');
    }

    public function show(Request $request, Pembayaran $pembayaran)
    {
        $pembayaran->load(['transaksiAkun.akunGame', 'transaksiAkun.user', 'pesananJoki.paketJoki', 'pesananJoki.user']);

        $punyaAkses = $pembayaran->transaksiAkun?->user_id === $request->user()->id
            || $pembayaran->pesananJoki?->user_id === $request->user()->id
            || $request->user()->role === 'admin';

        abort_unless($punyaAkses, 403);

        $snapToken = null;

        if ($pembayaran->metode === 'qris'
            && $pembayaran->status === 'pending'
            && MidtransService::aktif()) {
            MidtransService::sinkronStatus($pembayaran->refresh());

            if (! $pembayaran->snap_token) {
                $snapToken = MidtransService::snapToken($pembayaran);

                if ($snapToken) {
                    $pembayaran->update([
                        'snap_token' => $snapToken,
                        'midtrans_order_id' => $pembayaran->kode_pembayaran,
                    ]);
                }
            }
        }

        return view('pembayaran', [
            'pembayaran' => $pembayaran->refresh(),
            'snapToken' => $pembayaran->snap_token,
        ]);
    }
}
