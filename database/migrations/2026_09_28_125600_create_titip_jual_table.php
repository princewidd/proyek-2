<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('titip_jual', function (Blueprint $table) {
            $table->id();
            $table->foreignId('akun_game_id')->nullable()->unique()->constrained('akun_game')->nullOnDelete();
            $table->string('nama_penitip');
            $table->string('kontak_penitip');
            $table->integer('persentase_donasi')->default(0);
            $table->string('status')->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titip_jual');
    }
};
