<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunGame;
use App\Models\PaketJoki;
use App\Models\PesananJoki;
use App\Models\TransaksiAkun;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->query('tab', 'my-listing');
        $tabs = ['my-listing', 'dashboard', 'payout', 'purchases', 'favourites', 'reviews'];

        if (! in_array($tab, $tabs)) {
            $tab = 'my-listing';
        }

        $pesanan = PesananJoki::with('paketJoki')->latest()->get();

        return view('dashboard', [
            'tab' => $tab,
            'tabs' => [
                'my-listing' => 'My Listing',
                'dashboard' => 'Dashboard',
                'payout' => 'Payout Options',
                'purchases' => 'My Purchases',
                'favourites' => 'My Favourites',
                'reviews' => 'My Reviews',
            ],
            'listingAkun' => AkunGame::latest()->get(),
            'listingJoki' => PaketJoki::latest()->get(),
            'pesanan' => $pesanan,
            'totalAkun' => AkunGame::count(),
            'akunTersedia' => AkunGame::where('status', 'tersedia')->count(),
            'totalPaketJoki' => PaketJoki::count(),
            'totalPesanan' => PesananJoki::count() + TransaksiAkun::count(),
        ]);
    }
}
