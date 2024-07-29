<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public $table = 'chats';

    public $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'status',
        'community_id',
    ];

    protected $casts = [
        'sender_id' => 'integer',
        'receiver_id' => 'integer',
        'message' => 'string',
        'status' => 'string'
    ];

    public static array $rules = [
        'sender_id' => 'required',
        'receiver_id' => 'required',
        'message' => 'required',
        'status' => 'required',
        'community_id' => 'required'
    ];
}
