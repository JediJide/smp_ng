<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyLexiconRequest;
use App\Http\Requests\StoreLexiconRequest;
use App\Http\Requests\UpdateLexiconRequest;
use App\Models\Lexicon;
use App\Models\TherapyArea;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class LexiconController extends Controller
{
    use MediaUploadingTrait;

    public function index(): View
    {
        abort_if(Gate::denies('lexicon_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lexicons = Lexicon::with(['therapy_area'])->get();

        $therapy_areas = TherapyArea::get();

        return view('admin.lexicons.index', compact('lexicons', 'therapy_areas'));
    }

    public function create(): View
    {
        abort_if(Gate::denies('lexicon_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $therapy_areas = TherapyArea::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.lexicons.create', compact('therapy_areas'));
    }

    public function store(StoreLexiconRequest $request): RedirectResponse
    {
        $lexicon = Lexicon::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $lexicon->id]);
        }

        return redirect()->route('admin.lexicons.index');
    }

    public function edit(Lexicon $lexicon): View
    {
        abort_if(Gate::denies('lexicon_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $therapy_areas = TherapyArea::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lexicon->load('therapy_area');

        return view('admin.lexicons.edit', compact('lexicon', 'therapy_areas'));
    }

    public function update(UpdateLexiconRequest $request, Lexicon $lexicon): RedirectResponse
    {
        $lexicon->update($request->all());

        return redirect()->route('admin.lexicons.index');
    }

    public function show(Lexicon $lexicon): View
    {
        abort_if(Gate::denies('lexicon_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lexicon->load('therapy_area');

        return view('admin.lexicons.show', compact('lexicon'));
    }

    public function destroy(Lexicon $lexicon)
    {
        abort_if(Gate::denies('lexicon_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lexicon->delete();

        return back();
    }

    public function massDestroy(MassDestroyLexiconRequest $request): \Illuminate\Http\Response
    {
        Lexicon::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request): JsonResponse
    {
        abort_if(Gate::denies('lexicon_create') && Gate::denies('lexicon_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new Lexicon();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
