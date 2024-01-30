@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.resource.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.resources.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.id') }}
                        </th>
                        <td>
                            {{ $resource->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.title') }}
                        </th>
                        <td>
                            {{ $resource->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.filename') }}
                        </th>
                        <td>
                            @foreach($resource->filename as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.user') }}
                        </th>
                        <td>
                            {{ $resource->user->email ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.url') }}
                        </th>
                        <td>
                            {{ $resource->url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.resource.fields.temporary_url') }}
                        </th>
                        <td>
                            {{ $resource->temporary_url }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.resources.index') }}">
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
            <a class="nav-link" href="#resource_themes" role="tab" data-toggle="tab">
                {{ trans('cruds.theme.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#resource_statements" role="tab" data-toggle="tab">
                {{ trans('cruds.statement.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="resource_themes">
            @includeIf('admin.resources.relationships.resourceThemes', ['themes' => $resource->resourceThemes])
        </div>
        <div class="tab-pane" role="tabpanel" id="resource_statements">
            @includeIf('admin.resources.relationships.resourceStatements', ['statements' => $resource->resourceStatements])
        </div>
    </div>
</div>

@endsection