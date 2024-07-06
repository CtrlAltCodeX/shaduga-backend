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
        'community_id' => 'required',
        'user_id' => 'required',
        'join_date' => 'required',
        'status' => 'required',
        'role' => 'required',
        'last_active' => 'required'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
