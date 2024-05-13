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
        'conversation_id',
        'type'
    ];

    protected $casts = [
        'sender_id' => 'integer',
        'receiver_id' => 'integer',
        'message' => 'string',
        'status' => 'string',
        'conversation_id' => 'integer',
        'type' => 'string'
    ];

    public static array $rules = [
        'sender_id' => 'requried',
        'receiver_id' => 'required',
        'message' => 'required',
        'status' => 'requreid',
        'conversation_id' => 'requred',
        'type' => 'required'
    ];

    
}
