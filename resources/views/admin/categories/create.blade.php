@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.category.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.categories.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">{{ trans('cruds.category.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}">
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="therapy_area_id">{{ trans('cruds.category.fields.therapy_area') }}</label>
                <select class="form-control select2 {{ $errors->has('therapy_area') ? 'is-invalid' : '' }}" name="therapy_area_id" id="therapy_area_id" required>
                    @foreach($therapy_areas as $id => $entry)
                        <option value="{{ $id }}" {{ old('therapy_area_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('therapy_area'))
                    <div class="invalid-feedback">
                        {{ $errors->first('therapy_area') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.category.fields.therapy_area_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection