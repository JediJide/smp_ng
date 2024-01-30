@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.therapyArea.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.therapy-areas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.therapyArea.fields.id') }}
                        </th>
                        <td>
                            {{ $therapyArea->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.therapyArea.fields.name') }}
                        </th>
                        <td>
                            {{ $therapyArea->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.therapy-areas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#therapy_area_statements" role="tab" data-toggle="tab">
                {{ trans('cruds.statement.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#therapy_area_glossaries" role="tab" data-toggle="tab">
                {{ trans('cruds.glossary.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#therapy_area_lexicons" role="tab" data-toggle="tab">
                {{ trans('cruds.lexicon.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="therapy_area_statements">
            @includeIf('admin.therapyAreas.relationships.therapyAreaStatements', ['statements' => $therapyArea->therapyAreaStatements])
        </div>
        <div class="tab-pane" role="tabpanel" id="therapy_area_glossaries">
            @includeIf('admin.therapyAreas.relationships.therapyAreaGlossaries', ['glossaries' => $therapyArea->therapyAreaGlossaries])
        </div>
        <div class="tab-pane" role="tabpanel" id="therapy_area_lexicons">
            @includeIf('admin.therapyAreas.relationships.therapyAreaLexicons', ['lexicons' => $therapyArea->therapyAreaLexicons])
        </div>
    </div>
</div>

@endsection