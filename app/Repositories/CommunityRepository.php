<?php

namespace App\Repositories;

use App\Models\Community;
use App\Repositories\BaseRepository;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

class CommunityRepository extends EloquentBaseRepository
{
    protected $fieldSearchable = [
        'name',
        'owner_id',
        'privacy',
        'category_id',
        'member_count',
        'post_count',
        'status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Community::class;
    }
}
