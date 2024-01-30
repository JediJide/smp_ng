@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.reference.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.references.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="url">{{ trans('cruds.reference.fields.url') }}</label>
                <input class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" type="text" name="url" id="url" value="{{ old('url', '') }}">
                @if($errors->has('url'))
                    <div class="invalid-feedback">
                        {{ $errors->first('url') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.reference.fields.url_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tag">{{ trans('cruds.reference.fields.tag') }}</label>
                <input class="form-control {{ $errors->has('tag') ? 'is-invalid' : '' }}" type="text" name="tag" id="tag" value="{{ old('tag', '') }}">
                @if($errors->has('tag'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tag') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.reference.fields.tag_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="file">{{ trans('cruds.reference.fields.file') }}</label>
                <div class="needsclick dropzone {{ $errors->has('file') ? 'is-invalid' : '' }}" id="file-dropzone">
                </div>
                @if($errors->has('file'))
                    <div class="invalid-feedback">
                        {{ $errors->first('file') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.reference.fields.file_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.reference.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.reference.fields.title_helper') }}</span>
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
    var uploadedFileMap = {}
Dropzone.options.fileDropzone = {
    url: '{{ route('admin.references.storeMedia') }}',
    maxFilesize: 500, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 500
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="file[]" value="' + response.name + '">')
      uploadedFileMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFileMap[file.name]
      }
      $('form').find('input[name="file[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($reference) && $reference->file)
          var files =
            {!! json_encode($reference->file) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="file[]" value="' + file.file_name + '">')
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