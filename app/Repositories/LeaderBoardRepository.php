<?php

namespace App\Repositories;

use App\Models\LeaderBoard;
use App\Repositories\BaseRepository;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

class LeaderBoardRepository extends EloquentBaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'score',
        'rank',
        'level'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return LeaderBoard::class;
    }
}
