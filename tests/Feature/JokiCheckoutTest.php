<?php

namespace Tests\Feature;

use App\Models\PaketJoki;
use App\Models\PesananJoki;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class JokiCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_akun_joki_tersimpan_terenkripsi()
    {
        $pesanan = PesananJoki::create([
            'user_id' => User::factory()->create()->id,
            'paket_joki_id' => PaketJoki::create([
                'nama_paket' => 'Push Mythic',
                'game' => 'Mobile Legends',
                'harga' => 100000,
            ])->id,
            'email_akun' => 'akun@mail.com',
            'password_akun' => 'rahasia123',
            'status' => 'pending',
        ]);

        $raw = DB::table('pesanan_joki')->where('id', $pesanan->id)->value('password_akun');

        $this->assertNotSame('rahasia123', $raw);
        $this->assertStringNotContainsString('rahasia123', $raw);
        $this->assertSame('rahasia123', $pesanan->fresh()->password_akun);
    }

    public function test_checkout_joki_menyimpan_password_terenkripsi_dan_membuat_pembayaran()
    {
        $pelanggan = User::factory()->create();
        $paket = PaketJoki::create([
            'nama_paket' => 'Push Mythic',
            'game' => 'Mobile Legends',
            'harga' => 100000,
        ]);

        $this->actingAs($pelanggan)
            ->post(route('beli.joki.store'), [
                'paket_joki_id' => $paket->id,
                'metode' => 'qris',
                'email_akun' => 'akun@mail.com',
                'password_akun' => 'rahasia123',
            ])
            ->assertRedirect();

        $pesanan = PesananJoki::firstOrFail();

        $raw = DB::table('pesanan_joki')->where('id', $pesanan->id)->value('password_akun');

        $this->assertNotSame('rahasia123', $raw);
        $this->assertSame('rahasia123', $pesanan->password_akun);
        $this->assertDatabaseHas('pembayaran', [
            'pesanan_joki_id' => $pesanan->id,
            'jumlah' => 100000,
            'status' => 'pending',
        ]);
    }
}
