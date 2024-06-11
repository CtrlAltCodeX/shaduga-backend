<?php

namespace App\Repositories;

use App\Models\Module;
use App\Repositories\BaseRepository;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

class ModuleRepository extends EloquentBaseRepository
{
    protected $fieldSearchable = [
        'title',
        'desc',
        'community_id',
        'user_id',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Module::class;
    }
}
