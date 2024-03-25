<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RepositoryContract
{
    /**
     * @param array $with
     * @param array $where
     * @return mixed
     */
    public function getWithWhereSingle(array $with, array $where): mixed;

    /**
     * @param array $with
     * @param array $where
     * @return mixed
     */
    public function getWithWhere(array $with, array $where): mixed;

    /**
     * @param array $data
     * @return Model
     */
    public function make(array $data): Model;

}
