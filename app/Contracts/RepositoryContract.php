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
    public function getWithWhereSingle(array $where, array $with): mixed;

    /**
     * @param array $with
     * @param array $where
     * @return mixed
     */
    public function getWithWhere(array $where, array $with): mixed;

    /**
     * @param array $data
     * @return Model
     */
    public function make(array $data): Model;

}
