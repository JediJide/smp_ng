<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTherapyAreaRequest;
use App\Http\Resources\Admin\TherapyAreaResource;
use App\Models\TherapyArea;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TherapyAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     tags={"therapyarea"},
     *     path="/api/v1/therapyareas",
     *     summary="get glossary list",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/TherapyAreaResource"))))
     * )
     */
    public function index(): TherapyAreaResource
    {
        return new TherapyAreaResource(TherapyArea::all('id', 'name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */

    /**
     * @OA\Post  (
     *     tags={"therapyarea"},
     *     path="/api/v1/therapyareas",
     *     summary="Add new therapyarea",
     *      security={{ "Bearer":{} }},
     *     description="Add new therapy area",
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/TherapyArea",
     *             )
     *         )
     *     ),
     *
     *      @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201", description="Message", @OA\JsonContent(type="object", @OA\Property(format="string", default="Therapy Area created", description="message", property="message"))),
     * )
     */
    public function store(StoreTherapyAreaRequest $request): object
    {
        $therapy_area = TherapyArea::create($request->validated());

        return (new TherapyAreaResource($therapy_area))
            ->response()
            ->setStatusCode(ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */

    /**
     * @OA\Get(
     *     tags={"therapyarea"},
     *     path="/api/v1/therapyareas/{id}",
     *     summary="get therapyarea by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="therapy areas",
     *
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiNotFoundException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(ref="#/components/schemas/TherapyAreaResource")))
     * )
     *
     * @return Collection|TherapyArea[]
     */
    public function show(int $id): Collection|array
    {
        return TherapyArea::all()->where('id', $id);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *     path="/api/v1/therapyareas/{id}",
     *     tags={"therapyarea"},
     *     summary="Update therapyarea by Id",
     *     description="Update therapyareas by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\RequestBody(
     *          request="person",
     *          required=false,
     *          description="Optional Request Parameters for Querying",
     *
     *          @OA\JsonContent(ref="#/components/schemas/UpdateTherapyAreaRequest")
     *      ),
     *
     *      @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="TherapyArea Id",
     *
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
     *
     *          @OA\JsonContent(
     *              type="array",
     *
     *              @OA\Items(ref="#/components/schemas/UpdateTherapyAreaRequest")
     *          )
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        TherapyArea::query()->where('id', '=', $id)
            ->update(['name' => $request->name]);

        $updated_therapy_area = TherapyArea::all()->where('id', $id);

        return response()->json([
            'Glossaries' => [$updated_therapy_area],
        ], '202');
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * @OA\Delete(
     *     tags={"therapyarea"},
     *     path="/api/v1/therapyarea/{id}",
     *     summary="Delete therapyarea by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="therapyarea Id",
     *
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiNotFoundException")),
     *     @OA\Response(response="202", description="success",@OA\JsonContent(ref="#/components/schemas/MassDestroyTherapyAreaRequest")))
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        TherapyArea::destroy($id);

        return response()->json('resource has been deleted', '202');
    }
}
