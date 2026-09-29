<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TitipJual extends Model
{
    protected $table = 'titip_jual';

    protected $fillable = ['akun_game_id', 'nama_penitip', 'kontak_penitip', 'persentase_donasi', 'status'];

    public function akunGame()
    {
        return $this->belongsTo(AkunGame::class);
    }
}
