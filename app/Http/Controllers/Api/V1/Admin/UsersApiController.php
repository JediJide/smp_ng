<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UsersApiController extends Controller
{
    public function index(): UserResource
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new UserResource(User::with(['roles' => fn ($query) => $query->select('id', 'title')])
            ->paginate(500, ['id', 'name', 'last_name', 'email', 'email_verified_at']));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));

        return (new UserResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(User $user): UserResource
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new UserResource($user->load(['roles']));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));

        return (new UserResource($user->load(['roles'])))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(User $user): \Illuminate\Http\Response|Application|ResponseFactory
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $check_invitation_data = DB::table('invites')->where('email', $user->email)->get();

        //remove redundant invitation data from invites table
        if (! $check_invitation_data->isEmpty()) {
            foreach ($check_invitation_data as $invitation_datum) {
                DB::table('invites')->delete($invitation_datum->id);
            }
        }

        //$user->delete();
        $user->forceDelete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
