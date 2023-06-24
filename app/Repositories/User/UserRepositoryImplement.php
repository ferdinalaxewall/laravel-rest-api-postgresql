<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepositoryImplement extends BaseRepository implements UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}