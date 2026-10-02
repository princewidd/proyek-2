<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'widhi',
            'email' => 'widhi@gmail.com',
            'password' => bcrypt('widhi123'),
            'role' => 'pelanggan',
        ]);
    }
}
