<?php

namespace Tests\Feature;

use App\Models\AkunGame;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminListingTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
    }

    public function test_admin_dapat_menambah_listing_akun()
    {
        $this->actingAs($this->admin())
            ->post(route('admin.akun.store'), [
                'game' => 'Mobile Legends',
                'judul' => 'Mythic Glory Full Skin',
                'harga' => '1.500.000',
                'status' => 'tersedia',
                'server' => 'Asia',
            ])
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('akun_game', [
            'game' => 'Mobile Legends',
            'judul' => 'Mythic Glory Full Skin',
            'harga' => 1500000,
            'status' => 'tersedia',
            'quantity' => 1,
        ]);
    }

    public function test_edit_listing_mengunci_game_lama()
    {
        $akun = AkunGame::create([
            'game' => 'Mobile Legends',
            'judul' => 'Akun Lama',
            'harga' => 500000,
            'status' => 'tersedia',
            'quantity' => 1,
        ]);

        $this->actingAs($this->admin())
            ->put(route('admin.akun.update', $akun), [
                'game' => 'Game Lain',
                'judul' => 'Akun Baru',
                'harga' => '750.000',
                'status' => 'terjual',
            ])
            ->assertRedirect(route('dashboard'));

        $akun->refresh();

        $this->assertSame('Mobile Legends', $akun->game);
        $this->assertSame('Akun Baru', $akun->judul);
        $this->assertSame(750000, (int) $akun->harga);
        $this->assertSame('terjual', $akun->status);
        $this->assertSame(1, (int) $akun->quantity);
    }

    public function test_pelanggan_tidak_dapat_menambah_listing()
    {
        $pelanggan = User::factory()->create(['role' => 'pelanggan', 'email_verified_at' => now()]);

        $this->actingAs($pelanggan)
            ->post(route('admin.akun.store'), [
                'game' => 'Mobile Legends',
                'judul' => 'Nyelundup',
                'harga' => '1000',
                'status' => 'tersedia',
            ])
            ->assertForbidden();
    }
}
