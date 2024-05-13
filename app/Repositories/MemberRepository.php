<?php

namespace App\Repositories;

use App\Models\Member;
use App\Repositories\BaseRepository;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

class MemberRepository extends EloquentBaseRepository
{
    protected $fieldSearchable = [
        'community_id',
        'user_id',
        'join_date',
        'status',
        'role',
        'last_active'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Member::class;
    }
}
