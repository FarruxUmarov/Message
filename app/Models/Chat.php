<?php

namespace App\Models;

use Database\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    /** @use HasFactory<MessageFactory> */
    use HasFactory;

    public $fillable = [
        'sender_id',
        'receiver_id',
        'chat',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        $this->belongsTo(User::class, 'receiver_id');
    }
}
