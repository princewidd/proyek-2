<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan_joki', function (Blueprint $table) {
            $table->text('password_akun')->change();
        });

        DB::table('pesanan_joki')
            ->whereNotNull('password_akun')
            ->chunkById(100, function ($rows) {
                foreach ($rows as $row) {
                    try {
                        Crypt::decryptString($row->password_akun);
                    } catch (Throwable) {
                        DB::table('pesanan_joki')
                            ->where('id', $row->id)
                            ->update(['password_akun' => Crypt::encryptString($row->password_akun)]);
                    }
                }
            });
    }

    public function down(): void
    {
        DB::table('pesanan_joki')
            ->whereNotNull('password_akun')
            ->chunkById(100, function ($rows) {
                foreach ($rows as $row) {
                    try {
                        $plain = Crypt::decryptString($row->password_akun);

                        DB::table('pesanan_joki')
                            ->where('id', $row->id)
                            ->update(['password_akun' => $plain]);
                    } catch (Throwable) {
                        // sudah plaintext
                    }
                }
            });

        Schema::table('pesanan_joki', function (Blueprint $table) {
            $table->string('password_akun')->change();
        });
    }
};
