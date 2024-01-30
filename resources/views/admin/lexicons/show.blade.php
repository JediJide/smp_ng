@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.lexicon.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.lexicons.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.lexicon.fields.id') }}
                        </th>
                        <td>
                            {{ $lexicon->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lexicon.fields.therapy_area') }}
                        </th>
                        <td>
                            {{ $lexicon->therapy_area->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lexicon.fields.preferred_phrase') }}
                        </th>
                        <td>
                            {!! $lexicon->preferred_phrase !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lexicon.fields.guidance_for_usage') }}
                        </th>
                        <td>
                            {!! $lexicon->guidance_for_usage !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lexicon.fields.non_preferred_terms') }}
                        </th>
                        <td>
                            {!! $lexicon->non_preferred_terms !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.lexicons.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection