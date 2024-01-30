<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyThemeRequest;
use App\Http\Requests\StoreThemeRequest;
use App\Http\Requests\UpdateThemeRequest;
use App\Models\Category;
use App\Models\Reference;
use App\Models\Resource;
use App\Models\Theme;
use App\Models\TherapyArea;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ThemeController extends Controller
{
    use MediaUploadingTrait;

    public function index(): View
    {
        abort_if(Gate::denies('theme_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $themes = Theme::with(['therapy_area', 'category', 'resources', 'references'])->get();

        $therapy_areas = TherapyArea::get();

        $categories = Category::get();

        $resources = Resource::get();

        $references = Reference::get();

        return view('admin.themes.index', compact('categories', 'references', 'resources', 'themes', 'therapy_areas'));
    }

    public function create(): View
    {
        abort_if(Gate::denies('theme_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $therapy_areas = TherapyArea::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $resources = Resource::pluck('title', 'id');

        $references = Reference::pluck('title', 'id');

        return view('admin.themes.create', compact('categories', 'references', 'resources', 'therapy_areas'));
    }

    public function store(StoreThemeRequest $request): RedirectResponse
    {
        $theme = Theme::create($request->all());
        $theme->resources()->sync($request->input('resources', []));
        $theme->references()->sync($request->input('references', []));
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $theme->id]);
        }

        return redirect()->route('admin.themes.index');
    }

    public function edit(Theme $theme): View
    {
        abort_if(Gate::denies('theme_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $therapy_areas = TherapyArea::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $resources = Resource::pluck('title', 'id');

        $references = Reference::pluck('title', 'id');

        $theme->load('therapy_area', 'category', 'resources', 'references');

        return view('admin.themes.edit', compact('categories', 'references', 'resources', 'theme', 'therapy_areas'));
    }

    public function update(UpdateThemeRequest $request, Theme $theme): RedirectResponse
    {
        $theme->update($request->all());
        $theme->resources()->sync($request->input('resources', []));
        $theme->references()->sync($request->input('references', []));

        return redirect()->route('admin.themes.index');
    }

    public function show(Theme $theme): View
    {
        abort_if(Gate::denies('theme_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $theme->load('therapy_area', 'category', 'resources', 'references', 'themeStatements');

        return view('admin.themes.show', compact('theme'));
    }

    public function destroy(Theme $theme)
    {
        abort_if(Gate::denies('theme_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $theme->delete();

        return back();
    }

    public function massDestroy(MassDestroyThemeRequest $request): \Illuminate\Http\Response
    {
        Theme::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request): JsonResponse
    {
        abort_if(Gate::denies('theme_create') && Gate::denies('theme_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new Theme();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
