<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketJoki extends Model
{
    protected $table = 'paket_joki';

    protected $fillable = ['nama_paket', 'game', 'harga', 'estimasi_waktu', 'deskripsi'];

    public function pesananJokis()
    {
        return $this->hasMany(PesananJoki::class);
    }
}
