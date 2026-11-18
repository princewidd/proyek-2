<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunGame;
use App\Models\PaketJoki;
use App\Models\PesananJoki;
use App\Models\Review;
use App\Models\TransaksiAkun;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'my-listing');
        $tabs = ['my-listing', 'dashboard', 'reviews'];

        if (! in_array($tab, $tabs)) {
            $tab = 'my-listing';
        }

        $isAdmin = $request->user()->role === 'admin';

        if (! $isAdmin) {
            $pesanan = PesananJoki::with(['paketJoki', 'user', 'pembayarans'])
                ->where('user_id', $request->user()->id)
                ->latest()
                ->get();

            $transaksiAkun = TransaksiAkun::with(['akunGame', 'user', 'pembayarans'])
                ->where('user_id', $request->user()->id)
                ->latest()
                ->get();
        } else {
            $pesanan = PesananJoki::with(['paketJoki', 'user', 'pembayarans'])
                ->latest()
                ->get();

            $transaksiAkun = TransaksiAkun::with(['akunGame', 'user', 'pembayarans'])
                ->latest()
                ->get();
        }

        // Gabungkan semua order (Akun Game + Paket Joki) untuk Listing Overview di Dashboard
        $ordersAkun = $transaksiAkun->map(function ($t) {
            $pembayaran = $t->pembayarans->first();
            $statusRaw = strtolower($t->status ?? 'pending');

            $category = match (true) {
                in_array($statusRaw, ['selesai', 'completed', 'paid', 'berhasil']) => 'completed',
                in_array($statusRaw, ['hold', 'on_hold', 'menunggu_verifikasi']) => 'on_hold',
                default => 'on_going',
            };

            return [
                'id' => 'AKN-' . str_pad((string) $t->id, 6, '0', STR_PAD_LEFT),
                'kode_bayar' => $pembayaran?->kode_pembayaran ?? ('AKN-' . $t->id),
                'type' => 'Accounts',
                'buyer_name' => $t->user?->name ?? 'Guest Buyer',
                'buyer_username' => $t->user?->username ?? ('user-' . ($t->user_id ?? '0')),
                'title' => $t->akunGame?->judul ?? 'Akun Game Terhapus',
                'game' => $t->akunGame?->game ?? '-',
                'date' => $t->created_at?->format('d F Y, H:i') ?? '-',
                'timestamp' => $t->created_at?->timestamp ?? 0,
                'price' => (int) ($t->harga_final ?? 0),
                'status_raw' => $statusRaw,
                'category' => $category,
                'status_label' => match ($category) {
                    'completed' => 'Good Delivery',
                    'on_hold' => 'On Hold',
                    default => ucfirst($statusRaw),
                },
                'metode' => $pembayaran?->metode ?? 'Manual / Midtrans',
            ];
        });

        $ordersJoki = $pesanan->map(function ($p) {
            $pembayaran = $p->pembayarans->first();
            $statusRaw = strtolower($p->status ?? 'pending');

            $category = match (true) {
                in_array($statusRaw, ['selesai', 'completed', 'paid', 'berhasil']) => 'completed',
                in_array($statusRaw, ['hold', 'on_hold', 'menunggu_verifikasi']) => 'on_hold',
                default => 'on_going',
            };

            return [
                'id' => 'JKI-' . str_pad((string) $p->id, 6, '0', STR_PAD_LEFT),
                'kode_bayar' => $pembayaran?->kode_pembayaran ?? ('JKI-' . $p->id),
                'type' => 'Joki',
                'buyer_name' => $p->user?->name ?? 'Guest Buyer',
                'buyer_username' => $p->user?->username ?? ($p->email_akun ?? 'customer'),
                'title' => $p->paketJoki?->nama_paket ?? 'Paket Joki Terhapus',
                'game' => $p->paketJoki?->game ?? '-',
                'date' => $p->created_at?->format('d F Y, H:i') ?? '-',
                'timestamp' => $p->created_at?->timestamp ?? 0,
                'price' => (int) ($p->paketJoki?->harga ?? $pembayaran?->jumlah ?? 0),
                'status_raw' => $statusRaw,
                'category' => $category,
                'status_label' => match ($category) {
                    'completed' => 'Good Delivery',
                    'on_hold' => 'On Hold',
                    default => ucfirst($statusRaw),
                },
                'metode' => $pembayaran?->metode ?? 'Manual / Midtrans',
            ];
        });

        $allOrders = $ordersAkun
            ->concat($ordersJoki)
            ->sortByDesc('timestamp')
            ->values();

        // Hitung ringkasan keuangan untuk Overview
        $onGoingAmount = $allOrders->where('category', 'on_going')->sum('price');
        $onHoldAmount = $allOrders->where('category', 'on_hold')->sum('price');
        $balanceAmount = $allOrders->where('category', 'completed')->sum('price');
        $withdrawnAmount = 0;

        $listingAkun = AkunGame::latest()->get();
        $listingJoki = PaketJoki::latest()->get();

        // Ambil review yang terhubung dengan transaksi selesai (anti fake review)
        $reviews = Review::with('user')
            ->whereNotNull('reviewable_id')
            ->latest()
            ->get();

        return view('dashboard', [
            'tab' => $tab,
            'tabs' => [
                'my-listing' => 'My Listing',
                'dashboard' => 'Dashboard',
                'reviews' => 'My Reviews',
            ],
            'listingAkun' => $listingAkun,
            'listingJoki' => $listingJoki,
            'pesanan' => $pesanan,
            'allOrders' => $allOrders,
            'reviews' => $reviews,
            'overview' => [
                'on_going' => $onGoingAmount,
                'on_hold' => $onHoldAmount,
                'balance' => $balanceAmount,
                'withdrawn' => $withdrawnAmount,
                'total_products' => $listingAkun->count() + $listingJoki->count(),
                'sold_products' => $listingAkun->where('status', 'terjual')->count() + $allOrders->where('category', 'completed')->count(),
            ],
        ]);
    }

    public function destroyReview(Request $request, Review $review): RedirectResponse
    {
        abort_unless($request->user()?->role === 'admin', 403);

        $review->delete();

        return redirect()
            ->route('dashboard', ['tab' => 'reviews'])
            ->with('success', 'Review berhasil dihapus.');
    }
}