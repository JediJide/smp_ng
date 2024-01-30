@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.statement.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.statements.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.statement.fields.id') }}
                        </th>
                        <td>
                            {{ $statement->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.statement.fields.therapy_area') }}
                        </th>
                        <td>
                            {{ $statement->therapy_area->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.statement.fields.parent') }}
                        </th>
                        <td>
                            {{ $statement->parent->title ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.statement.fields.theme') }}
                        </th>
                        <td>
                            {{ $statement->theme->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.statement.fields.title') }}
                        </th>
                        <td>
                            {{ $statement->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.statement.fields.description') }}
                        </th>
                        <td>
                            {!! $statement->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.statement.fields.is_notify_all') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $statement->is_notify_all ? 'checked' : '' }}>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.statement.fields.resource') }}
                        </th>
                        <td>
                            @foreach($statement->resources as $key => $resource)
                                <span class="label label-info">{{ $resource->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.statement.fields.reference') }}
                        </th>
                        <td>
                            @foreach($statement->references as $key => $reference)
                                <span class="label label-info">{{ $reference->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.statement.fields.audience') }}
                        </th>
                        <td>
                            @foreach($statement->audiences as $audience => $item)
                                <span class="badge badge-info">{{ $item->name }}</span>
                            @endforeach
                        </td>
                    </tr>

                    <tr>
                        <th>
                            {{ trans('cruds.statement.fields.status') }}
                        </th>
                        <td>
                            {{ $statement->status->status ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.statement.fields.order_by') }}
                        </th>
                        <td>
                            {{ $statement->order_by }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.statements.index') }}">
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
            <a class="nav-link" href="#parent_statements" role="tab" data-toggle="tab">
                {{ trans('cruds.statement.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="parent_statements">
            @includeIf('admin.statements.relationships.parentStatements', ['statements' => $statement->parentStatements])
        </div>
    </div>
</div>

@endsection
