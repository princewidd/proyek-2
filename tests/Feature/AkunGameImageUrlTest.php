<?php

namespace Tests\Feature;

use App\Models\AkunGame;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AkunGameImageUrlTest extends TestCase
{
    use RefreshDatabase;

    private function akun(array $attributes = []): AkunGame
    {
        return AkunGame::create(array_merge([
            'game' => 'Zenless Zone Zero',
            'judul' => 'Akun Demo',
            'harga' => 100000,
            'status' => 'tersedia',
            'quantity' => 1,
        ], $attributes));
    }

    public function test_halaman_game_menampilkan_url_storage_untuk_gambar_upload(): void
    {
        $this->akun(['gambar' => 'akun-game/demo.jpg']);

        $response = $this->get(route('game.show', ['slug' => 'zenless-zone-zero']));

        $response->assertOk();
        $response->assertSee(asset('storage/akun-game/demo.jpg'), false);
        $response->assertDontSee(asset('akun-game/demo.jpg'), false);
    }

    public function test_halaman_detail_akun_menampilkan_url_storage_untuk_gambar_upload(): void
    {
        $akun = $this->akun(['gambar' => 'akun-game/demo.jpg']);

        $response = $this->get(route('game.akun.show', [
            'slug' => 'zenless-zone-zero',
            'akun' => $akun->id,
        ]));

        $response->assertOk();
        $response->assertSee(asset('storage/akun-game/demo.jpg'), false);
        $response->assertDontSee(asset('akun-game/demo.jpg'), false);
    }

    public function test_gambar_statis_publik_tetap_menggunakan_url_langsung(): void
    {
        $this->akun(['gambar' => 'img/beranda/zenles.png']);

        $response = $this->get(route('game.show', ['slug' => 'zenless-zone-zero']));

        $response->assertOk();
        $response->assertSee(asset('img/beranda/zenles.png'), false);
    }
}
