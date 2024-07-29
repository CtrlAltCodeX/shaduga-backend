<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    public $table = 'communities';

    public $fillable = [
        'logo',
        'name',
        'description',
        'is_blockchain',
        'website',
        'categories',
        'invites',
        'link',
        'user_id'
    ];

    protected $casts = [
        'name' => 'string',
        // 'privacy' => 'string'
    ];

    public static array $rules = [
        'name' => 'required',
        'description' => 'required',
        'is_blockchain' => 'required',
        'website' => 'required',
        'categories' => 'required',
        // 'owner_id' => 'required',
        // 'privacy' => 'required',
        // 'member_count' => 'requried',
        // 'post_count' => 'requried',
        // 'status' => 'required'
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
