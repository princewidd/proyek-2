<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiAkun extends Model
{
    protected $table = 'transaksi_akun';

    protected $fillable = ['user_id', 'akun_game_id', 'harga_final', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function akunGame()
    {
        return $this->belongsTo(AkunGame::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
