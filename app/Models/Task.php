<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $table = 'tasks';

    public $fillable = [
        'name',
        'description',
        'status',
        'start',
        'end',
        'category_id',
        'assigned_to'
    ];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'start' => 'date',
        'end' => 'date',
        'category_id' => 'integer',
        'assigned_to' => 'integer'
    ];

    public static array $rules = [
        'name' => 'required',
        'description' => 'required',
        'status' => 'required',
        'start' => 'required',
        'end' => 'required',
        'category_id' => 'required',
        'assigned_to' => 'required'
    ];

    
}
