<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_redirect_ke_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_pelanggan_ditolak_403(): void
    {
        $user = User::factory()->create(['role' => 'pelanggan', 'email_verified_at' => now()]);

        $this->actingAs($user)->get('/dashboard')->assertForbidden();
    }

    public function test_admin_bisa_akses_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);

        $this->actingAs($admin)->get('/dashboard')->assertOk()->assertViewIs('dashboard');
    }
}
