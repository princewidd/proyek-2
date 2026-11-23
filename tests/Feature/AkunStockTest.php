<?php

namespace Tests\Feature;

use App\Models\AkunGame;
use App\Models\Pembayaran;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AkunStockTest extends TestCase
{
    use RefreshDatabase;

    private function buatAkun(array $override = []): AkunGame
    {
        return AkunGame::create(array_merge([
            'game' => 'Mobile Legends',
            'judul' => 'Akun Mythic',
            'harga' => 500000,
            'status' => 'tersedia',
            'quantity' => 1,
            'multiple_quantity' => false,
        ], $override));
    }

    public function test_akun_single_langsung_terjual_setelah_checkout()
    {
        $akun = $this->buatAkun();

        $this->actingAs(User::factory()->create())
            ->post(route('beli.akun.store', $akun), ['metode' => 'qris'])
            ->assertRedirect();

        $this->assertSame('terjual', $akun->fresh()->status);
    }

    public function test_akun_multi_stok_berkurang_dan_terjual_saat_habis()
    {
        $akun = $this->buatAkun(['multiple_quantity' => true, 'quantity' => 2]);

        $this->actingAs(User::factory()->create())
            ->post(route('beli.akun.store', $akun), ['metode' => 'qris'])
            ->assertRedirect();

        $this->assertSame('tersedia', $akun->fresh()->status);
        $this->assertSame(1, $akun->fresh()->quantity);

        $this->actingAs(User::factory()->create())
            ->post(route('beli.akun.store', $akun), ['metode' => 'qris'])
            ->assertRedirect();

        $this->assertSame('terjual', $akun->fresh()->status);
        $this->assertSame(0, $akun->fresh()->quantity);
    }

    public function test_akun_terjual_tidak_bisa_dibeli_lagi()
    {
        $akun = $this->buatAkun(['status' => 'terjual']);

        $this->actingAs(User::factory()->create())
            ->post(route('beli.akun.store', $akun), ['metode' => 'qris'])
            ->assertNotFound();
    }

    public function test_stok_kembali_saat_pembayaran_expire()
    {
        $akun = $this->buatAkun();

        $this->actingAs(User::factory()->create())
            ->post(route('beli.akun.store', $akun), ['metode' => 'qris']);

        $pembayaran = Pembayaran::firstOrFail();

        MidtransService::terapkanStatus($pembayaran, 'expire');

        $this->assertSame('kadaluarsa', $pembayaran->fresh()->status);
        $this->assertSame('tersedia', $akun->fresh()->status);
    }

    public function test_stok_kembali_saat_pembayaran_cancel_hanya_sekali()
    {
        $akun = $this->buatAkun(['multiple_quantity' => true, 'quantity' => 3]);

        $this->actingAs(User::factory()->create())
            ->post(route('beli.akun.store', $akun), ['metode' => 'qris']);

        $pembayaran = Pembayaran::firstOrFail();

        MidtransService::terapkanStatus($pembayaran, 'cancel');
        MidtransService::terapkanStatus($pembayaran, 'cancel');

        $this->assertSame(3, $akun->fresh()->quantity);
        $this->assertSame('tersedia', $akun->fresh()->status);
    }
}
