<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @OA\OpenApi(
 *    @OA\Info(
 *        version="1.0.0",
 *        title="Social Networking Platform API",
 *        description="This is the API documentation for the Social Networking Platform.",
 *        @OA\Contact(
 *            email="support@socialnetworkingplatform.com"
 *        ),
 *        @OA\License(
 *            name="Apache 2.0",
 *            url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *        )
 *    ),
 *    @OA\Server(
 *        url="http://localhost:8000/api",
 *        description="Local development server"
 *    )
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
