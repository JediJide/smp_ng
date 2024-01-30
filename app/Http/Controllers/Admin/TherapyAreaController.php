<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTherapyAreaRequest;
use App\Http\Requests\StoreTherapyAreaRequest;
use App\Http\Requests\UpdateTherapyAreaRequest;
use App\Models\TherapyArea;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class TherapyAreaController extends Controller
{
    public function index(): View
    {
        abort_if(Gate::denies('therapy_area_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $therapyAreas = TherapyArea::all();

        return view('admin.therapyAreas.index', compact('therapyAreas'));
    }

    public function create(): View
    {
        abort_if(Gate::denies('therapy_area_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.therapyAreas.create');
    }

    public function store(StoreTherapyAreaRequest $request): RedirectResponse
    {
        $therapyArea = TherapyArea::create($request->all());

        return redirect()->route('admin.therapy-areas.index');
    }

    public function edit(TherapyArea $therapyArea): View
    {
        abort_if(Gate::denies('therapy_area_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.therapyAreas.edit', compact('therapyArea'));
    }

    public function update(UpdateTherapyAreaRequest $request, TherapyArea $therapyArea): RedirectResponse
    {
        $therapyArea->update($request->all());

        return redirect()->route('admin.therapy-areas.index');
    }

    public function show(TherapyArea $therapyArea): View
    {
        abort_if(Gate::denies('therapy_area_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $therapyArea->load('therapyAreaStatements', 'therapyAreaGlossaries', 'therapyAreaLexicons');

        return view('admin.therapyAreas.show', compact('therapyArea'));
    }

    public function destroy(TherapyArea $therapyArea)
    {
        abort_if(Gate::denies('therapy_area_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $therapyArea->delete();

        return back();
    }

    public function massDestroy(MassDestroyTherapyAreaRequest $request): \Illuminate\Http\Response
    {
        TherapyArea::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
