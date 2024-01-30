<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyStatementRequest;
use App\Http\Requests\StoreStatementRequest;
use App\Http\Requests\UpdateStatementRequest;
use App\Models\Audience;
use App\Models\Reference;
use App\Models\Resource;
use App\Models\Statement;
use App\Models\StatementStatus;
use App\Models\Theme;
use App\Models\TherapyArea;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class StatementController extends Controller
{
    use MediaUploadingTrait;

    public function index(): Factory|View|Application
    {
        abort_if(Gate::denies('statement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statements = Statement::get();

        $therapy_areas = TherapyArea::get();

        $themes = Theme::get();

        $resources = Resource::get();

        $references = Reference::get();

        $audiences = Audience::get();

        $statement_statuses = StatementStatus::get();

        return view('admin.statements.index', compact('references', 'resources', 'statement_statuses', 'statements', 'themes', 'therapy_areas', 'audiences'));
    }

    public function create(): Factory|View|Application
    {
        abort_if(Gate::denies('statement_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $therapy_areas = TherapyArea::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $parents = Statement::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $themes = Theme::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $resources = Resource::pluck('title', 'id');

        $references = Reference::pluck('title', 'id');

        $audiences = Audience::pluck('name', 'id');

        $statuses = StatementStatus::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.statements.create', compact('parents', 'references', 'resources', 'statuses', 'themes', 'therapy_areas', 'audiences'));
    }

    public function store(StoreStatementRequest $request): RedirectResponse
    {
        $statement = Statement::create($request->all());
        $statement->resources()->sync($request->input('resources', []));
        $statement->references()->sync($request->input('references', []));
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $statement->id]);
        }

        return redirect()->route('admin.statements.index');
    }

    public function edit(Statement $statement): Factory|View|Application
    {
        abort_if(Gate::denies('statement_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $therapy_areas = TherapyArea::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $parents = Statement::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $themes = Theme::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $resources = Resource::pluck('title', 'id');

        $references = Reference::pluck('title', 'id');

        $audiences = Audience::pluck('name', 'id');

        $statuses = StatementStatus::pluck('status', 'id')->prepend(trans('global.pleaseSelect'), '');

        $statement->load('therapy_area', 'parent', 'theme', 'resources', 'references', 'status');

        return view('admin.statements.edit', compact('parents', 'references', 'resources', 'statement', 'statuses', 'themes', 'therapy_areas', 'audiences'));
    }

    public function update(UpdateStatementRequest $request, Statement $statement): RedirectResponse
    {
        $statement->update($request->all());
        $statement->resources()->sync($request->input('resources', []));
        $statement->references()->sync($request->input('references', []));
        $statement->audiences()->sync($request->input('audiences', []));

        return redirect()->route('admin.statements.index');
    }

    public function show(Statement $statement): \Illuminate\View\View
    {
        abort_if(Gate::denies('statement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statement->load('therapy_area', 'parent', 'theme', 'resources', 'references', 'status', 'parentStatements', 'audiences');

        return view('admin.statements.show', compact('statement'));
    }

    public function destroy(Statement $statement)
    {
        abort_if(Gate::denies('statement_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statement->delete();

        return back();
    }

    public function massDestroy(MassDestroyStatementRequest $request): \Illuminate\Http\Response
    {
        Statement::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request): JsonResponse
    {
        abort_if(Gate::denies('statement_create') && Gate::denies('statement_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model = new Statement();
        $model->id = $request->input('crud_id', 0);
        $model->exists = true;
        $media = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
