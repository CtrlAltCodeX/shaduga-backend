<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public $table = 'subscriptions';

    public $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'user_id' => 'string',
        'plan_id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public static array $rules = [
        'user_id' => 'required',
        'plan_id' => 'required',
        'start_date' => 'required',
        'end_date' => 'required',
        'status' => 'required'
    ];

    
}
