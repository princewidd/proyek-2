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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_joki_id')->nullable()->constrained('pesanan_joki')->cascadeOnDelete();
            $table->foreignId('transaksi_akun_id')->nullable()->constrained('transaksi_akun')->cascadeOnDelete();
            $table->string('kode_pembayaran')->unique();
            $table->string('metode');
            $table->bigInteger('jumlah');
            $table->string('status')->default('pending');
            $table->string('bukti')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
