<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $table = 'users';

    public $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'password' => 'string'
    ];

    public static array $rules = [
        'name' => 'required',
        'email' => 'email',
        'password' => 'required'
    ];

    
}
