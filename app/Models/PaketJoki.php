<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketJoki extends Model
{
    protected $table = 'paket_joki';

    protected $fillable = ['nama_paket', 'game', 'kategori', 'harga', 'estimasi_waktu', 'deskripsi'];

    public const KATEGORI = [
        'leveling-account' => 'Leveling Account',
        'leveling-character' => 'Leveling Character',
        'maintenance-account' => 'Maintenance Account',
        'explorasi' => 'Explorasi',
        'quest' => 'Quest',
    ];

    public function pesananJokis()
    {
        return $this->hasMany(PesananJoki::class);
    }
}
