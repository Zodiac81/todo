<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="TODO API Documentation",
 *      description="TODO API documentation",
 *      @OA\Contact(
 *          email="aleksey.derevyanko81@gmail.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API Server"
 * )
 *
 * @OA\Tag(
 *     name="Auth",
 *     description="API Endpoints for user auth"
 * )
 *
 * @OA\Tag(
 *     name="Todo",
 *     description="API Endpoints for todo`s"
 * )
 *
 */
class BaseController extends Controller
{
    use ApiResponseTrait;


}
