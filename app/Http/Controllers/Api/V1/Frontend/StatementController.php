<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Statement;
use App\Models\Theme;
use App\Services\StatementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatementController extends Controller
{
    private Theme $theme;

    private Statement $statement;

    public function __construct(Theme $theme, Statement $statement)
    {
        $this->theme = $theme;
        $this->statement = $statement;
    }
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     tags={"statement"},
     *     path="/api/v1/statement/category/{category_id}",
     *     summary="get statement by category_id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Parameter(
     *        name="category_id",
     *        in="path",
     *        description="Get Statements with Theme",
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
     *     @OA\Response(response="200", description="success",@OA\JsonContent(ref="#/components/schemas/StatementResource")))
     * )
     */
    public function index(int $therapy_area_id, int $category): JsonResponse //, $view = null $category
    {
        $statement = $this->theme->getThemesTopLevel($therapy_area_id, $category);

        return response()->json($statement);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */

    /**
     * @OA\Post  (
     *     tags={"statement"},
     *     path="/api/v1/statement",
     *     summary="Add new statement",
     *      security={{ "Bearer":{} }},
     *     description="Add new statement",
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/Statement",
     *             )
     *         )
     *     ),
     *
     *      @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201", description="Message", @OA\JsonContent(type="object", @OA\Property(format="string", default="Statement created", description="message", property="message"))),
     * )
     */
    public function store(Request $request, StatementService $service, Statement $statement): object
    {
        // clear themes statement cache from statement
        // Cache::forget('themes_statement_tree');
        // clear statement cache from statement
        //  Cache::forget('statement_tree');

        return $service->createStatement($request, $statement);
    }

    /**
     * @OA\Get(
     *     tags={"statement"},
     *     path="/api/v1/statement/{id}",
     *     summary="get statement by Theme Id. Endpoint will return related theme, with associated statement(s), if exists",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="Theme with statements",
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
     *     @OA\Response(response="200", description="success",@OA\JsonContent(ref="#/components/schemas/StatementResource")))
     * )
     */
    public function show(int $id): JsonResponse
    {
        // get statement parent child, add references and resources payloads.
        $statement = new Statement();
        $theme = $this->theme->getThemeById($id);
        $Theme['Theme'] = $theme;
        $StatementResources['StatementResources'] = $statement->getThemeResources($id);

        // Get main sub statement tree relationships by theme_id from either cache or new query
        $statement_tree = $statement->statementTree($id);

        // bring in replace key function
        $statement_tree = $this->replaceKey($statement_tree, 'parent_statements', 'SubStatement');
        $statement_tree = $this->replaceKey($statement_tree, 'resources', 'StatementResources');
        $statement_tree = $this->replaceKey($statement_tree, 'references', 'References');
        $statement_tree = $this->replaceKey($statement_tree, 'audiences', 'Audiences');

        $SubStatement['SubStatement'] = $statement_tree;

        $merged = array_merge($Theme, $SubStatement, $StatementResources);

        return response()->json($merged);
    }

    public function replaceKey($array, $old, $new)
    {
        //flatten the array into a JSON string
        $str = json_encode($array);
        // colon after the closing quote will ensure only keys are targeted
        $str = str_replace('"'.$old.'":', '"'.$new.'":', $str);

        // restore JSON string to array
        return json_decode($str, true);
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/api/v1/statement/{id}",
     *     tags={"statement"},
     *     summary="Update statement by Id",
     *     description="Update statement by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\RequestBody(
     *          request="person",
     *          required=false,
     *          description="Optional Request Parameters for Querying",
     *
     *          @OA\JsonContent(ref="#/components/schemas/UpdateStatementRequest")
     *      ),
     *
     *      @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="Statement Id",
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
     *              @OA\Items(ref="#/components/schemas/UpdateStatementRequest")
     *          )
     *     )
     * )
     */
    public function update(Request $request, Statement $statement, StatementService $statementService, int $id): object
    {
        // clear themes statement cache from statement
        //  Cache::forget('themes_statement_tree');
        // clear statement cache from statement
        // Cache::forget('statement_tree');

        $statement_id = $statementService->editStatement($request, $statement, $id);

        return response()->json($statement_id, '202');
    }

    /**
     * @OA\Delete(
     *     tags={"statement"},
     *     path="/api/v1/statement/{id}",
     *     summary="Delete statement by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="statement Id",
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
     *     @OA\Response(response="202", description="success",@OA\JsonContent(ref="#/components/schemas/MassDestroyStatementRequest")))
     * )
     */
    public function destroy(StatementService $statementService, int $id): JsonResponse
    {
        // // clear statement cache from statement
        // Cache::forget('statement_tree');
        // Statement::destroy($id);

        $statementService->deleteStatement($id);

        return response()->json('statement has been deleted', '202');
        // return response(null, ResponseAlias::HTTP_NO_CONTENT);
    }
}
