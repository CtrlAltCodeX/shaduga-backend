<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\BaseRepository;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

class TaskRepository extends EloquentBaseRepository
{
    protected $fieldSearchable = [
        'name',
        'description',
        'status',
        'start',
        'end',
        'category_id',
        'assigned_to'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Task::class;
    }
}
