<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\InviteCreated;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InviteController extends Controller implements ShouldQueue
{
    public function invite(Request $request): Factory|View|Application
    {
        abort_unless($request->hasValidSignature(), 401, 'That link has expired or is no longer valid!');

        $user_object = Invite::where('token', $request->token)->first();

        // abort if email no longer exist in the invite table
        abort_if(empty($user_object), 401, 'That link has expired or is no longer valid!');

        $email = $user_object->email;
        $ui_url = env('APP_UI_URL');

        return view('auth.registration', compact('email', 'ui_url'));
    }

    public function process(Request $request): JsonResponse
    {

        /**
         * @OA\Post  (
         *     tags={"user"},
         *     path="/api/invite",
         *     summary="Invite user by email",
         *      security={{ "Bearer":{} }},
         *     description="Invite user by email",
         *
         *     @OA\RequestBody(
         *
         *         @OA\MediaType(
         *             mediaType="application/x-www-form-urlencoded",
         *
         *             @OA\Schema(
         *                 type="object",
         *                 ref="#/components/schemas/Invite",
         *             )
         *         )
         *     ),
         *
         *      @OA\Response(response="401", description="fail", @OA\JsonContent(ref="#/components/schemas/ApiRequestException")),
         *     @OA\Response(response="202", description="Message", @OA\JsonContent(type="object", @OA\Property(format="string", default="Invitation Sent", description="message", property="message"))),
         * )
         */
        // validate the incoming request data
        $request->validate([
            'email' => 'required',
            'role' => 'required',
        ]);

        // in case of re-invitation, clear the user from the invite table
        $invite = Invite::where('email', $request->email)->first();

        if (! empty($invite)) {
            $invite->delete();
        }
        // check if user has already registered.
        $check_user = User::where('email', $request->email)->first();
        if (! empty($check_user)) {
            abort(401, 'This user is already registered and is in the system');
        }

        //then create a new invite process.
        do {
            // generate a random string using Str::random helper
            $token = Str::random(40);
        } // check if the token already exists and if it does, try again
        while (Invite::where('token', $token)->first());

        // create a new invite record
        try {
            $invite = Invite::create([
                'email' => $request->get('email'),
                'token' => $token,
                'password_changed_at' => Carbon::now(),
            ]);

            // Had to update invites table, insert wasn't working on initial create function.
            DB::table('invites')
                ->where('email', $request->get('email'))
                ->update(['role_id' => $request->role]);

        } catch (\Exception $e) {
            // Database error
            return response()->json($e->getMessage(), '500');
        }

        // put email in a 2 minutes queue
        $when = now()->addMinutes(2);
        // send the email

        $email = new InviteCreated($invite);

        Mail::to($request->get('email'))->send($email);
        //Mail::to($request->get('email'))->queue ($email);
        // Mail::to($request->get('email'))->later ($when, $email) ;

        // redirect back where we came from
        return response()->json('Invitation Sent', '202');
    }

    public function accept($token): string
    {
        // Look up the invite, the link expires, send them back to the admin team. Need an email contact?
        if (! $invite = Invite::where('token', $token)->first()) {
            return response()->json([
                'message' => 'We do not recognize sending an invitation to your email address. Please contact the site\'s administrator for further assistance',
                'errors' => [
                    'email' => [
                        'invalid credential',
                    ],
                ],
            ], status: 422);
        }
        // create the user with the details from the invite
        $new_user = User::create(['email' => $invite->email]);

        // then insert the role
        $role_data = ['user_id' => $invite->id, 'role_id' => $invite->role_id];
        DB::table('role_user')->insert($role_data);

        // Invitation deleted from the database to avoid duplicate invitation record, or should we keep it and set a flag? TBC with the team
        $invite->delete();

        // here you would probably log the user in and show them the dashboard, but we'll just prove it worked
        return response()->json([
            'message' => 'Welcome message',
        ], status: 201);
    }

    public function importUser()
    {
        $path = storage_path().'/json/user_smp.json';

        $user_details = json_decode(file_get_contents($path), true);

        $user_details_len = count($user_details);

        $i = 0;
        foreach ($user_details as $user_detail) {
            // $email =  $user_detail['Email'];

            //  print_r ($email);
            //   dd ();

            $user = User::create([
                'name' => $user_detail['FirstName'],
                'last_name' => $user_detail['LastName'],
                'email' => $user_detail['Email'],
                'password' => bcrypt('password123!'),
                'email_verified_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // insert role(s).
            $user->roles()->attach(2);

            $i++;
        }

        // return $user_details[0]['Email'];

        return response()->json([
            'message' => 'User Import DOne',
        ], status: 422);
    }
}
