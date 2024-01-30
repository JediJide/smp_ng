<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AudienceResource;
use App\Models\Audience;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AudienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AudienceResource
     */

    /**
     * @OA\Get(
     *     tags={"Audience"},
     *     path="/api/v1/Audiences",
     *     summary="get audience list",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/AudienceResource"))))
     * )
     */
    public function index(): AudienceResource
    {
        return new AudienceResource(Audience::all('id', 'name'));
    }


}
