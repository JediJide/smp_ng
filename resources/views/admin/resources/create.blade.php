@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.resource.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.resources.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">{{ trans('cruds.resource.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}">
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="filename">{{ trans('cruds.resource.fields.filename') }}</label>
                <div class="needsclick dropzone {{ $errors->has('filename') ? 'is-invalid' : '' }}" id="filename-dropzone">
                </div>
                @if($errors->has('filename'))
                    <div class="invalid-feedback">
                        {{ $errors->first('filename') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.filename_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="user_id">{{ trans('cruds.resource.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id">
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="url">{{ trans('cruds.resource.fields.url') }}</label>
                <input class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" type="text" name="url" id="url" value="{{ old('url', '') }}">
                @if($errors->has('url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.url_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="temporary_url">{{ trans('cruds.resource.fields.temporary_url') }}</label>
                <input class="form-control {{ $errors->has('temporary_url') ? 'is-invalid' : '' }}" type="text" name="temporary_url" id="temporary_url" value="{{ old('temporary_url', '') }}">
                @if($errors->has('temporary_url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('temporary_url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.temporary_url_helper') }}</span>
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
    var uploadedFilenameMap = {}
Dropzone.options.filenameDropzone = {
    url: '{{ route('admin.resources.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="filename[]" value="' + response.name + '">')
      uploadedFilenameMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFilenameMap[file.name]
      }
      $('form').find('input[name="filename[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($resource) && $resource->filename)
          var files =
            {!! json_encode($resource->filename) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="filename[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection