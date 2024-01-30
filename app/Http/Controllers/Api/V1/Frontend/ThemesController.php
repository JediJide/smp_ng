<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Statement;
use App\Models\Theme;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Symfony\Component\HttpFoundation\Response;

class ThemesController extends Controller
{
    use HasRecursiveRelationships;
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     path="/api/v1/theme/category/{category_id}/view/{view_type}",
     *     tags={"theme"},
     *     summary="Get list by category_id and view_type",
     *     description="Get list by category_id and view_type",
     *     security={{ "Bearer":{} }},
     *
     *      @OA\Parameter(
     *        name="category_id",
     *        in="path",
     *        description="Category Id",
     *
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *
     *     @OA\Parameter(
     *        name="view_type",
     *        in="path",
     *        description="view_type",
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
     *          description="Returns matching Theme Object",
     *
     *          @OA\JsonContent(
     *              type="array",
     *
     *              @OA\Items(ref="#/components/schemas/UpdateThemeRequest")
     *          )
     *     )
     * )
     */
    public function index($therapy_area_id, $category_id, $view, $audience_id, Theme $theme): JsonResponse
    {
        // future-proof to handle themes display types
        $view = intval($view);
        $result = match ($view) {
            1 => $theme->getThemesTopLevel($therapy_area_id, $category_id, $audience_id),
            2 => $theme->themeStatementTree($therapy_area_id, $category_id, $audience_id),

            default => 'Theme '.$view.' does not exist.',
        };

        $result = $this->replaceKey($result, 'theme_statements', 'SubStatement');
        $result = $this->replaceKey($result, 'resources_themes', 'StatementResources');
        $result = $this->replaceKey($result, 'audiences', 'Audiences');

        return response()->json($result);
    }

    // function to rename JSON elements
    public function replaceKey($array, $old, $new)
    {
        // flatten the array into a JSON string
        $str = json_encode($array);
        // colon after the closing quote will ensure only keys are targeted
        $str = str_replace('"'.$old.'":', '"'.$new.'":', $str);

        // restore JSON string to array
        return json_decode($str, true);
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post  (
     *     tags={"theme"},
     *     path="/api/v1/theme",
     *     summary="Add new category",
     *      security={{ "Bearer":{} }},
     *     description="Add new theme",
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/Theme",
     *             )
     *         )
     *     ),
     *
     *      @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201", description="Message", @OA\JsonContent(type="object", @OA\Property(format="string", default="Category created", description="message", property="message"))),
     * )
     */
    public function store(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            'name' => 'required',
        ]);

        $theme = [
            'name' => $request->name,
            'description' => $request->description,
            'therapy_area_id' => $request->therapy_area_id,
            'category_id' => $request->category_id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        //clear themes statement cache from statement
        Cache::forget('themes_statement_tree');
        //  clear statement cache from statement
        Cache::forget('statement_tree');

        $theme_model = new Theme();

        $theme_latest_id = $theme_model->insertTheme($theme);
        if (isset($request->resource_id)) {
            //convert form string list into int
            $string_resourceIDs = $request->resource_id;
            $resourceIDs = array_map('intval', explode(',', $string_resourceIDs));
            //store data into link table resources
            Theme::find($theme_latest_id)->resources()->sync($resourceIDs);

            //update resource link field
            foreach ($resourceIDs as $resourceID) {
                Resource::where('id', $resourceID)
                    ->update(['is_linked' => 1]);
            }
        }

        $theme = $theme_model->getThemeById($theme_latest_id)->toArray();

        return response($theme, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */

    /**
     * @OA\Get(
     *     tags={"theme"},
     *     path="/api/v1/theme/{id}",
     *     summary="get theme by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="Get theme by Id",
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
     *     @OA\Response(response="200", description="success",@OA\JsonContent(ref="#/components/schemas/ThemeResource")))
     * )
     */
    public function show(Theme $theme, int $id): JsonResponse
    {
        // $theme = $theme->getThemeById ($id);
        return response()->json($theme->getThemeById($id));
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/api/v1/theme/{id}",
     *     tags={"theme"},
     *     summary="Update theme by Id",
     *     description="Update theme by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\RequestBody(
     *          request="person",
     *          required=false,
     *          description="Optional Request Parameters for Querying",
     *
     *          @OA\JsonContent(ref="#/components/schemas/UpdateThemeRequest")
     *      ),
     *
     *      @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="Theme Id",
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
     *          description="Returns matching Theme Object",
     *
     *          @OA\JsonContent(
     *              type="array",
     *
     *              @OA\Items(ref="#/components/schemas/UpdateThemeRequest")
     *          )
     *     )
     * )
     */
    public function update(Request $request, int $id): \Illuminate\Http\Response
    {
        $request->validate([
            'name' => 'required',
        ]);
        // clear themes statement cache from statement
        Cache::forget('themes_statement_tree');
        // clear statement cache from statement
        Cache::forget('statement_tree');

        $theme = new Theme();

        $theme->updateThemeById($id, $request->name, $request->description);

        $theme_id = Theme::find($id);

        if (isset($request->resource_id)) {
            //convert form string list into int
            $string_resourceIDs = $request->resource_id;
            $resourceIDs = array_map('intval', explode(',', $string_resourceIDs));

            $theme_id->resources()->sync($resourceIDs);

            //update resource link field
            foreach ($resourceIDs as $resourceID) {
                Resource::where('id', $resourceID)
                    ->update(['is_linked' => 1]);
            }
        } else {
            //if resource_id not set or is set to "" then remove all resource links
            $theme_id->resources()->sync([]);
        }

        $theme = $theme->getThemeById($id)->toArray();

        return response($theme, Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return int
     */

    /**
     * @OA\Delete(
     *     tags={"theme"},
     *     path="/api/v1/theme/{id}",
     *     summary="Delete theme by Id",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="theme Id",
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
     *     @OA\Response(response="202", description="success",@OA\JsonContent(ref="#/components/schemas/MassDestroyThemeRequest")))
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        Theme::destroy($id);

        return response()->json('resource has been deleted', '202');
    }
}
