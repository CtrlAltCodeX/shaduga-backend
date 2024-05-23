<?php

namespace App\Repositories;

use App\Models\Community;
use App\Repositories\BaseRepository;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

class CommunityRepository extends EloquentBaseRepository
{
    protected $fieldSearchable = [
        'logo',
        'name',
        'description',
        'is_blockchain',
        'website',
        'categories',
        'invites',
        'link',
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
