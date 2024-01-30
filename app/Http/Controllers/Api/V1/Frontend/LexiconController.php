<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLexiconRequest;
use App\Http\Requests\UpdateLexiconRequest;
use App\Http\Resources\Admin\LexiconResource;
use App\Models\Lexicon;
use App\Models\TherapyArea;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class LexiconController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */

    /**
     * @OA\Get(
     *     tags={"lexicon"},
     *     path="/api/v1/lexicon",
     *     summary="get lexicon list",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/LexiconResource"))))
     * )
     */
    public function index(Lexicon $lexicon): LengthAwarePaginator
    {
        //show all lexicon
        return $lexicon->getAllLexicon();
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post  (
     *     tags={"lexicon"},
     *     path="/api/v1/lexicon",
     *     summary="Add new lexicon",
     *      security={{ "Bearer":{} }},
     *     description="Add new lexicon area",
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/Lexicon",
     *             )
     *         )
     *     ),
     *
     *      @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201", description="Message", @OA\JsonContent(type="object", @OA\Property(format="string", default="Therapy Area created", description="message", property="message"))),
     * )
     */
    public function store(StoreLexiconRequest $request): object
    {
        //print_r ($request->all ());
        // dd ();
        $lexicon = Lexicon::create($request->validated());

        return (new LexiconResource($lexicon))
            ->response()
            ->setStatusCode(ResponseAlias::HTTP_CREATED);
    }

    public function create(): Response|Application|ResponseFactory
    {
        abort_if(Gate::denies('lexicon_create'), ResponseAlias::HTTP_FORBIDDEN, '403 Forbidden');

        return response([
            'meta' => [
                'therapy_area' => TherapyArea::get(['id', 'name']),
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */

    /**
     * @OA\Get(
     *     tags={"lexicon"},
     *     path="/api/v1/lexicon/{id}",
     *     summary="get lexicon by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="lexicon",
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
     *     @OA\Response(response="200", description="success",@OA\JsonContent(ref="#/components/schemas/LexiconResource")))
     * )
     */
    public function show(Lexicon $lexicon, int $id): JsonResponse
    {
        return response()->json($lexicon->getLexiconById($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateLexiconRequest  $request
     */

    /**
     * @OA\Put(
     *     path="/api/v1/lexicon/{id}",
     *     tags={"lexicon"},
     *     summary="Update lexicon by Id",
     *     description="Update lexicon by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\RequestBody(
     *          request="person",
     *          required=false,
     *          description="Optional Request Parameters for Querying",
     *
     *          @OA\JsonContent(ref="#/components/schemas/UpdateLexiconRequest")
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
    public function update(Request $request, int $id, Lexicon $lexicon): Collection
    {
        $lexicon_arrays =
            ['preferred_phrase' => $request->preferred_phrase,
                'guidance_for_usage' => $request->guidance_for_usage,
                'non_preferred_terms' => $request->non_preferred_terms,
                'therapy_area_id' => $request->therapy_area_id,
                'updated_at' => Carbon::now(),
            ];

        $lexicon->updateLexiconById($id, $lexicon_arrays);

        return $lexicon->getLexiconById($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     tags={"lexicon"},
     *     path="/api/v1/lexicon/{id}",
     *     summary="Delete lexicon by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="lexicon Id",
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
     *     @OA\Response(response="202", description="success",@OA\JsonContent(ref="#/components/schemas/MassDestroyLexiconRequest")))
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        Lexicon::destroy($id);

        return response()->json('resource has been deleted', '202');
        // return response(null, ResponseAlias::HTTP_NO_CONTENT);
    }
}
