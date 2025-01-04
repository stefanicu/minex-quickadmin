@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.brand.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.brands.store") }}" enctype="multipart/form-data">
                @csrf
                {{--                <div class="form-group">--}}
                {{--                    <div class="form-check {{ $errors->has('online') ? 'is-invalid' : '' }}">--}}
                {{--                        <input type="hidden" name="online" value="0">--}}
                {{--                        <input class="form-check-input" type="checkbox" name="online" id="online"--}}
                {{--                               value="0">--}}
                {{--                        <label class="form-check-label" for="online">{{ trans('cruds.brand.fields.online') }}</label>--}}
                {{--                    </div>--}}
                {{--                    @if($errors->has('online'))--}}
                {{--                        <span class="text-danger">{{ $errors->first('online') }}</span>--}}
                {{--                    @endif--}}
                {{--                    <span class="help-block">{{ trans('cruds.brand.fields.online_helper') }}</span>--}}
                {{--                </div>--}}

                <div class="row">
                    <div class="form-group col-12 col-sm-6">
                        <label class="required" for="name">{{ trans('cruds.brand.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                               name="name" id="name" value="{{ old('name', '') }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.brand.fields.name_helper') }}</span>
                    </div>
                    <div class="form-group col-12 col-sm-6">
                        <label class="required" for="slug">{{ trans('cruds.brand.fields.slug') }}</label>
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                               name="slug" id="slug" value="{{ old('slug', '') }}" required>
                        @if($errors->has('slug'))
                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.brand.fields.slug_helper') }}</span>
                    </div>
                </div>

                <div class="row align-items-center">

                    <div class="form-group col-3">
                        <label class="required" for="photo">{{ trans('cruds.brand.fields.photo') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }} w-max text-center"
                             id="photo-dropzone">
                        </div>
                        @if($errors->has('photo'))
                            <span class="text-danger">{{ $errors->first('photo') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.brand.fields.photo_helper') }}</span>
                    </div>

                    <div class="form-group col-9">
                        <label class="" for="offline_message">{{ trans('cruds.brand.fields.offline_message') }}</label>
                        <textarea
                                class="form-control ckeditor {{ $errors->has('offline_message') ? 'is-invalid' : '' }}"
                                name="offline_message"
                                id="offline_message">{!! old('offline_message', '') !!}</textarea>
                        @if($errors->has('offline_message'))
                            <span class="text-danger">{{ $errors->first('offline_message') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.brand.fields.offline_message_helper') }}</span>
                    </div>

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
                editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
                    return {
                        upload: function () {
                            return loader.file
                                .then(function (file) {
                                    return new Promise(function (resolve, reject) {
                                        // Init request
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST', '{{ route('admin.blogs.storeCKEditorImages') }}', true);
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
                                        data.append('crud_id', '{{ $blog->id ?? 0 }}');
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
        Dropzone.options.photoDropzone = {
            url: '{{ route('admin.brands.storeMedia') }}',
            maxFilesize: 2, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2,
                width: 345,
                height: 228
            },
            success: function (file, response) {
                $('form').find('input[name="photo"]').remove()
                $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
            },
            removedfile: function (file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="photo"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function () {

                const dropzoneInfo = document.createElement('p');
                dropzoneInfo.textContent = 'Allowed: Images only, max 2MB, dimensions up to 343x228px.';
                dropzoneInfo.style.color = '#f00';
                dropzoneInfo.style.marginBottom = '10px';
                this.element.insertBefore(dropzoneInfo, this.element.firstChild);

                @if(isset($brand) && $brand->photo)
                var file = {!! json_encode($brand->photo) !!}
                this.options.addedfile.call(this, file)
                this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                file.previewElement.classList.add('dz-complete')
                $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
                this.options.maxFiles = this.options.maxFiles - 1
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
