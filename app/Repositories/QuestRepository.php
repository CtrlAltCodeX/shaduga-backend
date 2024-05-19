<?php

namespace App\Repositories;

use App\Models\Quest;
use App\Repositories\BaseRepository;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

class QuestRepository extends EloquentBaseRepository
{
    protected $fieldSearchable = [
        'recurrence',
        'cooldown',
        'claim_time',
        'condition',
        'reward',
        'module',
        'sprint',
        'status',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Quest::class;
    }
}
