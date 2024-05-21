<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public $table = 'reviews';

    public $fillable = [
        'user_id',
        'rating',
        'title',
        'body',
        'status',
        'image',
        "bookmarked"
    ];

    protected $casts = [
        'user_id' => 'string',
        'rating' => 'integer',
        'title' => 'string',
        'body' => 'string'
    ];

    public static array $rules = [
        'user_id' => 'required',
        'rating' => 'required',
        'title' => 'required',
        'body' => 'required',
        'status' => 'required',
        'image' => "required",
        "bookmarked" => "required"
    ];
}
