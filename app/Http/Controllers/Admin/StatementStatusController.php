<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyStatementStatusRequest;
use App\Http\Requests\StoreStatementStatusRequest;
use App\Http\Requests\UpdateStatementStatusRequest;
use App\Models\StatementStatus;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class StatementStatusController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('statement_status_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statementStatuses = StatementStatus::all();

        return view('admin.statementStatuses.index', compact('statementStatuses'));
    }

    public function create()
    {
        abort_if(Gate::denies('statement_status_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.statementStatuses.create');
    }

    public function store(StoreStatementStatusRequest $request)
    {
        $statementStatus = StatementStatus::create($request->all());

        return redirect()->route('admin.statement-statuses.index');
    }

    public function edit(StatementStatus $statementStatus)
    {
        abort_if(Gate::denies('statement_status_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.statementStatuses.edit', compact('statementStatus'));
    }

    public function update(UpdateStatementStatusRequest $request, StatementStatus $statementStatus)
    {
        $statementStatus->update($request->all());

        return redirect()->route('admin.statement-statuses.index');
    }

    public function show(StatementStatus $statementStatus)
    {
        abort_if(Gate::denies('statement_status_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statementStatus->load('statusStatements');

        return view('admin.statementStatuses.show', compact('statementStatus'));
    }

    public function destroy(StatementStatus $statementStatus)
    {
        abort_if(Gate::denies('statement_status_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statementStatus->delete();

        return back();
    }

    public function massDestroy(MassDestroyStatementStatusRequest $request)
    {
        StatementStatus::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
