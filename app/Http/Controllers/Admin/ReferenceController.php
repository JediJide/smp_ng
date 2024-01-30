<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyReferenceRequest;
use App\Http\Requests\StoreReferenceRequest;
use App\Http\Requests\UpdateReferenceRequest;
use App\Models\Reference;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ReferenceController extends Controller
{
    use MediaUploadingTrait;

    public function index(): View
    {
        abort_if(Gate::denies('reference_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $references = Reference::with(['media'])->get();

        return view('admin.references.index', compact('references'));
    }

    public function create(): View
    {
        abort_if(Gate::denies('reference_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.references.create');
    }

    public function store(StoreReferenceRequest $request): RedirectResponse
    {
        $reference = Reference::create($request->all());

        foreach ($request->input('file', []) as $file) {
            $reference->addMedia(storage_path('tmp/uploads/'.basename($file)))->toMediaCollection('file');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $reference->id]);
        }

        return redirect()->route('admin.references.index');
    }

    public function edit(Reference $reference): View
    {
        abort_if(Gate::denies('reference_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.references.edit', compact('reference'));
    }

    public function update(UpdateReferenceRequest $request, Reference $reference): RedirectResponse
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

        return redirect()->route('admin.references.index');
    }

    public function show(Reference $reference): View
    {
        abort_if(Gate::denies('reference_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $reference->load('referenceThemes', 'referenceStatements');

        return view('admin.references.show', compact('reference'));
    }

    public function destroy(Reference $reference)
    {
        abort_if(Gate::denies('reference_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $reference->delete();

        return back();
    }

    public function massDestroy(MassDestroyReferenceRequest $request): \Illuminate\Http\Response
    {
        Reference::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request): JsonResponse
    {
        abort_if(Gate::denies('reference_create') && Gate::denies('reference_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new Reference();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
