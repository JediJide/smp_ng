<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyGlossaryRequest;
use App\Http\Requests\StoreGlossaryRequest;
use App\Http\Requests\UpdateGlossaryRequest;
use App\Models\Glossary;
use App\Models\TherapyArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class GlossaryController extends Controller
{
    use MediaUploadingTrait;

    public function index(): View
    {
        abort_if(Gate::denies('glossary_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $glossaries = Glossary::with(['therapy_area'])->get();

        $therapy_areas = TherapyArea::get();

        return view('admin.glossaries.index', compact('glossaries', 'therapy_areas'));
    }

    public function create(): View
    {
        abort_if(Gate::denies('glossary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $therapy_areas = TherapyArea::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.glossaries.create', compact('therapy_areas'));
    }

    public function store(StoreGlossaryRequest $request): RedirectResponse
    {
        $glossary = Glossary::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $glossary->id]);
        }

        return redirect()->route('admin.glossaries.index');
    }

    public function edit(Glossary $glossary): View
    {
        abort_if(Gate::denies('glossary_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $therapy_areas = TherapyArea::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $glossary->load('therapy_area');

        return view('admin.glossaries.edit', compact('glossary', 'therapy_areas'));
    }

    public function update(UpdateGlossaryRequest $request, Glossary $glossary): RedirectResponse
    {
        $glossary->update($request->all());

        return redirect()->route('admin.glossaries.index');
    }

    public function show(Glossary $glossary): View
    {
        abort_if(Gate::denies('glossary_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $glossary->load('therapy_area');

        return view('admin.glossaries.show', compact('glossary'));
    }

    public function destroy(Glossary $glossary)
    {
        abort_if(Gate::denies('glossary_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $glossary->delete();

        return back();
    }

    public function massDestroy(MassDestroyGlossaryRequest $request): \Illuminate\Http\Response
    {
        Glossary::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request): JsonResponse
    {
        abort_if(Gate::denies('glossary_create') && Gate::denies('glossary_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new Glossary();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
