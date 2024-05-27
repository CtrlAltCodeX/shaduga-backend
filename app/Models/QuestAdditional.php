<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestAdditional extends Model
{
    public $table = 'quest_additionals';

    public $fillable = [
        'quest_id',
        'link',
        'partnership',
        'number_invitation',
        'description',
        'endpoint',
        'api_key',
        'methods',
        'task_type',
        'request_type',
        'correct_answer',
        'stars',
        'steps',
        'labels',
        'files',
    ];

    protected $casts = [
        'quest_id' => 'integer',
        'link' => 'string',
        'partnership' => 'string',
        'number_invitation' => 'string',
        'description' => 'string',
        'endpoint' => 'string',
        'api_key' => 'string',
        'methods' => 'string',
        'task_type' => 'string',
    ];

    public static array $rules = [
        'quest_id' => 'required',
        'quest_id' => 'required',
        'link' => 'required',
        'partnership' => 'required',
        'number_invitation' => 'required',
        'description' => 'required',
        'endpoint' => 'required',
        'api_key' => 'required',
        'methods' => 'required',
        'task_type' => 'required',
    ];
}
