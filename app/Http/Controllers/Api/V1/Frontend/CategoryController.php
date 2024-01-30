<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Category[]|Collection
     */
    /**
     * @OA\Get(
     *     tags={"category"},
     *     path="/api/v1/category",
     *     summary="get category list",
     *     security={{ "Bearer":{} }},
     *
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CategoryResource"))))
     * )
     */
    public function index(): Collection|array
    {
        return Category::all('id', 'name');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */

    /**
     * @OA\Post  (
     *     tags={"category"},
     *     path="/api/v1/category",
     *     summary="Add new category",
     *      security={{ "Bearer":{} }},
     *     description="Add new category",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/Category",
     *             )
     *         )
     *     ),
     *      @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="201", description="Message", @OA\JsonContent(type="object", @OA\Property(format="string", default="Category created", description="message", property="message"))),
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'therapy_area_id' => 'required',
            'name' => 'required', ]);

        return Category::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param Category $category
     * @return array
     */

    /**
     * @OA\Get(
     *     tags={"category"},
     *     path="/api/v1/category/{id}",
     *     summary="get category by Id",
     *     security={{ "Bearer":{} }},
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="Get category by Id",
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiNotFoundException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(ref="#/components/schemas/CategoryResource")))
     * )
     * @param int $id
     * @param Category $category
     * @return array
     */
    public function show(int $id, Category $category): array
    {
        return $category->getCategoryById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */

    /**
     * @OA\Put(
     *     path="/api/v1/category/{id}",
     *     tags={"category"},
     *     summary="Update category by Id",
     *     description="Update category by Id",
     *     security={{ "Bearer":{} }},
     *     @OA\RequestBody(
     *          request="category",
     *          required=false,
     *          description="Optional Request Parameters for Querying",
     *          @OA\JsonContent(ref="#/components/schemas/UpdateCategoryRequest")
     *      ),
     *
     *      @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="Category Id",
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
     *          description="Returns matching Category Object",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/UpdateCategoryRequest")
     *          )
     *     )
     * )
     */
    public function update(Request $request, int $id)
    {
        $category = Category::find($id);
        $category->update($request->all());

        return $category;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Application|ResponseFactory|Response
     */

    /**
     * @OA\Delete(
     *     tags={"category"},
     *     path="/api/v1/category/{id}",
     *     summary="Delete category by Id",
     *     security={{ "Bearer":{} }},
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="category Id",
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiNotFoundException")),
     *     @OA\Response(response="202", description="success",@OA\JsonContent(ref="#/components/schemas/MassDestroyCategoryRequest")))
     * )
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        Category::destroy($id);

        return response()->json('resource has been deleted', '202');
    }
}
