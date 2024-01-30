<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTherapyAreaRequest;
use App\Http\Requests\UpdateTherapyAreaRequest;
use App\Http\Resources\Admin\TherapyAreaResource;
use App\Models\TherapyArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class TherapyAreaApiController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('therapy_area_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TherapyAreaResource(TherapyArea::all());
    }

    public function store(StoreTherapyAreaRequest $request)
    {
        $therapyArea = TherapyArea::create($request->all());

        return (new TherapyAreaResource($therapyArea))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TherapyArea $therapyArea)
    {
        abort_if(Gate::denies('therapy_area_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TherapyAreaResource($therapyArea);
    }

    public function update(UpdateTherapyAreaRequest $request, TherapyArea $therapyArea)
    {
        $therapyArea->update($request->all());

        return (new TherapyAreaResource($therapyArea))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(TherapyArea $therapyArea)
    {
        abort_if(Gate::denies('therapy_area_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $therapyArea->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
