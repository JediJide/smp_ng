<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreStatementRequest;
use App\Http\Requests\UpdateStatementRequest;
use App\Http\Resources\Admin\StatementResource;
use App\Models\Statement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class StatementApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('statement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StatementResource(Statement::with(['therapy_area', 'parent', 'theme', 'resources', 'references', 'status'])->get());
    }

    public function store(StoreStatementRequest $request)
    {
        $statement = Statement::create($request->all());
        $statement->resources()->sync($request->input('resources', []));
        $statement->references()->sync($request->input('references', []));

        return (new StatementResource($statement))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Statement $statement)
    {
        abort_if(Gate::denies('statement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StatementResource($statement->load(['therapy_area', 'parent', 'theme', 'resources', 'references', 'status']));
    }

    public function update(UpdateStatementRequest $request, Statement $statement)
    {
        $statement->update($request->all());
        $statement->resources()->sync($request->input('resources', []));
        $statement->references()->sync($request->input('references', []));

        return (new StatementResource($statement))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Statement $statement)
    {
        abort_if(Gate::denies('statement_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statement->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
