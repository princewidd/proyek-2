<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('akun_game', function (Blueprint $table) {
            $table->string('server')->nullable();
            $table->boolean('multiple_quantity')->default(false);
            $table->unsignedInteger('quantity')->default(1);
            $table->string('delivery_method')->default('coordinated');
            $table->boolean('account_linked')->default(false);
            $table->unsignedSmallInteger('delivery_days')->nullable();
            $table->unsignedSmallInteger('delivery_hours')->nullable();
            $table->json('tags')->nullable();
            $table->json('gambar_tambahan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('akun_game', function (Blueprint $table) {
            $table->dropColumn([
                'server',
                'multiple_quantity',
                'quantity',
                'delivery_method',
                'account_linked',
                'delivery_days',
                'delivery_hours',
                'tags',
                'gambar_tambahan',
            ]);
        });
    }
};