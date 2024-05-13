<?php

namespace App\Repositories;

use App\Models\Chat;
use App\Repositories\BaseRepository;
use Prettus\Repository\Eloquent\BaseRepository as EloquentBaseRepository;

class ChatRepository extends EloquentBaseRepository
{
    protected $fieldSearchable = [
        'sender_id',
        'receiver_id',
        'message',
        'status',
        'conversation_id',
        'type'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Chat::class;
    }
}
