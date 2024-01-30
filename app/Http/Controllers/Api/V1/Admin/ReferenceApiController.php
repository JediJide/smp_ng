<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreReferenceRequest;
use App\Http\Requests\UpdateReferenceRequest;
use App\Http\Resources\Admin\ReferenceResource;
use App\Models\Reference;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ReferenceApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('reference_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ReferenceResource(Reference::all());
    }

    public function store(StoreReferenceRequest $request)
    {
        $reference = Reference::create($request->all());

        foreach ($request->input('file', []) as $file) {
            $reference->addMedia(storage_path('tmp/uploads/'.basename($file)))->toMediaCollection('file');
        }

        return (new ReferenceResource($reference))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Reference $reference)
    {
        abort_if(Gate::denies('reference_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ReferenceResource($reference);
    }

    public function update(UpdateReferenceRequest $request, Reference $reference)
    {
        $reference->update($request->all());

        if (count($reference->file) > 0) {
            foreach ($reference->file as $media) {
                if (! in_array($media->file_name, $request->input('file', []))) {
                    $media->delete();
                }
            }
        }
        $media = $reference->file->pluck('file_name')->toArray();
        foreach ($request->input('file', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $reference->addMedia(storage_path('tmp/uploads/'.basename($file)))->toMediaCollection('file');
            }
        }

        return (new ReferenceResource($reference))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Reference $reference)
    {
        abort_if(Gate::denies('reference_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $reference->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
