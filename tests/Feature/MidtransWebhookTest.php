<?php

namespace Tests\Feature;

use App\Models\AkunGame;
use App\Models\Pembayaran;
use App\Models\TransaksiAkun;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MidtransWebhookTest extends TestCase
{
    use RefreshDatabase;

    private function buatPembayaran(): Pembayaran
    {
        $akun = AkunGame::create([
            'game' => 'Mobile Legends',
            'judul' => 'Akun Test',
            'harga' => 50000,
            'status' => 'tersedia',
        ]);

        $transaksi = TransaksiAkun::create([
            'user_id' => User::factory()->create()->id,
            'akun_game_id' => $akun->id,
            'harga_final' => 50000,
            'status' => 'pending',
        ]);

        return Pembayaran::create([
            'transaksi_akun_id' => $transaksi->id,
            'kode_pembayaran' => 'PAY-TEST123',
            'metode' => 'qris',
            'jumlah' => 50000,
            'status' => 'pending',
            'midtrans_order_id' => 'PAY-TEST123',
        ]);
    }

    public function test_webhook_settlement_menandai_pembayaran_paid(): void
    {
        $pembayaran = $this->buatPembayaran();

        $payload = [
            'order_id' => 'PAY-TEST123',
            'status_code' => '200',
            'gross_amount' => '50000.00',
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
        ];
        $payload['signature_key'] = hash('sha512',
            $payload['order_id'].$payload['status_code'].$payload['gross_amount'].config('midtrans.server_key'));

        $this->postJson('/midtrans/callback', $payload)
            ->assertOk();

        $this->assertDatabaseHas('pembayaran', ['id' => $pembayaran->id, 'status' => 'paid', 'midtrans_status' => 'settlement']);
        $this->assertDatabaseHas('transaksi_akun', ['id' => $pembayaran->transaksi_akun_id, 'status' => 'proses']);
    }

    public function test_webhook_signature_salah_ditolak(): void
    {
        $this->buatPembayaran();

        $payload = [
            'order_id' => 'PAY-TEST123',
            'status_code' => '200',
            'gross_amount' => '50000.00',
            'transaction_status' => 'settlement',
            'signature_key' => 'signature-palsu',
        ];

        $this->postJson('/midtrans/callback', $payload)
            ->assertStatus(403);

        $this->assertDatabaseHas('pembayaran', ['kode_pembayaran' => 'PAY-TEST123', 'status' => 'pending']);
    }

    public function test_webhook_expire_menandai_kadaluarsa(): void
    {
        $pembayaran = $this->buatPembayaran();

        $payload = [
            'order_id' => 'PAY-TEST123',
            'status_code' => '200',
            'gross_amount' => '50000.00',
            'transaction_status' => 'expire',
        ];
        $payload['signature_key'] = hash('sha512',
            $payload['order_id'].$payload['status_code'].$payload['gross_amount'].config('midtrans.server_key'));

        $this->postJson('/midtrans/callback', $payload)
            ->assertOk();

        $this->assertDatabaseHas('pembayaran', ['id' => $pembayaran->id, 'status' => 'kadaluarsa']);
    }
}
