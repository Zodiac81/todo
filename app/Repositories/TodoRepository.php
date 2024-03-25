<?php

namespace App\Repositories;

use App\Foundation\BaseRepository;
use App\Models\ToDo;

class TodoRepository extends BaseRepository
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(ToDo::class);
    }
}
