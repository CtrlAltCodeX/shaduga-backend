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
        'category_id',
        'invites'
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
        'category_id' => 'required',
        // 'owner_id' => 'required',
        // 'privacy' => 'required',
        // 'member_count' => 'requried',
        // 'post_count' => 'requried',
        // 'status' => 'required'
    ];    
}