@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.theme.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.themes.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.theme.fields.id') }}
                        </th>
                        <td>
                            {{ $theme->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.theme.fields.therapy_area') }}
                        </th>
                        <td>
                            {{ $theme->therapy_area->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.theme.fields.category') }}
                        </th>
                        <td>
                            {{ $theme->category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.theme.fields.name') }}
                        </th>
                        <td>
                            {{ $theme->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.theme.fields.description') }}
                        </th>
                        <td>
                            {!! $theme->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.theme.fields.resource') }}
                        </th>
                        <td>
                            @foreach($theme->resources as $key => $resource)
                                <span class="label label-info">{{ $resource->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.theme.fields.reference') }}
                        </th>
                        <td>
                            @foreach($theme->references as $key => $reference)
                                <span class="label label-info">{{ $reference->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.themes.index') }}">
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
            <a class="nav-link" href="#theme_statements" role="tab" data-toggle="tab">
                {{ trans('cruds.statement.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="theme_statements">
            @includeIf('admin.themes.relationships.themeStatements', ['statements' => $theme->themeStatements])
        </div>
    </div>
</div>

@endsection