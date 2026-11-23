<?php

namespace Tests\Feature;

use App\Models\AkunGame;
use App\Models\PaketJoki;
use App\Models\PesananJoki;
use App\Models\TransaksiAkun;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderStatusTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
    }

    private function transaksi(): TransaksiAkun
    {
        $akun = AkunGame::create([
            'game' => 'Mobile Legends',
            'judul' => 'Akun Mythic',
            'harga' => 500000,
            'status' => 'terjual',
            'quantity' => 1,
        ]);

        return TransaksiAkun::create([
            'user_id' => User::factory()->create()->id,
            'akun_game_id' => $akun->id,
            'harga_final' => 500000,
            'status' => 'pending',
        ]);
    }

    public function test_admin_mengubah_status_order_akun_ke_selesai()
    {
        $transaksi = $this->transaksi();

        $this->actingAs($this->admin())
            ->patchJson(route('admin.orders.status', ['akun', $transaksi->id]), ['status' => 'selesai'])
            ->assertOk()
            ->assertJsonPath('status_raw', 'selesai')
            ->assertJsonPath('category', 'completed');

        $this->assertSame('selesai', $transaksi->fresh()->status);
    }

    public function test_admin_mengubah_status_order_joki()
    {
        $paket = PaketJoki::create([
            'nama_paket' => 'Push Mythic',
            'game' => 'Mobile Legends',
            'harga' => 100000,
        ]);

        $pesanan = PesananJoki::create([
            'user_id' => User::factory()->create()->id,
            'paket_joki_id' => $paket->id,
            'email_akun' => 'akun@mail.com',
            'password_akun' => 'rahasia',
            'status' => 'pending',
        ]);

        $this->actingAs($this->admin())
            ->patchJson(route('admin.orders.status', ['joki', $pesanan->id]), ['status' => 'menunggu_verifikasi'])
            ->assertOk()
            ->assertJsonPath('category', 'on_hold');

        $this->assertSame('menunggu_verifikasi', $pesanan->fresh()->status);
    }

    public function test_batal_mengembalikan_stok_akun()
    {
        $transaksi = $this->transaksi();

        $this->actingAs($this->admin())
            ->patchJson(route('admin.orders.status', ['akun', $transaksi->id]), ['status' => 'batal'])
            ->assertOk();

        $this->assertSame('tersedia', $transaksi->akunGame->fresh()->status);
    }

    public function test_batal_mengembalikan_kategori_cancelled()
    {
        $transaksi = $this->transaksi();

        $this->actingAs($this->admin())
            ->patchJson(route('admin.orders.status', ['akun', $transaksi->id]), ['status' => 'batal'])
            ->assertOk()
            ->assertJsonPath('status_raw', 'batal')
            ->assertJsonPath('category', 'cancelled')
            ->assertJsonPath('status_label', 'Dibatalkan');
    }

    public function test_order_batal_tidak_muncul_di_daftar_dashboard()
    {
        $transaksi = $this->transaksi();
        $idTampil = 'AKN-'.str_pad((string) $transaksi->id, 6, '0', STR_PAD_LEFT);

        $admin = $this->admin();

        $this->actingAs($admin)
            ->patchJson(route('admin.orders.status', ['akun', $transaksi->id]), ['status' => 'batal'])
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee($idTampil);
    }

    public function test_status_tidak_valid_ditolak()
    {
        $transaksi = $this->transaksi();

        $this->actingAs($this->admin())
            ->patchJson(route('admin.orders.status', ['akun', $transaksi->id]), ['status' => 'ngawur'])
            ->assertStatus(422);
    }

    public function test_pelanggan_tidak_dapat_mengubah_status_order()
    {
        $transaksi = $this->transaksi();
        $pelanggan = User::factory()->create(['role' => 'pelanggan', 'email_verified_at' => now()]);

        $this->actingAs($pelanggan)
            ->patchJson(route('admin.orders.status', ['akun', $transaksi->id]), ['status' => 'selesai'])
            ->assertForbidden();
    }
}
