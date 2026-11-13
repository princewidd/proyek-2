<?php

namespace App\Http\Controllers;

use App\Models\AkunGame;
use App\Models\PaketJoki;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameController extends Controller
{
    private const GAMES = [
        ['name' => 'Genshin Impact', 'img' => 'gensin inpak.png'],
        ['name' => 'Valorant', 'img' => 'valo.png'],
        ['name' => 'Solo Leveling: Arise', 'img' => 'solo.png'],
        ['name' => 'Seven Knights Re:BIRTH', 'img' => 'seven1.png'],
        ['name' => 'Wuthering Waves', 'img' => 'waves.png'],
        ['name' => 'Neverness to Everness', 'img' => 'nte.png'],
        ['name' => 'Roblox', 'img' => 'image-10.png'],
        ['name' => 'Aniimo', 'img' => 'animo.png'],
        ['name' => 'Zenless Zone Zero', 'img' => 'zenles.png'],
        ['name' => 'Persona 5: The Phantom X', 'img' => 'pesona.png'],
    ];

    public static function games(): array
    {
        return self::GAMES;
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $games = $search === ''
            ? self::GAMES
            : array_values(array_filter(
                self::GAMES,
                fn(array $game) => str_contains(strtolower($game['name']), strtolower($search)),
            ));

        return view('beranda', ['games' => $games, 'search' => $search]);
    }

    public function show(string $slug)
    {
        $game = collect(self::GAMES)->first(fn(array $g) => Str::slug($g['name']) === $slug);
        abort_unless($game !== null, 404);

        $name = $game['name'];

        return view('game', [
            'game' => $game,
            'slug' => $slug,
            'akun' => AkunGame::where('game', $name)->where('status', 'tersedia')->latest()->get(),
            'paket' => PaketJoki::where('game', $name)->latest()->get(),
            'kategorijoki' => PaketJoki::KATEGORI,
        ]);
    }
}
