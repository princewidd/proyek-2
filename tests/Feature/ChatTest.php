<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_pembeli_dapat_membuka_halaman_chat()
    {
        $pelanggan = User::factory()->create();

        $this->actingAs($pelanggan)
            ->get(route('chat'))
            ->assertOk()
            ->assertSee('Chat CS');
    }

    public function test_pembeli_mengirim_pesan_membuat_chat_baru_dengan_admin()
    {
        $pelanggan = User::factory()->create();
        User::factory()->create(['role' => 'admin']);

        $this->actingAs($pelanggan)
            ->postJson(route('chat.send'), ['pesan' => 'Boleh nego harga?'])
            ->assertOk()
            ->assertJsonPath('message.pesan', 'Boleh nego harga?');

        $this->assertDatabaseHas('chats', [
            'pelanggan_id' => $pelanggan->id,
            'status' => 'aktif',
        ]);

        $this->assertDatabaseHas('detail_chats', [
            'pengirim_id' => $pelanggan->id,
            'pesan' => 'Boleh nego harga?',
        ]);
    }

    public function test_polling_pembeli_mengembalikan_pesan_dan_status_online_admin()
    {
        $pelanggan = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin', 'last_seen_at' => now()]);

        $chat = Chat::create([
            'pelanggan_id' => $pelanggan->id,
            'admin_id' => $admin->id,
            'status' => 'aktif',
        ]);

        $chat->detailChats()->create([
            'pengirim_id' => $admin->id,
            'pesan' => 'Halo, mau nego? Gas!',
        ]);

        $this->actingAs($pelanggan)
            ->getJson(route('chat.poll'))
            ->assertOk()
            ->assertJsonPath('admin_online', true)
            ->assertJsonCount(1, 'messages')
            ->assertJsonPath('messages.0.pesan', 'Halo, mau nego? Gas!');
    }

    public function test_admin_dapat_membalas_chat_pelanggan()
    {
        $pelanggan = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);

        $chat = Chat::create([
            'pelanggan_id' => $pelanggan->id,
            'admin_id' => $admin->id,
            'status' => 'aktif',
        ]);

        $this->actingAs($admin)
            ->postJson(route('admin.chat.send', $chat), ['pesan' => 'Bisa kok, 450k deal!'])
            ->assertOk()
            ->assertJsonPath('message.pesan', 'Bisa kok, 450k deal!');

        $this->assertDatabaseHas('detail_chats', [
            'chat_id' => $chat->id,
            'pengirim_id' => $admin->id,
            'pesan' => 'Bisa kok, 450k deal!',
        ]);
    }

    public function test_pesanan_menjadi_dibaca_setelah_penerima_polling()
    {
        $pelanggan = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);

        $chat = Chat::create([
            'pelanggan_id' => $pelanggan->id,
            'admin_id' => $admin->id,
            'status' => 'aktif',
        ]);

        $pesan = $chat->detailChats()->create([
            'pengirim_id' => $pelanggan->id,
            'pesan' => 'Halo admin',
            'dibaca' => false,
        ]);

        $this->actingAs($admin)
            ->getJson(route('admin.chat.poll', $chat))
            ->assertOk();

        $this->assertTrue($pesan->fresh()->dibaca);
    }

    public function test_pelanggan_tidak_dapat_mengakses_inbox_admin()
    {
        $pelanggan = User::factory()->create();

        $this->actingAs($pelanggan)
            ->get(route('admin.chat'))
            ->assertForbidden();
    }

    public function test_pelanggan_tidak_dapat_polling_chat_mitra_lain()
    {
        $pelanggan = User::factory()->create();
        $pelangganLain = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);

        $chat = Chat::create([
            'pelanggan_id' => $pelangganLain->id,
            'admin_id' => $admin->id,
            'status' => 'aktif',
        ]);

        $this->actingAs($pelanggan)
            ->postJson(route('admin.chat.send', $chat), ['pesan' => 'nyelunduk'])
            ->assertForbidden();
    }
}
