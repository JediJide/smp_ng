<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
// use App\Http\Requests\StoreAudienceRequest;
// use App\Http\Requests\UpdateAudienceRequest;
use App\Http\Resources\Admin\AudienceResource;
use App\Models\Audience;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AudienceApiController extends Controller
{
    public function index()
    {

        return new AudienceResource(Audience::all());
    }

    // public function store(StoreAudienceRequest $request)
    // {
    //     $Audience = Audience::create($request->all());

    //     return (new AudienceResource($Audience))
    //         ->response()
    //         ->setStatusCode(Response::HTTP_CREATED);
    // }

    // public function show(Audience $Audience)
    // {
    //     abort_if(Gate::denies('therapy_area_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     return new AudienceResource($Audience);
    // }

    // public function update(UpdateAudienceRequest $request, Audience $Audience)
    // {
    //     $Audience->update($request->all());

    //     return (new AudienceResource($Audience))
    //         ->response()
    //         ->setStatusCode(Response::HTTP_ACCEPTED);
    // }

    // public function destroy(Audience $Audience)
    // {
    //     abort_if(Gate::denies('therapy_area_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     $Audience->delete();

    //     return response(null, Response::HTTP_NO_CONTENT);
    // }
}
