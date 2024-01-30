<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return UserResource
     */

    /**
     * @OA\Get(
     *     tags={"user"},
     *     path="/api/v1/users",
     *     summary="get user list",
     *     security={{ "Bearer":{} }},
     *     @OA\Parameter(
     *         name="offset",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *         in="query",
     *         description="offset",
     *         example=0,
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *         in="query",
     *         description="offset",
     *         example=10,
     *         required=true,
     *     ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/UserResource"))))
     * )
     */
    public function index(User $user) : UserResource
    {
        // dd ();
        //return new UserResource(User::with(['roles'])->advancedFilter());
       // $user->getUserByEmail ();
       //return $user->getUsers ();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return UserResource
     */

    /**
     * @OA\Get(
     *     tags={"user"},
     *     path="/api/v1/users/{id}",
     *     summary="get user by Id",
     *     security={{ "Bearer":{} }},
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="user Id",
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiNotFoundException")),
     *     @OA\Response(response="200", description="success",@OA\JsonContent(ref="#/components/schemas/UserResource")))
     * )
     * @param  int  $id
     * @return UserResource
     */
    public function show(int $id): UserResource
    {
        return new UserResource(User::with(['roles'])->where('id', '=', $id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */

    /**
     * @OA\Put(
     *     path="/api/v1/users/{id}",
     *     tags={"user"},
     *     summary="Update user by Id",
     *     description="Update user by Id",
     *     security={{ "Bearer":{} }},
     *     @OA\RequestBody(
     *          request="person",
     *          required=false,
     *          description="Optional Request Parameters for Querying",
     *          @OA\JsonContent(ref="#/components/schemas/UpdateUserRequest")
     *      ),
     *
     *      @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="user Id",
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
     *              @OA\Items(ref="#/components/schemas/UpdateUserRequest")
     *          )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {

        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */

    /**
     * @OA\Delete(
     *     tags={"user"},
     *     path="/api/v1/users/{id}",
     *     summary="Delete user by Id",
     *     security={{ "Bearer":{} }},
     *     @OA\Parameter(
     *        name="id",
     *        in="path",
     *        description="user Id",
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        ),
     *        required=true,
     *        example=1
     *     ),
     *     @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="404", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiNotFoundException")),
     *     @OA\Response(response="204", description="success",@OA\JsonContent(ref="#/components/schemas/UserResource")))
     * )
     * @param  int  $id
     * @return void
     */
    public function destroy(int $id)
    {
        //
    }
}
