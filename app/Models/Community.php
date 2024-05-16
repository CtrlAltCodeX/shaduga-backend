<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    public $table = 'communities';

    public $fillable = [
        'name',
        'owner_id',
        'privacy',
        'category_id',
        'member_count',
        'post_count',
        'status'
    ];

    protected $casts = [
        'name' => 'string',
        'privacy' => 'string'
    ];

    public static array $rules = [
        'name' => 'required',
        'owner_id' => 'required',
        'privacy' => 'required',
        'category_id' => 'required',
        'member_count' => 'required',
        'post_count' => 'required',
        'status' => 'required'
    ];

    
}
