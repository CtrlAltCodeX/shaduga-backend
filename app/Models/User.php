<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    
    public $table = 'users';

    public $fillable = [
        'name',
        'email',
        'password',
        'otp'
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
