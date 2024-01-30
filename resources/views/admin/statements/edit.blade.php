@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.statement.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.statements.update", [$statement->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="therapy_area_id">{{ trans('cruds.statement.fields.therapy_area') }}</label>
                <select class="form-control select2 {{ $errors->has('therapy_area') ? 'is-invalid' : '' }}" name="therapy_area_id" id="therapy_area_id" required>
                    @foreach($therapy_areas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('therapy_area_id') ? old('therapy_area_id') : $statement->therapy_area->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('therapy_area'))
                    <div class="invalid-feedback">
                        {{ $errors->first('therapy_area') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.statement.fields.therapy_area_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="parent_id">{{ trans('cruds.statement.fields.parent') }}</label>
                <select class="form-control select2 {{ $errors->has('parent') ? 'is-invalid' : '' }}" name="parent_id" id="parent_id">
                    @foreach($parents as $id => $entry)
                        <option value="{{ $id }}" {{ (old('parent_id') ? old('parent_id') : $statement->parent->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('parent'))
                    <div class="invalid-feedback">
                        {{ $errors->first('parent') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.statement.fields.parent_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="theme_id">{{ trans('cruds.statement.fields.theme') }}</label>
                <select class="form-control select2 {{ $errors->has('theme') ? 'is-invalid' : '' }}" name="theme_id" id="theme_id" required>
                    @foreach($themes as $id => $entry)
                        <option value="{{ $id }}" {{ (old('theme_id') ? old('theme_id') : $statement->theme->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('theme'))
                    <div class="invalid-feedback">
                        {{ $errors->first('theme') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.statement.fields.theme_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.statement.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $statement->title) }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.statement.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.statement.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $statement->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.statement.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('is_notify_all') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="is_notify_all" value="0">
                    <input class="form-check-input" type="checkbox" name="is_notify_all" id="is_notify_all" value="1" {{ $statement->is_notify_all || old('is_notify_all', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_notify_all">{{ trans('cruds.statement.fields.is_notify_all') }}</label>
                </div>
                @if($errors->has('is_notify_all'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_notify_all') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.statement.fields.is_notify_all_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="resources">{{ trans('cruds.statement.fields.resource') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('resources') ? 'is-invalid' : '' }}" name="resources[]" id="resources" multiple>
                    @foreach($resources as $id => $resource)
                        <option value="{{ $id }}" {{ (in_array($id, old('resources', [])) || $statement->resources->contains($id)) ? 'selected' : '' }}>{{ $resource }}</option>
                    @endforeach
                </select>
                @if($errors->has('resources'))
                    <div class="invalid-feedback">
                        {{ $errors->first('resources') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.statement.fields.resource_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="references">{{ trans('cruds.statement.fields.reference') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('references') ? 'is-invalid' : '' }}" name="references[]" id="references" multiple>
                    @foreach($references as $id => $reference)
                        <option value="{{ $id }}" {{ (in_array($id, old('references', [])) || $statement->references->contains($id)) ? 'selected' : '' }}>{{ $reference }}</option>
                    @endforeach
                </select>
                @if($errors->has('references'))
                    <div class="invalid-feedback">
                        {{ $errors->first('references') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.statement.fields.reference_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="audiences">{{ trans('cruds.statement.fields.audience') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('audiences') ? 'is-invalid' : '' }}" name="audiences[]" id="audiences" multiple>
                    @foreach($audiences as $id => $audience)
                        <option value="{{ $id }}" {{ (in_array($id, old('audiences', [])) || $statement->audiences->contains($id)) ? 'selected' : '' }}>{{ $audience }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <div class="invalid-feedback">
                        {{ $errors->first('audiences') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.statement.fields.audience_helper') }}</span>
            </div>


            <div class="form-group">
                <label for="status_id">{{ trans('cruds.statement.fields.status') }}</label>
                <select class="form-control select2 {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status_id" id="status_id">
                    @foreach($statuses as $id => $entry)
                        <option value="{{ $id }}" {{ (old('status_id') ? old('status_id') : $statement->status->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.statement.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="order_by">{{ trans('cruds.statement.fields.order_by') }}</label>
                <input class="form-control {{ $errors->has('order_by') ? 'is-invalid' : '' }}" type="number" name="order_by" id="order_by" value="{{ old('order_by', $statement->order_by) }}" step="1">
                @if($errors->has('order_by'))
                    <div class="invalid-feedback">
                        {{ $errors->first('order_by') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.statement.fields.order_by_helper') }}</span>
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

@section('scripts')
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.statements.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $statement->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection
