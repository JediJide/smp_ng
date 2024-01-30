@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.lexicon.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.lexicons.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="therapy_area_id">{{ trans('cruds.lexicon.fields.therapy_area') }}</label>
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
                <span class="help-block">{{ trans('cruds.lexicon.fields.therapy_area_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="preferred_phrase">{{ trans('cruds.lexicon.fields.preferred_phrase') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('preferred_phrase') ? 'is-invalid' : '' }}" name="preferred_phrase" id="preferred_phrase">{!! old('preferred_phrase') !!}</textarea>
                @if($errors->has('preferred_phrase'))
                    <div class="invalid-feedback">
                        {{ $errors->first('preferred_phrase') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lexicon.fields.preferred_phrase_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="guidance_for_usage">{{ trans('cruds.lexicon.fields.guidance_for_usage') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('guidance_for_usage') ? 'is-invalid' : '' }}" name="guidance_for_usage" id="guidance_for_usage">{!! old('guidance_for_usage') !!}</textarea>
                @if($errors->has('guidance_for_usage'))
                    <div class="invalid-feedback">
                        {{ $errors->first('guidance_for_usage') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lexicon.fields.guidance_for_usage_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="non_preferred_terms">{{ trans('cruds.lexicon.fields.non_preferred_terms') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('non_preferred_terms') ? 'is-invalid' : '' }}" name="non_preferred_terms" id="non_preferred_terms">{!! old('non_preferred_terms') !!}</textarea>
                @if($errors->has('non_preferred_terms'))
                    <div class="invalid-feedback">
                        {{ $errors->first('non_preferred_terms') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lexicon.fields.non_preferred_terms_helper') }}</span>
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
                xhr.open('POST', '{{ route('admin.lexicons.storeCKEditorImages') }}', true);
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
                data.append('crud_id', '{{ $lexicon->id ?? 0 }}');
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