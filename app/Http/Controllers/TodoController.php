<?php

namespace App\Http\Controllers;

use App\DTO\ApiTodoDTO;
use App\DTO\TodoDTO;
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
     * Index
     *
     *  @OA\Get (
     *      path="/todos",
     *      tags={"Todo"},
     *      summary="Get auth user todos",
     *       @OA\Parameter(
     *          description="Amount items per page",
     *          in="query",
     *          name="perPage",
     *          example="3",
     *          @OA\Schema(
     *            type="integer",
     *            format="int64"
     *          )
     *       ),
     *       @OA\Parameter(
     *          description="Pagination page index",
     *          in="query",
     *          name="page",
     *          example="1",
     *          @OA\Schema(
     *            type="integer",
     *            format="int64"
     *          )
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *           @OA\JsonContent(
     *               @OA\Property(property="code", type="number", example=200),
     *               @OA\Property(property="payload", type="object",
     *                        @OA\Property(property="data", type="object",
     *                        @OA\Property(property="todo", type="object",
     *                        @OA\Property(property="id", type="number", example=2),
     *                        @OA\Property(property="title", type="string", example="Title"),
     *                        @OA\Property(property="description", type="string", example="Some description"),
     *                        @OA\Property(property="updated_at", type="string", example="2024-01-30 06:06:17"),
     *                        @OA\Property(property="created_at", type="string", example="2024-01-30 06:06:17"),
     *                    ),
     *                ),
     *               ),
     *           )
     *       ),
     *       @OA\Response(
     *           response=401,
     *           description="Invalid token",
     *           @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="Unauthenticated."),
     *           )
     *       ),
     *       security={
     *          {"sanctum": {}}
     *      }
     * )
     *
     * @param TodoRequest $request
     * @return JsonResponse
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
     * Store
     *
     * @OA\Post (
     *       path="/todos",
     *       tags={"Todo"},
     *       summary="Store todo",
     *       security={{"sanctum": {}}},
     *       @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"title"},
     *                      @OA\Property(
     *                          property="title",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string",
     *                      ),
     *                      example={
     *                          "title":"New todo title",
     *                          "description":"New todo description"
     *                      }
     *              )
     *          )
     *       ),
     *       @OA\Response(
     *            response=201,
     *            description="Created",
     *            @OA\JsonContent(
     *                @OA\Property(property="code", type="number", example=201),
     *                @OA\Property(property="payload", type="object",
     *                      @OA\Property(property="id", type="number", example=2),
     *                      @OA\Property(property="title", type="string", example="Title"),
     *                      @OA\Property(property="description", type="string", example="Some description"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-01-30 06:06:17"),
     *                      @OA\Property(property="created_at", type="string", example="2024-01-30 06:06:17"),
     *                 ),
     *             ),
     *        ),
     *        @OA\Response(
     *            response=401,
     *            description="Invalid token",
     *            @OA\JsonContent(
     *                 @OA\Property(property="message", type="string", example="Unauthenticated."),
     *            )
     *        ),
     *        @OA\Response(
     *            response=422,
     *            description="Validation errors",
     *            @OA\JsonContent(
     *                @OA\Property(property="title", type="array", example={
     *                    {"message":"The title field is required"},
     *                    {"message":"The title field must not be more than 255 characters"},
     *                    {"message":"The title field must not be less than 3 characters"}
     *                    },
     *                    @OA\Items(
     *                         type="string",
     *                         example="The title field is required",
     *                    ),
     *                    @OA\Items(
     *                         type="string",
     *                         example="The title field must not be more than 255 characters",
     *                    ),
     *                    @OA\Items(
     *                         type="string",
     *                         example="The title field must not be less than 3 characters",
     *                    ),
     *                ),
     *              @OA\Property(property="description", type="array", example={
     *                     {"message":"The title field must not be more than 1000 characters"},
     *                     {"message":"The title field must not be less than 3 characters"}},
     *                     @OA\Items(
     *                          type="string",
     *                          example="The description field must not be more than 1000 characters",
     *                     ),
     *                     @OA\Items(
     *                          type="string",
     *                          example="The description field must not be less than 3 characters",
     *                     ),
     *                 )
     *            )
     *        ),
     *  )
     *
     * @param TodoRequest $request
     * @return JsonResponse
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
     * Edit
     *
     * @OA\Patch(
     *       path="/todos/{id}",
     *       operationId="editTodo",
     *       tags={"Todo"},
     *       summary="Edit todo",
     *       description="Returns edited todo data",
     *       security={{"sanctum":{}}},
     *      @OA\Parameter(
     *           name="id",
     *           description="Todo id",
     *           required=true,
     *           in="path",
     *           example="1",
     *           @OA\Schema(
     *               type="integer",
     *               format="int64"
     *           )
     *       ),
     *     @OA\RequestBody(
     *           @OA\MediaType(
     *               mediaType="application/json",
     *               @OA\Schema(
     *                       @OA\Property(
     *                           property="title",
     *                           type="string",
     *                       ),
     *                       @OA\Property(
     *                           property="description",
     *                           type="string",
     *                       ),
     *                       example={
     *                           "title":"Updated todo title",
     *                           "description":"Updated todo description"
     *                       }
     *               )
     *           )
     *        ),
     *       @OA\Response(
     *           response=202,
     *           description="Accepted",
     *           @OA\JsonContent(
     *               @OA\Property(property="code", type="number", example=202),
     *               @OA\Property(property="payload", type="object",
     *                     @OA\Property(property="id", type="number", example=1),
     *                     @OA\Property(property="title", type="string", example="Updated Title"),
     *                     @OA\Property(property="description", type="string", example="Updated description"),
     *                     @OA\Property(property="updated_at", type="string", example="2024-01-30 06:06:17"),
     *                     @OA\Property(property="created_at", type="string", example="2024-01-30 06:06:17"),
     *                  ),
     *            ),
     *        ),
     *       @OA\Response(
     *           response=422,
     *           description="Unprocessable Entity",
     *           @OA\JsonContent(
     *                 @OA\Property(property="title", type="array", example={
     *                     {"message":"The title field must be present in request body"},
     *                     {"message":"The title field must not be more than 255 characters"},
     *                     {"message":"The title field must not be less than 3 characters"}},
     *                     @OA\Items(
     *                          type="string",
     *                          example="The title field must be present in request body",
     *                     ),
     *                     @OA\Items(
     *                          type="string",
     *                          example="The title field must not be more than 255 characters",
     *                     ),
     *                     @OA\Items(
     *                          type="string",
     *                          example="The title field must not be less than 3 characters",
     *                     ),
     *               ),
     *               @OA\Property(property="description", type="array", example={
     *                      {"message":"The description field must be present in request body"},
     *                      {"message":"The description field must not be more than 1000 characters"},
     *                      {"message":"The description field must not be less than 3 characters"}},
     *                      @OA\Items(
     *                           type="string",
     *                           example="The description field must not be more than 1000 characters",
     *                      ),
     *                      @OA\Items(
     *                           type="string",
     *                           example="The description field must not be less than 3 characters",
     *                      ),
     *                      @OA\Items(
     *                           type="string",
     *                           example="The description field must be present in request body",
     *                      ),
     *                  )
     *              )
     *       ),
     *       @OA\Response(
     *           response=401,
     *           description="Unauthenticated",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Unauthenticated."),
     *           )
     *       ),
     *       @OA\Response(
     *           response=404,
     *           description="Not found",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Not found."),
     *           )
     *       )
     *  )
     *
     * @param TodoRequest $request
     * @param ToDo $todo
     * @return JsonResponse
     */
    public function update(TodoRequest $request, int $id): JsonResponse
    {
        try{
            return $this->respond(
                new TodoResource($this->todoService->update(new ApiTodoDTO($id, $request->title, $request->description), $this->userID)),
                ResponseAlias::HTTP_ACCEPTED
            );
        } catch (Exception $exception) {
            return $this->respond($exception->getMessage(), $exception->getCode());
        }
    }


    /**
     * Destroy
     *
     * @OA\Delete(
     *       path="/todos/{id}",
     *       operationId="deleteTodo",
     *       tags={"Todo"},
     *       description="Delete todo",
     *       summary="Delete todo",
     *       security={{"sanctum":{}}},
     *       @OA\Parameter(
     *           name="id",
     *           description="Todo id",
     *           required=true,
     *           in="path",
     *           example=1,
     *           @OA\Schema(
     *               type="integer",
     *               format="int64"
     *           )
     *       ),
     *       @OA\Response(
     *           response=204,
     *           description="No Content",
     *        ),
     *       @OA\Response(
     *            response=401,
     *            description="Unauthenticated",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="Unauthenticated."),
     *            )
     *        ),
     *        @OA\Response(
     *            response=404,
     *            description="Not found",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="Not found."),
     *            )
     *        )
     *  )
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try{
            return $this->respond($this->todoService->delete($id, $this->userID), ResponseAlias::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return $this->respond($exception->getMessage(), $exception->getCode());
        }
    }
}
