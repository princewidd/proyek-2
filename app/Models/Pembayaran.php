<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = ['pesanan_joki_id', 'transaksi_akun_id', 'kode_pembayaran', 'metode', 'jumlah', 'status', 'bukti'];

    public function pesananJoki()
    {
        return $this->belongsTo(PesananJoki::class);
    }

    public function transaksiAkun()
    {
        return $this->belongsTo(TransaksiAkun::class);
    }
}
