@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.reference.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.references.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.reference.fields.id') }}
                        </th>
                        <td>
                            {{ $reference->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reference.fields.url') }}
                        </th>
                        <td>
                            {{ $reference->url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reference.fields.tag') }}
                        </th>
                        <td>
                            {{ $reference->tag }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reference.fields.file') }}
                        </th>
                        <td>
                            @foreach($reference->file as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.reference.fields.title') }}
                        </th>
                        <td>
                            {{ $reference->title }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.references.index') }}">
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
            <a class="nav-link" href="#reference_themes" role="tab" data-toggle="tab">
                {{ trans('cruds.theme.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#reference_statements" role="tab" data-toggle="tab">
                {{ trans('cruds.statement.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="reference_themes">
            @includeIf('admin.references.relationships.referenceThemes', ['themes' => $reference->referenceThemes])
        </div>
        <div class="tab-pane" role="tabpanel" id="reference_statements">
            @includeIf('admin.references.relationships.referenceStatements', ['statements' => $reference->referenceStatements])
        </div>
    </div>
</div>

@endsection