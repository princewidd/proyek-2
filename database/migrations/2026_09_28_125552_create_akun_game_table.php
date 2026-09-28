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
        Schema::create('akun_game', function (Blueprint $table) {
            $table->id();
            $table->string('game');
            $table->string('judul');
            $table->bigInteger('harga');
            $table->text('deskripsi')->nullable();
            $table->string('status')->default('tersedia');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_game');
    }
};
