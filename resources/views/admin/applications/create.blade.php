@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.application.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.applications.store") }}" enctype="multipart/form-data">
                @csrf
                {{--            <div class="form-group">--}}
                {{--                <div class="form-check {{ $errors->has('online') ? 'is-invalid' : '' }}">--}}
                {{--                    <input type="hidden" name="online" value="0">--}}
                {{--                    <input class="form-check-input" type="checkbox" name="online" id="online" value="1" {{ old('online', 0) == 1 || old('online') === null ? 'checked' : '' }}>--}}
                {{--                    <label class="form-check-label" for="online">{{ trans('cruds.application.fields.online') }}</label>--}}
                {{--                </div>--}}
                {{--                @if($errors->has('online'))--}}
                {{--                    <span class="text-danger">{{ $errors->first('online') }}</span>--}}
                {{--                @endif--}}
                {{--                <span class="help-block">{{ trans('cruds.application.fields.online_helper') }}</span>--}}
                {{--            </div>--}}
                <div class="row">
                    <div class="form-group col-6">
                        <label class="required" for="name">{{ trans('cruds.application.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                               name="name" id="name" value="{{ old('name', '') }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.name_helper') }}</span>
                    </div>
                    <div class="form-group col-6">
                        <label for="slug">{{ trans('cruds.application.fields.slug') }}</label>
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                               name="slug" id="slug" value="{{ old('slug', '') }}">
                        @if($errors->has('slug'))
                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.slug_helper') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="content">{{ trans('cruds.application.fields.content') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('content') ? 'is-invalid' : '' }}" rows="200"
                              name="content" id="content">{!! old('content', '') !!}</textarea>
                    @if($errors->has('content'))
                        <span class="text-danger">{{ $errors->first('content') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.application.fields.content_helper') }}</span>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-xl-6">
                        <label for="call_to_action">{{ trans('cruds.application.fields.call_to_action') }}</label>
                        <input class="form-control {{ $errors->has('call_to_action') ? 'is-invalid' : '' }}" type="text" name="call_to_action" id="call_to_action" value="{{ old('call_to_action', '') }}">
                        @if($errors->has('call_to_action'))
                            <span class="text-danger">{{ $errors->first('call_to_action') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.call_to_action_helper') }}</span>
                    </div>
                    <div class="form-group col-12 col-xl-6">
                        <label for="call_to_action">{{ trans('cruds.application.fields.call_to_action_link') }}</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ url('') . '/' . app()->getLocale() . '/' }}</span>
                            </div>
                            <input class="form-control {{ $errors->has('call_to_action_link') ? 'is-invalid' : '' }}" type="text" name="call_to_action_link" id="call_to_action_link" value="{{ old('call_to_action_link', '') }}">
                        </div>
                        @if($errors->has('call_to_action_link'))
                            <span class="text-danger">{{ $errors->first('call_to_action_link') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.call_to_action_link_helper') }}</span>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="form-group col-12 align-items-center">
                        <label for="image">{{ trans('cruds.application.fields.image') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image-dropzone"></div>
                        @if($errors->has('image'))
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.image_helper') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <input type="hidden" name="locale" value="{{app()->getLocale()}}">
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
                            return loader.file.then(function (file) {
                                return new Promise(function (resolve, reject) {
                                    // Init request
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST', '{{ route('admin.applications.storeCKEditorImages') }}', true);
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
                                    data.append('crud_id', '{{ $application->id ?? 0 }}');
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
                        extraPlugins: [SimpleUploadAdapter],
                        heading: {
                            options: [
                                {model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph'},
                                {model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'},
                                {model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3'},
                                {model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4'}
                            ]
                        }
                    }
                );
            }
        });
    </script>
    <script>
        Dropzone.options.imageDropzone = {
            url: '{{ route('admin.applications.storeMedia') }}',
            maxFilesize: 1, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 1,
                width: 300,
                height: 300
            },
            success: function (file, response) {
                $('form').find('input[name="image"]').remove()
                $('form').append('<input type="hidden" name="image" value="' + response.name + '">')
            },
            removedfile: function (file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="image"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function () {
                @if(isset($application) && $application->image)
                var file = {!! json_encode($application->image) !!}
                this.options.addedfile.call(this, file)
                this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                file.previewElement.classList.add('dz-complete')
                $('form').append('<input type="hidden" name="image" value="' + file.file_name + '">')
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
