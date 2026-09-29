<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkunGame extends Model
{
    protected $table = 'akun_game';

    protected $fillable = ['game', 'judul', 'harga', 'deskripsi', 'status', 'gambar'];

    public function titipJual()
    {
        return $this->hasOne(TitipJual::class);
    }

    public function transaksiAkuns()
    {
        return $this->hasMany(TransaksiAkun::class);
    }
}
