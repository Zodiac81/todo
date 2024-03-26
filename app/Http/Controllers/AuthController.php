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
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        return $this->respond(
            Auth::user()->currentAccessToken()->delete(),
            ResponseAlias::HTTP_OK
        );
    }
}
