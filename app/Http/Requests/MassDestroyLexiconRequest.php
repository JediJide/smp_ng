<?php

namespace App\Http\Requests;

use App\Models\Lexicon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Schema(),
 * required={"id"}
 * MassDestroyLexiconRequest class
 */
class MassDestroyLexiconRequest extends FormRequest
{
    /**
     * @OA\Property(format="string", title="message", default="Resource has been deleted", property="message"),
     */
    public function authorize()
    {
        abort_if(Gate::denies('lexicon_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:lexicons,id',
        ];
    }
}