<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Http\Resources\Admin\ResourceResource;
use App\Models\Resource;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ResourcesApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('resource_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ResourceResource(Resource::with(['user'])->get());
    }

    public function store(StoreResourceRequest $request)
    {
        $resource = Resource::create($request->all());

        foreach ($request->input('filename', []) as $file) {
            $resource->addMedia(storage_path('tmp/uploads/'.basename($file)))->toMediaCollection('filename');
        }

        return (new ResourceResource($resource))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Resource $resource)
    {
        abort_if(Gate::denies('resource_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ResourceResource($resource->load(['user']));
    }

    public function update(UpdateResourceRequest $request, Resource $resource)
    {
        $resource->update($request->all());

        if (count($resource->filename) > 0) {
            foreach ($resource->filename as $media) {
                if (! in_array($media->file_name, $request->input('filename', []))) {
                    $media->delete();
                }
            }
        }
        $media = $resource->filename->pluck('file_name')->toArray();
        foreach ($request->input('filename', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $resource->addMedia(storage_path('tmp/uploads/'.basename($file)))->toMediaCollection('filename');
            }
        }

        return (new ResourceResource($resource))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Resource $resource): \Illuminate\Http\Response
    {
        abort_if(Gate::denies('resource_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $resource->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
