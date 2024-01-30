<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\Welcome;
use App\Models\Invite;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

/**
 * @OA\Tag(
 *   name="auth",
 *   description="Endpoints for authorisation"
 * ),
 * @OA\Tag(
 *   name="user",
 *   description="API Endpoints of Users"
 * ),
 *  @OA\Tag(
 *  name="theme",
 *   description="API Endpoints of Theme"
 * ),
 *  @OA\Tag(
 *  name="statement",
 *   description="API Endpoints of Statement"
 * ),
 *  @OA\Tag(
 *   name="category",
 *   description="API Endpoints of Category"
 * ),
 * @OA\Tag(
 *  name="resource",
 *   description="API Endpoints of Resource. NB* Resources are uploaded to S3 AWS."
 * ),
 *  @OA\Tag(
 *  name="references",
 *   description="API Endpoints of References"
 * ),
 *  @OA\Tag(
 *  name="lexicon",
 *   description="API Endpoints of Lexicon"
 * ),
 *  @OA\Tag(
 *  name="glossary",
 *   description="API Endpoints of Glossary"
 * ),
 *  @OA\Tag(
 *  name="therapyarea",
 *   description="API Endpoints for Therapy Areas"
 * ),
 * @OA\Tag(
 *  name="search",
 *   description="API Endpoints for Search"
 * ),
 * @OA\Tag(
 *  name="notification",
 *   description="API Endpoints for notification"
 * ),
 */
class AuthController extends Controller implements ShouldQueue
{
    /**
     * @OA\Post(
     * path="/api/registration",
     * summary="Registration. **USERS CAN ONLY REGISTER VIA INVITE LINK**",
     * description="Registration. USERS CAN ONLY REGISTER VIA INVITE LINK",
     * operationId="authRegister",
     * tags={"user"},
     * security={ {"bearer": {} }},
     *
     * @OA\Response(
     *    response=201,
     *    description="Successful operation",
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Returns when user is not authenticated",
     *
     *    @OA\JsonContent(
     *
     *       @OA\Property(property="message", type="string", example="Not authorized"),
     *    )),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="User already exist in the system"
     *      )
     *
     * )
     */
    public function register(Request $request): Application|Factory|View
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->uncompromised(),
            ],
        ]);

        $input = $request->all();

        if (! isset($input['name']) || ! isset($input['password'])) {
            return view('errors.422');
        }

        try {
            // check the invitation table by invitation token
            $invite = Invite::where('token', $request->token)->first();

            if (! $invite) {
                return view('errors.422');
            }

            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $invite->email,
                'password' => bcrypt($request->password),
                'email_verified_at' => Carbon::now(),
                'remember_token' => $request->token,
                'password_changed_at' => Carbon::now(),
            ]);

            // insert role(s).
            $user->roles()->attach($invite->role_id);

            // send welcome email here...
            $email = new Welcome($user);
            //Mail::to ( $invite->email )->queue ( $email );
            Mail::to($invite->email)->send($email);

            $invite->delete();

            // return view('welcome');
        } catch (\Exception $e) {
            $errors = $e->getMessage();
            if (strpos($errors, ' Integrity constraint violation')) {
                $errors = 'The system is showing that you have already registered, please contact the site admin for assistance.';
            }

            return view('errors.500', compact('errors'));
        }

        return view('welcome');
    }

    /**
     * @OA\Post  (
     *     tags={"auth"},
     *     path="/api/login",
     *     summary="user login",
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 ref="#/components/schemas/User",
     *             )
     *         )
     *     ),
     *
     *      @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
     *     @OA\Response(response="200", description="An example resource", @OA\JsonContent(type="object", @OA\Property(format="string", default="20d338931e8d6bd9466edeba78ea7dce7c7bc01aa5cc5b4735691c50a2fe3228", description="token", property="token"))),
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'email|required',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);
        if (! auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        'invalid credentials',
                    ],
                ],
            ], status: 422);
        }

        // $user = User::where('email', $request->email)->value();
        $user = User::with(['roles' => function ($q) {
        }])
            ->where('email', $request->email)->first();

        $user->update(['updated_at' => Carbon::now()]);

        // set json data from the huge returned object
        $user_details = [
            'id' => $user->id,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'role' => $user->roles[0]->title,
            'password_changed_at' => $user->password_changed_at,
            'new_password_token' => app('auth.password.broker')->createToken($user),
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];

        $authToken = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'access_token' => $authToken,
            'user_details' => [$user_details],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        /**
         * @OA\Post(
         * path="/api/logout",
         * summary="Logout",
         * description="Logout user and invalidate token",
         * operationId="authLogout",
         * tags={"auth"},
         *  security={{ "Bearer":{} }},
         *
         * @OA\Response(
         *    response=205,
         *    description="No Content",
         *     ),
         * @OA\Response(
         *    response=401,
         *    description="Returns when user is not authenticated",
         *
         *    @OA\JsonContent(
         *
         *       @OA\Property(property="message", type="string", example="Not authorized"),
         *    )
         * )
         * )
         */
        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out',
        ], status: 205);
    }
}
