@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.testimonial.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.testimonials.store") }}" enctype="multipart/form-data">
                @csrf

                <div class="row align-items-center">
                    <div class="form-group col-8 col-xl-4 align-items-center">
                        <label class="required" for="logo">{{ trans('cruds.testimonial.fields.logo') }}</label>
                        <span class="help-block">{{ trans('cruds.testimonial.fields.logo_helper') }}</span>
                        <div class="needsclick dropzone {{ $errors->has('logo') ? 'is-invalid' : '' }} text-center"
                             id="logo-dropzone">
                        </div>
                        @if($errors->has('logo'))
                            <span class="text-danger">{{ $errors->first('logo') }}</span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-xl-8">
                        <label class="required" for="content">{{ trans('cruds.testimonial.fields.content') }}</label>
                        <textarea
                                class="form-control ckeditor {{ $errors->has('content') ? 'is-invalid' : '' }}"
                                name="content" id="content">{!! old('content', '') !!}</textarea>
                        @if($errors->has('content'))
                            <span class="text-danger">{{ $errors->first('content') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.testimonial.fields.content_helper') }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-8 col-xl-4">
                        <label for="name">{{ trans('cruds.testimonial.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                               name="name" id="name" value="{{ old('name', '') }}">
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.testimonial.fields.name_helper') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-8 col-xl-4">
                        <label for="job">{{ trans('cruds.testimonial.fields.job') }}</label>
                        <input class="form-control {{ $errors->has('job') ? 'is-invalid' : '' }}" type="text" name="job"
                               id="job" value="{{ old('job', '') }}">
                        @if($errors->has('job'))
                            <span class="text-danger">{{ $errors->first('job') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.testimonial.fields.job_helper') }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-8 col-xl-4">
                        <label class="required" for="company">{{ trans('cruds.testimonial.fields.company') }}</label>
                        <input class="form-control {{ $errors->has('company') ? 'is-invalid' : '' }}" type="text"
                               name="company" id="company" value="{{ old('company', '') }}" required>
                        @if($errors->has('company'))
                            <span class="text-danger">{{ $errors->first('company') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.testimonial.fields.company_helper') }}</span>
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
                            return loader.file
                                .then(function (file) {
                                    return new Promise(function (resolve, reject) {
                                        // Init request
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST', '{{ route('admin.testimonials.storeCKEditorImages') }}', true);
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
                                        data.append('crud_id', '{{ $testimonial->id ?? 0 }}');
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
        Dropzone.options.logoDropzone = {
            url: '{{ route('admin.testimonials.storeMedia') }}',
            maxFilesize: 4, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 4,
                width: 360,
                height: 300
            },
            success: function (file, response) {
                $('form').append('<input type="hidden" name="logo[]" value="' + response.name + '">')
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
                $('form').find('input[name="logo[]"][value="' + name + '"]').remove()
            },
            init: function () {
                @if(isset($testimonial) && $testimonial->logo)
                var files = {!! json_encode($testimonial->logo) !!}
                for(
                var i
            in
                files
            )
                {
                    var file = files[i]
                    this.options.addedfile.call(this, file)
                    this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="logo[]" value="' + file.file_name + '">')
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
