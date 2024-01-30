<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAudienceRequest;
use App\Http\Requests\StoreAudienceRequest;
use App\Http\Requests\UpdateAudienceRequest;
use App\Models\Audience;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AudienceController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('audience_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $audiences = Audience::all();

        return view('admin.audiences.index', compact('audiences'));
    }

    public function create()
    {
        abort_if(Gate::denies('audience_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.audiences.create');
    }

    public function store(StoreAudienceRequest $request)
    {
        $audiences = Audience::create($request->all());

        return redirect()->route('admin.audiences.index');
    }

    public function edit(Audience $audience)
    {
        abort_if(Gate::denies('audience_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('admin.audiences.edit');
    }

    public function update(UpdateAudienceRequest $request, Audience $audience)
    {
        $audience->update($request->all());


        return redirect()->route('admin.audiences.index');
    }

    public function show(Audience $audience)
    {
        abort_if(Gate::denies('audience_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $audience->load('audienceStatements');

        return view('admin.audiences.show', compact('audience'));
    }

    public function destroy(Audience $audience)
    {
        abort_if(Gate::denies('audience_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $audience->delete();

        return back();
    }

    public function massDestroy(MassDestroyAudienceRequest $request)
    {
        Audience::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
