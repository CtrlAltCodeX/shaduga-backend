<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class Module extends Model
{
    public $table = 'modules';

    public $fillable = [
        'title',
        'desc',
        'community_id'
    ];

    protected $casts = [
        'title' => 'string',
        'desc' => 'string'
    ];

    public static array $rules = [
        'title' => 'required',
        'desc' => 'required',
        'community_id' => 'required'
    ];

    public function quest()
    {
        return $this->hasMany(Quest::class);
    }

    public function community()
    {
        return $this->hasOne(Community::class, 'id', 'community_id');
    }
}
