<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Scientific Messaging Platform REST API Doc",
 *    version="2.0.0",
 * )
 * )
 */
class Controller extends BaseController
{
    /**
     * @OA\SecurityScheme(
     *       scheme="Bearer",
     *       securityScheme="Bearer",
     *       type="http",
     *       in="header",
     *       name="Authorization",
     * )
     */
    use AuthorizesRequests, ValidatesRequests;
}
