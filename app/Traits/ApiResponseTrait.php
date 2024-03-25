<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait ApiResponseTrait
{
    /**
     * @return array{code: , status: true, payload: string[]|mixed}
     */
    public function respond($payload, $status): array
    {
        if(is_string($payload)) {
            $payload = ['msg' => $payload];
        }
        return [
            'code'      => $status,
            'payload'   => $payload
        ];
    }

    public function respondWithPagination($payload, $status): array
    {
        dd($payload);
        if(is_string($payload)) {
            $payload = ['msg' => $payload];
        }
        return [
            'code'      => $status,
            'payload'   => $payload
        ];
    }
}
