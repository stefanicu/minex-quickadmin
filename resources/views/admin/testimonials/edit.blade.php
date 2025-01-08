@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between">

            <div class="w-50">{{ trans('global.edit') }} {{ trans('cruds.testimonial.title_singular') }}</div>

            @if($testimonial->translate(app()->getLocale()))
                <div class="w-50 text-right">
                    <a class="blue"
                       href="{{ route('testimonials.'.app()->getLocale()).'#tab'.$testimonial->id }}"
                       target="_blank">
                        <svg class="mr-1" width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <g id="Interface / External_Link">
                                <path id="Vector"
                                      d="M10.0002 5H8.2002C7.08009 5 6.51962 5 6.0918 5.21799C5.71547 5.40973 5.40973 5.71547 5.21799 6.0918C5 6.51962 5 7.08009 5 8.2002V15.8002C5 16.9203 5 17.4801 5.21799 17.9079C5.40973 18.2842 5.71547 18.5905 6.0918 18.7822C6.5192 19 7.07899 19 8.19691 19H15.8031C16.921 19 17.48 19 17.9074 18.7822C18.2837 18.5905 18.5905 18.2839 18.7822 17.9076C19 17.4802 19 16.921 19 15.8031V14M20 9V4M20 4H15M20 4L13 11"
                                      stroke="#003eff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                        </svg>
                        Preview
                    </a>
                </div>
            @endif
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.testimonials.update", [$testimonial->id]) }}"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <div class="form-group">
                    <div class="form-check {{ $errors->has('online') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="checkbox" name="online" id="online"
                               value="1" {{ old('online', optional($testimonial->translations->firstWhere('locale', app()->getLocale()))->online) ? 'checked' : '' }}>
                        <label class="form-check-label" for="online">{{ trans('cruds.blog.fields.online') }}</label>
                    </div>
                    @if($errors->has('online'))
                        <span class="text-danger">{{ $errors->first('online') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.blog.fields.online_helper') }}</span>
                </div>

                <div class="row align-items-center">
                    <div class="form-group col-12 col-xl-8 align-items-center">
                        <label for="logo">{{ trans('cruds.testimonial.fields.logo') }}</label>
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
                        <textarea class="form-control ckeditor {{ $errors->has('content') ? 'is-invalid' : '' }}"
                                  name="content" id="content">{!! old('content', $testimonial->content) !!}</textarea>
                        @if($errors->has('content'))
                            <span class="text-danger">{{ $errors->first('content') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.testimonial.fields.content_helper') }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-xl-4">
                        <label for="name">{{ trans('cruds.testimonial.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                               name="name" id="name" value="{{ old('name', $testimonial->name) }}">
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.testimonial.fields.name_helper') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-12 col-xl-4">
                        <label for="job">{{ trans('cruds.testimonial.fields.job') }}</label>
                        <input class="form-control {{ $errors->has('job') ? 'is-invalid' : '' }}" type="text" name="job"
                               id="job" value="{{ old('job', $testimonial->job) }}">
                        @if($errors->has('job'))
                            <span class="text-danger">{{ $errors->first('job') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.testimonial.fields.job_helper') }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-xl-4">
                        <label class="required" for="company">{{ trans('cruds.testimonial.fields.company') }}</label>
                        <input class="form-control {{ $errors->has('company') ? 'is-invalid' : '' }}" type="text"
                               name="company" id="company" value="{{ old('company', $testimonial->company) }}" required>
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
        Dropzone.options.logoDropzone = {
            url: '{{ route('admin.references.storeMedia') }}',
            maxFilesize: 2, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2,
                // width: 360,
                // height: 240
            },
            success: function (file, response) {
                $('form').find('input[name="logo"]').remove()
                $('form').append('<input type="hidden" name="logo" value="' + response.name + '">')
            },
            removedfile: function (file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="logo"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function () {
                @if(isset($testimonial) && $testimonial->logo)
                var file = {!! json_encode($testimonial->logo) !!}
                this.options.addedfile.call(this, file)
                this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                file.previewElement.classList.add('dz-complete')
                $('form').append('<input type="hidden" name="logo" value="' + file.file_name + '">')
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
