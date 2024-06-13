<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quest extends Model
{
    public $table = 'quests';

    public $fillable = [
        'name',
        'description',
        'recurrence',
        'cooldown',
        'claim_time',
        'condition',
        'reward',
        'sprint',
        'status',
        'user_id',
        'image',
        'module_id',
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'recurrence' => 'string',
        'cooldown' => 'string',
        'claim_time' => 'string',
        'condition' => 'string',
        'reward' => 'string',
        'sprint' => 'string',
        'status' => 'string',
        'user_id' => 'integer'
    ];

    public static array $rules = [
        'name' => 'required',
        'description' => 'required',
        'recurrence' => 'required',
        'cooldown' => 'required',
        'claim_time' => 'required',
        'condition' => 'required',
        'reward' => 'required',
        'sprint' => 'required',
        'status' => 'required',
        'user_id' => 'required',
        'module_id' => 'required'
    ];

    public function additionals()
    {
        return $this->hasMany(QuestAdditional::class);
    }
}
