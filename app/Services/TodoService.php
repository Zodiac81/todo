<?php

namespace App\Services;

use App\DTO\ApiTodoDTO;
use App\DTO\TodoDTO;
use App\Repositories\TodoRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
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
     * @return Model
     */
    public function store(TodoDTO $dto): Model
    {
        $model = $this->repository->make([
            'title'         => $dto->title,
            'description'   => $dto->description,
            'user_id'       => Auth::id()
        ]);

        $model->save();
        return $model;
    }

    /**
     * @param ApiTodoDTO $dto
     * @return mixed
     */
    public function delete(ApiTodoDTO $dto): mixed
    {
        $model = $this->repository->getWithWhereSingle([], [
            ['user_id', '=', Auth::id()],
            ['id', '=', $dto->id]
        ]);

        if (!$model) {
            throw new ModelNotFoundException('model not found', ResponseAlias::HTTP_NOT_FOUND);
        }

       return $model->delete();
    }

    /**
     * @param ApiTodoDTO $dto
     * @return Model
     */
    public function update(ApiTodoDTO $dto): Model
    {
        $model = $this->repository->getWithWhereSingle([], [
            ['user_id', '=', Auth::id()],
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
    public function getAll(array $with, array $where): mixed
    {
        return $this->repository->getWithWhere($with, $where);
    }

}
