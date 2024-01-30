<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Lexicon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class LexiconTherapyAreaController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"lexicon"},
     *     path="/api/v1/lexicon-therapy-area/{id}",
     *     summary="get lexicon by THERAPY ID",
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
    public function getLexiconByTherapy(int $id, Lexicon $lexicon): LengthAwarePaginator
    {
        return $lexicon->getLexiconByTherapyId($id);
    }
}
