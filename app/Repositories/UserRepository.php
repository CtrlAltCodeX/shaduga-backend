<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

class UserRepository extends EloquentBaseRepository
{
    protected $fieldSearchable = [
        'name',
        'email',
        'password'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return User::class;
    }
}
