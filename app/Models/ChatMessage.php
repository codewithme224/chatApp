<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatMessage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'text'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }


    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
