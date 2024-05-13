<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    public $table = 'members';

    public $fillable = [
        'community_id',
        'user_id',
        'join_date',
        'status',
        'role',
        'last_active'
    ];

    protected $casts = [
        'community_id' => 'string',
        'user_id' => 'string',
        'join_date' => 'date',
        'role' => 'string',
        'last_active' => 'string'
    ];

    public static array $rules = [
        'community_id' => 'requreid',
        'user_id' => 'required',
        'join_date' => 'requried',
        'status' => 'requreid',
        'role' => 'required',
        'last_active' => 'required'
    ];

    
}
