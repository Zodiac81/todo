<?php

namespace App\Http\Controllers;

use App\DTO\AuthUserDTO;
use App\DTO\UserDTO;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\Auth\TokenRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends BaseController
{
    public function __construct(protected UserService $userService)
    {
    }

    /**
     * SignUp
     *
     * @OA\Post (
     *      path="/auth/sign-up",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                       type="object",
     *                       @OA\Property(
     *                           property="name",
     *                           type="string"
     *                       ),
     *                       @OA\Property(
     *                           property="email",
     *                           type="string",
     *                           pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$",
     *                           format="email"
     *                       ),
     *                       @OA\Property(
     *                           property="password",
     *                           type="string"
     *                       )
     *                  ),
     *                  example={
     *                      "name":"John",
     *                      "email":"john@test.com",
     *                      "password":"johnjohn1"
     *                 }
     *              )
     *          )
     *       ),
     *       @OA\Response(
     *           response=201,
     *           description="Created",
     *           @OA\JsonContent(
     *               @OA\Property(property="code", type="string", example="201"),
     *               @OA\Property(property="payload", type="object",
     *                   @OA\Property(property="access_token", type="object",
     *                       @OA\Property(property="token", type="string", example="1|randomtokenasfhajskfhajf398rureuuhfdshk"),
     *                   ),
     *               ),
     *           )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Validation error",
     *           @OA\JsonContent(
     *               @OA\Property(property="meta", type="object",
     *                   @OA\Property(property="code", type="number", example=422),
     *                   @OA\Property(property="status", type="string", example="error"),
     *                   @OA\Property(property="message", type="object",
     *                       @OA\Property(property="email", type="array", collectionFormat="multi",
     *                         @OA\Items(
     *                           type="string",
     *                           example="The email has already been taken.",
     *                           )
     *                       ),
     *                   ),
     *               ),
     *           )
     *       )
     *  )
     *
     * @param SignupRequest $request
     * @return JsonResponse
     */
    public function signUp(SignupRequest $request): JsonResponse
    {
        try {
            $token = $this->userService->store(new UserDTO($request->validated()));
        } catch (Exception $exception) {
            return $this->respond($exception->getMessage(), $exception->getCode());
        }

        return $this->respond(['token' => $token], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Login
     *
     * @OA\Post (
     *      path="/auth/token",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                       type="object",
     *                       @OA\Property(
     *                           property="email",
     *                           type="string"
     *                       ),
     *                       @OA\Property(
     *                           property="password",
     *                           type="string"
     *                       )
     *                  ),
     *                  example={
     *                      "email":"john@test.com",
     *                      "password":"johnjohn1"
     *                 }
     *              )
     *          )
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Valid credentials",
     *           @OA\JsonContent(
     *               @OA\Property(property="code", type="number", example=200),
     *               @OA\Property(property="payload", type="object",
     *                 @OA\Property(property="token", type="string", example="1|randomtokenasfhajskfhajf398rureuuhfdshk"),
     *               ),
     *           )
     *       ),
     *       @OA\Response(
     *           response=401,
     *           description="Not found",
     *           @OA\JsonContent(
     *               @OA\Property(property="code", type="number", example=401),
     *               @OA\Property(property="payload", type="object",
     *                   @OA\Property(property="message", type="string", example="user not found"),
     *               ),
     *           )
     *       ),
     *       @OA\Response(
     *            response=422,
     *            description="Invalid credentials",
     *            @OA\JsonContent(
     *                @OA\Property(property="code", type="number", example=422),
     *                @OA\Property(property="payload", type="object",
     *                    @OA\Property(property="message", type="string", example="these credentials do not match our records"),
     *                ),
     *            )
     *        ),
     *  )
     *
     * @param TokenRequest $request
     * @return JsonResponse
     */
    public function getToken(TokenRequest $request): JsonResponse
    {
        try {
            $token = $this->userService->getToken(new AuthUserDTO($request->validated()));
        } catch (Exception $exception) {
            return $this->respond($exception->getMessage(), $exception->getCode());
        }
        return $this->respond(['token' => $token], ResponseAlias::HTTP_OK);
    }

    /**
     * Logout
     *
     * @OA\Post (
     *      path="/auth/logout",
     *      tags={"Auth"},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                  ),
     *              )
     *          )
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Success",
     *           @OA\JsonContent(
     *                @OA\Property(property="code", type="number", example=200),
     *                @OA\Property(property="payload", type="bool", example="true")
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
     *  )
     *
     *  @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        return $this->respond(
            Auth::user()->currentAccessToken()->delete(),
            ResponseAlias::HTTP_OK
        );
    }
}
