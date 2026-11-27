<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
            'quantity' => 'integer',
            'harga' => 'integer',
            'tags' => 'array',
            'gambar_tambahan' => 'array',
        ];
    }

    protected function gambarUrl(): Attribute
    {
        return Attribute::get(fn () => self::urlGambar($this->gambar));
    }

    public static function urlGambar(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }

        if (str_starts_with($path, 'akun-game/')) {
            return asset('storage/'.$path);
        }

        return asset($path);
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
