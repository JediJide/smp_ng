<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Glossary;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlossaryTherapyAreaController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"glossary"},
     *     path="/api/v1/glossary-therapy-area/{id}",
     *     summary="get Glossary THERAPY ID",
     *     security={{ "Bearer":{} }},
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="therapy_area_id",
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiNotFoundException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(ref="#/components/schemas/LexiconResource")))
     * )
     */
    public function getGlossaryByTherapyArea($therapy_area_id, Glossary $glossary): JsonResponse
    {
        $result = $glossary->getGlossaryByTherapyArea($therapy_area_id);

        return response()->json($result);
    }
}
