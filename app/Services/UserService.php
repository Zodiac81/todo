<?php

namespace App\Services;

use App\DTO\AuthUserDTO;
use App\DTO\UserDTO;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
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
        return $user->createToken('authToken', ['*'], now()->addDay())->plainTextToken;
    }

    /**
     * @param AuthUserDTO $dto
     * @return string
     */
    public function getToken(AuthUserDTO $dto): string
    {
        $user = $this->repository->getWithWhereSingle([], [
            'email' => $dto->email
        ]);

        if (!$user) {
            throw new ModelNotFoundException('user not found', ResponseAlias::HTTP_NOT_FOUND);
        }

        if (!Hash::check($dto->password, $user->password)) {
            throw new InvalidArgumentException('these credentials do not match our records', ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $user->createToken('authToken', ['*'], now()->addDay())->plainTextToken;
    }

}
