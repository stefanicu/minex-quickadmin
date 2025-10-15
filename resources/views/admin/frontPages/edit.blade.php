@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.frontPage.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.front_pages.update", [$frontPage->id]) }}"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.frontPage.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name" value="{{ old('name', $frontPage->name) }}" required>
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.frontPage.fields.name_helper') }}</span>
                </div>
                @if(!in_array($frontPage->id,[5]))
                    <div class="form-group">
                        <label for="first_text">{{ trans('cruds.frontPage.fields.first_text') }}</label>
                        <textarea class="form-control ckeditor {{ $errors->has('first_text') ? 'is-invalid' : '' }}"
                                  name="first_text"
                                  id="first_text">{{ old('first_text', $frontPage->first_text) }}</textarea>
                        @if($errors->has('first_text'))
                            <span class="text-danger">{{ $errors->first('first_text') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.frontPage.fields.first_text_helper') }}</span>
                    </div>
                @endif

                @if(!in_array($frontPage->id,[3,5,7]))
                    <div class="form-group">
                        <label for="second_text">{{ trans('cruds.frontPage.fields.second_text') }}</label>
                        <textarea class="form-control ckeditor {{ $errors->has('second_text') ? 'is-invalid' : '' }}"
                                  name="second_text"
                                  id="second_text">{!! old('second_text', $frontPage->second_text) !!}</textarea>
                        @if($errors->has('second_text'))
                            <span class="text-danger">{{ $errors->first('second_text') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.frontPage.fields.second_text_helper') }}</span>
                    </div>
                @endif

                @if(!in_array($frontPage->id,[1,5,6,7]))
                    <div class="form-group">
                        <label for="quote">{{ trans('cruds.frontPage.fields.quote') }}</label>
                        <textarea class="form-control ckeditor {{ $errors->has('quote') ? 'is-invalid' : '' }}"
                                  name="quote" id="quote">{!! old('quote', $frontPage->quote) !!}</textarea>
                        @if($errors->has('quote'))
                            <span class="text-danger">{{ $errors->first('quote') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.frontPage.fields.quote_helper') }}</span>
                    </div>
                @endif

                @if(!in_array($frontPage->id,[1,3,6]))
                    <div class="form-group">
                        <label for="button">{{ trans('cruds.frontPage.fields.button') }}</label>
                        <input class="form-control {{ $errors->has('button') ? 'is-invalid' : '' }}" type="text"
                               name="button" id="button" value="{{ old('button', $frontPage->button) }}">
                        @if($errors->has('button'))
                            <span class="text-danger">{{ $errors->first('button') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.frontPage.fields.button_helper') }}</span>
                    </div>
                @endif

                @if(!in_array($frontPage->id,[1,5,7]))
                    <div class="form-group">
                        <label for="image">{{ trans('cruds.frontPage.fields.image') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}"
                             id="image-dropzone">
                        </div>
                        @if($errors->has('image'))
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                        @endif
                        <p><span class="help-block">{{ trans('cruds.frontPage.fields.image_helper') }}</span></p>

                        <img src="{{ asset('img/home/s' . $frontPage->id . '/sm-min.jpg') }}"
                             alt="{{ $frontPage->name }}">
                    </div>

                @endif

                <div class="form-group">
                    <input type="hidden" name="locale" value="{{app()->getLocale()}}">
                    <button class="btn btn-orange" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>

            @if( app()->getLocale() === 'en' )
                <form id="translateButtonAllForm" method="POST" class="" action="{{ route("admin.translation.languages") }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="form-check {{ $errors->has('online') ? 'is-invalid' : '' }}">
                            <input type="hidden" name="id" id="id" value="{{ $frontPage->id }}">
                            <input type="hidden" name="model_translation" id="model_translation" value="frontPage_translations">
                            <input type="hidden" name="foreign_key" id="foreign_key" value="frontPage_id">
                            <input type="hidden" name="language" id="language" value="{{ app()->getLocale() }}">
                            <button type="submit" class="btn btn-danger">{{ trans('admin.translation_button_all') }}</button>
                        </div>
                    </div>
                </form>
            @endif

            <form id="translateButtonForm" method="POST" class="" action="{{ route("admin.translation.granular") }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="form-check {{ $errors->has('online') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="id" id="id" value="{{ $frontPage->id }}">
                        <input type="hidden" name="model_translation" id="model_translation" value="front_page_translations">
                        <input type="hidden" name="foreign_key" id="foreign_key" value="front_page_id">
                        <input type="hidden" name="language" id="language" value="{{ app()->getLocale() }}">
                        @if(app()->getLocale() === 'en')
                            <button type="submit" class="btn btn-primary">{{ trans('admin.translation_button', ['from' => 'RO','to' => 'EN']) }}</button>
                        @elseif(app()->getLocale() === 'ro')
                            <button type="submit" class="btn btn-primary">{{ trans('admin.translation_button', ['from' => 'EN','to' => 'RO']) }}</button>
                        @else
                            <button type="submit" class="btn btn-primary">{{ trans('admin.translation_button', ['from' => 'EN and RO','to' => strtoupper(app()->getLocale())]) }}</button>
                        @endif
                    </div>
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
                                    xhr.open('POST', '{{ route('admin.front_pages.storeCKEditorImages') }}', true);
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
                                    data.append('crud_id', '{{ $frontPage->id ?? 0 }}');
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
                        },
                        heading: {
                            options: [
                                {model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph'},
                                {model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1'},
                                {model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'},
                                {model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3'},
                                {model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4'}
                            ]
                        }
                    }
                ).catch(error => console.error(error));
            }
        });
    </script>

    <script>
        Dropzone.options.imageDropzone = {
            url: '{{ route('admin.front_pages.storeMedia') }}',
            maxFilesize: 1, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 1,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 1,
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
                @if(isset($frontPage) && $frontPage->image)
                var file = {!! json_encode($frontPage->image) !!}
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
