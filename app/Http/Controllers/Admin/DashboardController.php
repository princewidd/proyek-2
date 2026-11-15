<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkunGame;
use App\Models\PaketJoki;
use App\Models\PesananJoki;
use App\Models\TransaksiAkun;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $akunGames = AkunGame::when($search, function ($query, $search) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('game', 'like', "%{$search}%");
        })->latest()->paginate(10)->withQueryString();

        return view('admin.dashboard', [
            'akunGames' => $akunGames,
            'search' => $search,
            'totalAkun' => AkunGame::count(),
            'akunTersedia' => AkunGame::where('status', 'tersedia')->count(),
            'totalPaketJoki' => PaketJoki::count(),
            'totalPesanan' => PesananJoki::count() + TransaksiAkun::count(),
        ]);
    }
}