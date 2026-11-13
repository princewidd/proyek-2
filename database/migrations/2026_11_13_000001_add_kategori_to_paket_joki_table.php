<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('paket_joki', function (Blueprint $table) {
            $table->string('kategori')->nullable()->after('game');
        });
    }

    public function down(): void
    {
        Schema::table('paket_joki', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }
};
