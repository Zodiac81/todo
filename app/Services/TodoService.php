<?php

namespace App\Services;

use App\DTO\ApiTodoDTO;
use App\DTO\TodoDTO;
use App\Repositories\TodoRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TodoService
{
    /**
     * @param TodoRepository $repository
     */
    public function __construct(protected TodoRepository $repository)
    {
    }

    /**
     * @param TodoDTO $dto
     * @param int $userId
     * @return Model
     */
    public function store(TodoDTO $dto, int $userId): Model
    {
        $model = $this->repository->make([
            'title'         => $dto->title,
            'description'   => $dto->description,
            'user_id'       => $userId
        ]);

        $model->save();
        return $model;
    }

    /**
     * @param int $id
     * @param int $userId
     * @return mixed
     */
    public function delete(int $id, int $userId): mixed
    {
        $model = $this->repository->getWithWhereSingle([
            ['user_id', '=', $userId],
            ['id', '=', $id]
        ]);

        if (!$model) {
            throw new ModelNotFoundException('model not found', ResponseAlias::HTTP_NOT_FOUND);
        }

       return $model->delete();
    }

    /**
     * @param ApiTodoDTO $dto
     * @param int $userId
     * @return Model
     */
    public function update(ApiTodoDTO $dto, int $userId): Model
    {
        $model = $this->repository->getWithWhereSingle([
            ['user_id', '=', $userId],
            ['id', '=', $dto->id]
        ]);

        if (!$model) {
            throw new ModelNotFoundException('model not found', ResponseAlias::HTTP_NOT_FOUND);
        }

        $model->update([
            'title'       => $dto->title,
            'description' => $dto->description
        ]);

        return $model;
    }

    /**
     * @param array $with
     * @param array $where
     * @return mixed
     */
    public function getAll(array $where = [], array $with = []): mixed
    {
        return $this->repository->getWithWhere($where, $with);
    }

}
