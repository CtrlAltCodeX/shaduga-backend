<?php

namespace App\Repositories;

use App\Models\QuestAdditional;
use App\Repositories\BaseRepository;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

class QuestAdditionalRepository extends EloquentBaseRepository
{
    protected $fieldSearchable = [
        'quest_id',
        'link',
        'partnership',
        'number_invitation',
        'description',
        'endpoint',
        'api_key',
        'methods',
        'task_type',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return QuestAdditional::class;
    }
}
