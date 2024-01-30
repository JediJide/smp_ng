@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.glossary.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.glossaries.update", [$glossary->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="therapy_area_id">{{ trans('cruds.glossary.fields.therapy_area') }}</label>
                <select class="form-control select2 {{ $errors->has('therapy_area') ? 'is-invalid' : '' }}" name="therapy_area_id" id="therapy_area_id" required>
                    @foreach($therapy_areas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('therapy_area_id') ? old('therapy_area_id') : $glossary->therapy_area->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('therapy_area'))
                    <div class="invalid-feedback">
                        {{ $errors->first('therapy_area') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.glossary.fields.therapy_area_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="term">{{ trans('cruds.glossary.fields.term') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('term') ? 'is-invalid' : '' }}" name="term" id="term">{!! old('term', $glossary->term) !!}</textarea>
                @if($errors->has('term'))
                    <div class="invalid-feedback">
                        {{ $errors->first('term') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.glossary.fields.term_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="definition">{{ trans('cruds.glossary.fields.definition') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('definition') ? 'is-invalid' : '' }}" name="definition" id="definition">{!! old('definition', $glossary->definition) !!}</textarea>
                @if($errors->has('definition'))
                    <div class="invalid-feedback">
                        {{ $errors->first('definition') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.glossary.fields.definition_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.glossaries.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $glossary->id ?? 0 }}');
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