<?php

namespace App\Repositories;

use App\Foundation\BaseRepository;
use App\Models\User;
class UserRepository extends BaseRepository
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(User::class);
    }
}
