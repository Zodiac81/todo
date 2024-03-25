<?php

namespace App\Foundation;

use App\Contracts\RepositoryContract;
use Illuminate\Database\Eloquent\Model;
class BaseRepository implements RepositoryContract
{
    /**
     * @var string
     */
    protected $modelClass = Model::class;

    /**
     * @param $model
     */
    public function __construct($model)
    {
        $this->modelClass = $model;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->modelClass;
    }

    /**
     * @param array $data
     * @return Model
     */
    public function make(array $data): Model
    {
        return $this->getModel()::make($data);
    }

    /**
     * @param array $with
     * @param array $where
     * @return mixed
     */
    public function getWithWhereSingle(array $with, array $where): mixed
    {
        return $this->getModel()::with($with)->where($where)->first();
    }

    /**
     * @param array $with
     * @param array $where
     * @return mixed
     */
    public function getWithWhere(array $with, array $where): mixed
    {
        return $this->getModel()::with($with)->where($where)->get();
    }

}
