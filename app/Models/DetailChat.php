<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailChat extends Model
{
    protected $table = 'detail_chats';

    protected $fillable = ['chat_id', 'pengirim_id', 'pesan', 'dibaca'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }
}
