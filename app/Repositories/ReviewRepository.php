<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\BaseRepository;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

class ReviewRepository extends EloquentBaseRepository
{
    protected $fieldSearchable = [
        'user_id',
        'rating',
        'title',
        'body',
        'status',
        "bookmarked",
        "image"
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Review::class;
    }
}
