<?php

namespace App\Http\Controllers;

use App\Models\AkunGame;
use App\Models\PaketJoki;
use App\Models\Pembayaran;
use App\Models\PesananJoki;
use App\Models\TransaksiAkun;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BeliController extends Controller
{
    public function store(Request $request, AkunGame $akunGame)
    {
        abort_unless($akunGame->status === 'tersedia', 404);

        $data = $request->validate([
            'metode' => ['required', 'in:qris,bank'],
        ]);

        $transaksi = TransaksiAkun::create([
            'user_id' => $request->user()->id,
            'akun_game_id' => $akunGame->id,
            'harga_final' => $akunGame->harga,
            'status' => 'pending',
        ]);

        $pembayaran = Pembayaran::create([
            'transaksi_akun_id' => $transaksi->id,
            'kode_pembayaran' => 'PAY-' . strtoupper(Str::random(8)),
            'metode' => $data['metode'],
            'jumlah' => $akunGame->harga,
            'status' => 'pending',
        ]);

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
            'kode_pembayaran' => 'PAY-' . strtoupper(Str::random(8)),
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
        $pembayaran->load(['transaksiAkun.akunGame', 'pesananJoki.paketJoki']);

        $punyaAkses = $pembayaran->transaksiAkun?->user_id === $request->user()->id
            || $pembayaran->pesananJoki?->user_id === $request->user()->id
            || $request->user()->role === 'admin';

        abort_unless($punyaAkses, 403);

        return view('pembayaran', [
            'pembayaran' => $pembayaran,
        ]);
    }
}
