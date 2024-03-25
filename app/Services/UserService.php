<?php

namespace App\Services;

use App\DTO\AuthUserDTO;
use App\DTO\UserDTO;
use App\Repositories\UserRepository;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserService
{
    /**
     * @param UserRepository $repository
     */
    public function __construct(protected UserRepository $repository)
    {
    }

    /**
     * @param UserDTO $dto
     * @return string
     */
    public function store(UserDTO $dto): string
    {
        $user = $this->repository->make([
            'name'      => $dto->name,
            'email'     => $dto->email,
            'password'  => Hash::make($dto->password)
        ]);

        $user->save();
        return $user->createToken('authToken', ['*'], now()->addHour())->plainTextToken;
    }

    /**
     * @param AuthUserDTO $dto
     * @return string
     */
    public function getToken(AuthUserDTO $dto): string
    {
        $user = $this->repository->getWithWhereSingle([], [
            'email'=> $dto->email
        ]);

        if (!$user) {
            throw new ModelNotFoundException('model not found', ResponseAlias::HTTP_NOT_FOUND);
        }

        if (!Hash::check($dto->password, $user->password)) {
            throw new InvalidArgumentException('incorrect login or password', ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $user->createToken('authToken', ['*'], now()->addDay())->plainTextToken;
    }

}
