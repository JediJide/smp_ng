<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStatementStatusRequest;
use App\Http\Requests\UpdateStatementStatusRequest;
use App\Http\Resources\Admin\StatementStatusResource;
use App\Models\StatementStatus;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class StatementStatusApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('statement_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StatementStatusResource(StatementStatus::all());
    }

    public function store(StoreStatementStatusRequest $request)
    {
        $statementStatus = StatementStatus::create($request->all());

        return (new StatementStatusResource($statementStatus))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(StatementStatus $statementStatus)
    {
        abort_if(Gate::denies('statement_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new StatementStatusResource($statementStatus);
    }

    public function update(UpdateStatementStatusRequest $request, StatementStatus $statementStatus)
    {
        $statementStatus->update($request->all());

        return (new StatementStatusResource($statementStatus))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(StatementStatus $statementStatus)
    {
        abort_if(Gate::denies('statement_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statementStatus->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
