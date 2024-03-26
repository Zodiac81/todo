<?php

namespace App\Http\Controllers;

use App\DTO\TodoDTO;
use App\DTO\ApiTodoDTO;
use App\Http\Requests\Todo\TodoRequest;
use App\Http\Resources\TodoCollection;
use App\Http\Resources\TodoResource;
use App\Models\ToDo;
use App\Services\PaginationService;
use App\Services\TodoService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TodoController extends BaseController
{
    /**
     * @var int|string
     */
    protected int|string $userID;
    /**
     * @param TodoService $todoService
     */
    public function __construct(protected TodoService $todoService)
    {
        $this->userID = Auth::id();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(TodoRequest $request): JsonResponse
    {
        return $this->respond(
            PaginationService::paginate(
                new TodoCollection($this->todoService->getAll(['user_id' => $this->userID])->sortByDesc('created_at')),
                $request->perPage ?? PaginationService::DEFAULT_PER_PAGE_VALUE),
            ResponseAlias::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request): JsonResponse
    {
        try {
            return $this->respond(
                new TodoResource($this->todoService->store(new TodoDTO($request->validated()), $this->userID)),
                ResponseAlias::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return $this->respond($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoRequest $request, ToDo $todo): JsonResponse
    {
        try{
            return $this->respond(
                new TodoResource($this->todoService->update(new ApiTodoDTO($todo->id, $request->title, $request->description), $this->userID)),
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (Exception $exception) {
            return $this->respond($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ToDo $todo): JsonResponse
    {
        try{
            return $this->respond($this->todoService->delete(new ApiTodoDTO($todo->id, $todo->title, $todo->description), $this->userID), ResponseAlias::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return $this->respond($exception->getMessage(), $exception->getCode());
        }
    }
}
