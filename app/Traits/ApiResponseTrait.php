<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * @return JsonResponse , status: true, payload: string[]|mixed}
     */
    public function respond($payload, $status): JsonResponse
    {
        if(is_string($payload)) {
            $payload = ['message' => $payload];
        }

        return response()->json([
            'code'      => $status,
            'payload'   => $payload
        ], $status);
    }
}
