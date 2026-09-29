<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananJoki extends Model
{
    protected $table = 'pesanan_joki';

    protected $fillable = ['user_id', 'paket_joki_id', 'email_akun', 'password_akun', 'catatan', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paketJoki()
    {
        return $this->belongsTo(PaketJoki::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
