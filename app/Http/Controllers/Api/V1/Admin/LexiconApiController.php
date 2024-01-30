<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreLexiconRequest;
use App\Http\Requests\UpdateLexiconRequest;
use App\Http\Resources\Admin\LexiconResource;
use App\Models\Lexicon;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class LexiconApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('lexicon_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LexiconResource(Lexicon::with(['therapy_area'])->get());
    }

    public function store(StoreLexiconRequest $request)
    {
        $lexicon = Lexicon::create($request->all());

        return (new LexiconResource($lexicon))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Lexicon $lexicon)
    {
        abort_if(Gate::denies('lexicon_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LexiconResource($lexicon->load(['therapy_area']));
    }

    public function update(UpdateLexiconRequest $request, Lexicon $lexicon)
    {
        $lexicon->update($request->all());

        return (new LexiconResource($lexicon))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Lexicon $lexicon)
    {
        abort_if(Gate::denies('lexicon_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lexicon->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
