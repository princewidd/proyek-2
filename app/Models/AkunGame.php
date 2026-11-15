<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkunGame extends Model
{
    protected $table = 'akun_game';

    protected $fillable = [
        'game',
        'judul',
        'harga',
        'deskripsi',
        'status',
        'gambar',
        'server',
        'multiple_quantity',
        'quantity',
        'delivery_method',
        'account_linked',
        'delivery_days',
        'delivery_hours',
        'tags',
        'gambar_tambahan',
    ];

    protected function casts(): array
    {
        return [
            'multiple_quantity' => 'boolean',
            'account_linked' => 'boolean',
            'tags' => 'array',
            'gambar_tambahan' => 'array',
        ];
    }

    public function titipJual()
    {
        return $this->hasOne(TitipJual::class);
    }

    public function transaksiAkuns()
    {
        return $this->hasMany(TransaksiAkun::class);
    }
}