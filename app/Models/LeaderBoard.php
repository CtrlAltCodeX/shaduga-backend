<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaderBoard extends Model
{
    public $table = 'leader_boards';

    public $fillable = [
        'user_id',
        'score',
        'rank',
        'level'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'score' => 'string',
        'rank' => 'string',
        'level' => 'string'
    ];

    public static array $rules = [
        'user_id' => 'required',
        'score' => 'required',
        'rank' => 'required',
        'level' => 'required'
    ];

    
}
