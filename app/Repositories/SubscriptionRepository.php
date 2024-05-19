<?php

namespace App\Repositories;

use App\Models\Subscription;
use App\Repositories\BaseRepository;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

class SubscriptionRepository extends EloquentBaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'plan_id',
        'start_date',
        'end_date',
        'status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Subscription::class;
    }
}
