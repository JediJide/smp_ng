<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyResourceRequest;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Models\Resource;
use App\Models\User;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ResourcesController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('resource_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $resources = Resource::with(['user', 'media'])->get();

        $users = User::get();

        return view('admin.resources.index', compact('resources', 'users'));
    }

    public function create()
    {
        abort_if(Gate::denies('resource_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.resources.create', compact('users'));
    }

    public function store(StoreResourceRequest $request): RedirectResponse
    {
        $resource = Resource::create($request->all());

        foreach ($request->input('filename', []) as $file) {
            $resource->addMedia(storage_path('tmp/uploads/'.basename($file)))->toMediaCollection('filename');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $resource->id]);
        }

        return redirect()->route('admin.resources.index');
    }

    public function edit(Resource $resource)
    {
        abort_if(Gate::denies('resource_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('email', 'id')->prepend(trans('global.pleaseSelect'), '');

        $resource->load('user');

        return view('admin.resources.edit', compact('resource', 'users'));
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

        return redirect()->route('admin.resources.index');
    }

    public function show(Resource $resource)
    {
        abort_if(Gate::denies('resource_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $resource->load('user', 'resourceThemes', 'resourceStatements');

        return view('admin.resources.show', compact('resource'));
    }

    public function destroy(Resource $resource)
    {
        abort_if(Gate::denies('resource_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $resource->delete();

        return back();
    }

    public function massDestroy(MassDestroyResourceRequest $request)
    {
        Resource::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('resource_create') && Gate::denies('resource_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new Resource();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
