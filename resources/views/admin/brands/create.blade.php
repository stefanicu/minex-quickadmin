@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.brand.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.brands.store") }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="form-group col-12 col-xl-6">
                        <label class="required" for="name">{{ trans('cruds.brand.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                               name="name" id="name" value="{{ old('name', '') }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.brand.fields.name_helper') }}</span>
                    </div>

                    <div class="form-group col-12 col-xl-6">
                        <label class="required" for="slug">{{ trans('cruds.brand.fields.slug') }}</label>
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                               name="slug" id="slug" value="{{ old('slug', '') }}" required>
                        @if($errors->has('slug'))
                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.brand.fields.slug_helper') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="content">{{ trans('cruds.brand.fields.content') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('content') ? 'is-invalid' : '' }}" rows="200"
                              name="content" id="content">{!! old('content', '') !!}</textarea>
                    @if($errors->has('content'))
                        <span class="text-danger">{{ $errors->first('content') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.brand.fields.content_helper') }}</span>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-xl-6">
                        <label for="call_to_action">{{ trans('cruds.brand.fields.call_to_action') }}</label>
                        <input class="form-control {{ $errors->has('call_to_action') ? 'is-invalid' : '' }}" type="text" name="call_to_action" id="call_to_action" value="{{ old('call_to_action', '') }}">
                        @if($errors->has('call_to_action'))
                            <span class="text-danger">{{ $errors->first('call_to_action') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.brand.fields.call_to_action_helper') }}</span>
                    </div>
                    <div class="form-group col-12 col-xl-6">
                        <label for="call_to_action">{{ trans('cruds.brand.fields.call_to_action_link') }}</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ url('') . '/' . app()->getLocale() . '/' }}</span>
                            </div>
                            <input class="form-control {{ $errors->has('call_to_action_link') ? 'is-invalid' : '' }}" type="text" name="call_to_action_link" id="call_to_action_link" value="{{ old('call_to_action_link', '') }}">
                        </div>
                        @if($errors->has('call_to_action_link'))
                            <span class="text-danger">{{ $errors->first('call_to_action_link') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.brand.fields.call_to_action_link_helper') }}</span>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="form-group col-12">
                        <label for="photo">{{ trans('cruds.brand.fields.photo') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }} w-max text-center"
                             id="photo-dropzone">
                        </div>
                        @if($errors->has('photo'))
                            <span class="text-danger">{{ $errors->first('photo') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.brand.fields.photo_helper') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <input type="hidden" name="locale" value="{{ app()->getLocale() }}">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/slugs.js') }}"></script>
    <script>
        $(document).ready(function () {
            function SimpleUploadAdapter(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
                    return {
                        upload: function () {
                            return loader.file.then(function (file) {
                                return new Promise(function (resolve, reject) {
                                    // Init request
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST', '{{ route('admin.brands.storeCKEditorImages') }}', true);
                                    xhr.setRequestHeader('x-csrf-token', window._token);
                                    xhr.setRequestHeader('Accept', 'application/json');
                                    xhr.responseType = 'json';

                                    // Init listeners
                                    var genericErrorText = `Couldn't upload file: ${file.name}.`;
                                    xhr.addEventListener('error', function () {
                                        reject(genericErrorText)
                                    });
                                    xhr.addEventListener('abort', function () {
                                        reject()
                                    });
                                    xhr.addEventListener('load', function () {
                                        var response = xhr.response;

                                        if (!response || xhr.status !== 201) {
                                            return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                                        }

                                        $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                                        resolve({default: response.url});
                                    });

                                    if (xhr.upload) {
                                        xhr.upload.addEventListener('progress', function (e) {
                                            if (e.lengthComputable) {
                                                loader.uploadTotal = e.total;
                                                loader.uploaded = e.loaded;
                                            }
                                        });
                                    }

                                    // Send request
                                    var data = new FormData();
                                    data.append('upload', file);
                                    data.append('crud_id', '{{ $brand->id ?? 0 }}');
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

    <script>
        var uploadedLogoMap = {}
        Dropzone.options.photoDropzone = {
            url: '{{ route('admin.brands.storeMedia') }}',
            maxFilesize: 1, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 1,
            },
            success: function (file, response) {
                $('form').append('<input type="hidden" name="photo[]" value="' + response.name + '">')
                uploadedLogoMap[file.name] = response.name
            },
            removedfile: function (file) {
                console.log(file)
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedLogoMap[file.name]
                }
                $('form').find('input[name="photo[]"][value="' + name + '"]').remove()
            },
            init: function () {
                @if(isset($brand) && $brand->photo)
                var files = {!! json_encode($brand->photo) !!};
                for (var i in files) {
                    var file = files[i]
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="photo[]" value="' + file.file_name + '">')
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
