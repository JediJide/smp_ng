@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.audience.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.audiences.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.audience.fields.id') }}
                        </th>
                        <td>
                            {{ $audience->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.audience.fields.name') }}
                        </th>
                        <td>
                            {{ $audience->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.audiences.index') }}">
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
            <a class="nav-link" href="#audience_statements" role="tab" data-toggle="tab">
                {{ trans('cruds.statement.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="audience_statements">
            @includeIf('admin.audiences.relationships.audienceStatements', ['statements' => $audience->audienceStatements])
        </div>
    </div>
</div>

@endsection
