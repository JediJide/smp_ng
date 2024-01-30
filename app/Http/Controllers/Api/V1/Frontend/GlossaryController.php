<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Glossary;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlossaryController extends Controller
{
    private Glossary $glossary;

    public function __construct(Glossary $glossary)
    {
        $this->glossary = $glossary;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Glossary $glossary
     * @return JsonResponse
     */

    /**
     * @OA\Get(
     *     tags={"glossary"},
     *     path="/api/v1/glossary",
     *     summary="get glossary list",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/GlossaryResource"))))
     * )
     */
    public function index(Glossary $glossary): JsonResponse
    {
        $glossaries = $glossary->getAllGlossary();

        return response()->json([
            'Glossaries' => [$glossaries],
        ], '200');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Glossary $glossary
     * @return JsonResponse
     */

    /**
     * @OA\Post  (
     *     tags={"glossary"},
     *     path="/api/v1/glossary",
     *     summary="Add new glossary",
     *      security={{ "Bearer":{} }},
     *     description="Add new glossary",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/Glossary",
     *             )
     *         )
     *     ),
     *      @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201", description="Message", @OA\JsonContent(type="object", @OA\Property(format="string", default="Statement created", description="message", property="message"))),
     * )
     */
    public function store(Request $request, Glossary $glossary): JsonResponse
    {
        $request->validate([
            'therapy_area_id' => 'required|integer',
            'term' => 'required',

        ]);

        $data = [
            [
                'term' => $request->term,
                'definition' => $request->definition,
                'therapy_area_id' => $request->therapy_area_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        $insert_id = $glossary->createNewCategory($data);

        $latest_glossary = $glossary->getAllGlossaryById($insert_id);

        return response()->json([
            'Glossaries' => [$latest_glossary],
        ], '201');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */

    /**
     * @OA\Get(
     *     tags={"glossary"},
     *     path="/api/v1/glossary/{id}",
     *     summary="get glossary by Id",
     *     security={{ "Bearer":{} }},
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="Glossary by Id",
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiNotFoundException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(ref="#/components/schemas/GlossaryResource")))
     * )
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $latest_glossary = $this->glossary->getAllGlossaryById($id);

        return response()->json([
            'Glossaries' => [$latest_glossary],
        ], '200');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */

    /**
     * @OA\Put(
     *     path="/api/v1/glossary/{id}",
     *     tags={"glossary"},
     *     summary="Update glossary by Id",
     *     description="Update glossary by Id",
     *     security={{ "Bearer":{} }},
     *     @OA\RequestBody(
     *          request="person",
     *          required=false,
     *          description="Optional Request Parameters for Querying",
     *          @OA\JsonContent(ref="#/components/schemas/UpdateGlossaryRequest")
     *      ),
     *
     *      @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="Statement Id",
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *
     *     @OA\Response(
     *          response="200",
     *          description="Returns matching Person Object",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/UpdateStatementRequest")
     *          )
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $glossary_arrays =
            ['term' => $request->term,
                'definition' => $request->definition,
                'therapy_area_id' => $request->therapy_area_id,
                'updated_at' => Carbon::now(),
            ];

        $this->glossary->updateGlossaryById($id, $glossary_arrays);

        $updated_glossary = $this->glossary->getAllGlossaryById($id);

        return response()->json([
            'Glossaries' => [$updated_glossary],
        ], '202');
    }

    /**
     * @OA\Delete(
     *     tags={"glossary"},
     *     path="/api/v1/glossary/{id}",
     *     summary="Delete glossary by Id",
     *     security={{ "Bearer":{} }},
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="glossary Id",
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiNotFoundException")),
     *     @OA\Response(response="202", description="success",@OA\JsonContent(ref="#/components/schemas/MassDestroyGlossaryRequest")))
     * )
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        Glossary::destroy($id);

        return response()->json('resource has been deleted', '202');
    }
}
