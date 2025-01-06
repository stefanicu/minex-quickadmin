@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="w-50">{{ trans('global.edit') }} {{ trans('cruds.reference.title_singular') }}</div>
            @if($reference->translate(app()->getLocale()))
                <div class="w-50 text-right">
                    <a class="blue"
                       href="{{ route('reference.'.app()->getLocale(), ['slug'=>$reference->slug]) }}"
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
            <form method="POST" action="{{ route("admin.references.update", [$reference->id]) }}"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <div class="form-group">
                    <div class="form-check {{ $errors->has('online') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="online" value="0">
                        <input class="form-check-input" type="checkbox" name="online" id="online"
                               value="1" {{ old('online', optional($reference->translations->firstWhere('locale', app()->getLocale()))->online) ? 'checked' : '' }}>
                        <label class="form-check-label" for="online">{{ trans('cruds.blog.fields.online') }}</label>
                    </div>
                    @if($errors->has('online'))
                        <span class="text-danger">{{ $errors->first('online') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.blog.fields.online_helper') }}</span>
                </div>

                <div class="row">
                    <div class="form-group col-4">
                        <label class="required" for="name">{{ trans('cruds.reference.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                               name="name" id="name" value="{{ old('name', $reference->name) }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.reference.fields.name_helper') }}</span>
                    </div>
                    <div class="form-group col-4">
                        <label class="required" for="slug">{{ trans('cruds.reference.fields.slug') }}</label>
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                               name="slug" id="slug" value="{{ old('slug', $reference->slug) }}" required>
                        @if($errors->has('slug'))
                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.reference.fields.slug_helper') }}</span>
                    </div>
                    <div class="form-group col-4">
                        <label for="industries_id">{{ trans('cruds.reference.fields.industry') }}</label>
                        <select class="form-control select2 {{ $errors->has('industries') ? 'is-invalid' : '' }}"
                                name="industries_id" id="industries_id">

                            @foreach($industries as $id => $entry)
                                <option value="{{ $id }}" {{ (old('industries_id') ? old('industries_id') : $reference->industries->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach

                        </select>
                        @if($errors->has('industries'))
                            <span class="text-danger">{{ $errors->first('industries') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.reference.fields.industries_helper') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="content">{{ trans('cruds.reference.fields.content') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('content') ? 'is-invalid' : '' }}"
                              name="content" id="content">{!! old('content', $reference->content) !!}</textarea>
                    @if($errors->has('content'))
                        <span class="text-danger">{{ $errors->first('content') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.reference.fields.content_helper') }}</span>
                </div>

                <div class="row">
                    <div class="form-group col-4">
                        <label for="photo_wide">{{ trans('cruds.reference.fields.photo_wide') }}</label>
                        <span class="help-block">{{ trans('cruds.reference.fields.photo_wide_helper') }}</span>
                        <div class="needsclick dropzone {{ $errors->has('photo_wide') ? 'is-invalid' : '' }}"
                             id="photo_wide-dropzone">
                        </div>
                        @if($errors->has('photo_wide'))
                            <span class="text-danger">{{ $errors->first('photo_wide') }}</span>
                        @endif
                    </div>
                    <div class="form-group col-8">
                        <label for="photo_square">{{ trans('cruds.reference.fields.photo_square') }}</label>
                        <span class="help-block">{{ trans('cruds.reference.fields.photo_square_helper') }}</span>
                        <div class="needsclick dropzone {{ $errors->has('photo_square') ? 'is-invalid' : '' }}"
                             id="photo_square-dropzone">
                        </div>
                        @if($errors->has('photo_square'))
                            <span class="text-danger">{{ $errors->first('photo_square') }}</span>
                        @endif
                    </div>
                </div>

                <div class="row">

                    <div class="form-group col-4">
                        <label for="text_img5">{{ trans('cruds.reference.fields.text_img5') }}</label>
                        <input class="form-control {{ $errors->has('text_img5') ? 'is-invalid' : '' }}" type="text"
                               name="text_img5" id="text_img5" value="{{ old('text_img5', $reference->text_img5) }}">
                        @if($errors->has('text_img5'))
                            <span class="text-danger">{{ $errors->first('text_img5') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.reference.fields.text_img5_helper') }}</span>
                    </div>

                    <div class="form-group col-2">
                        <label for="text_img1">{{ trans('cruds.reference.fields.text_img1') }}</label>
                        <input class="form-control {{ $errors->has('text_img1') ? 'is-invalid' : '' }}" type="text"
                               name="text_img1" id="text_img1" value="{{ old('text_img1', $reference->text_img1) }}">
                        @if($errors->has('text_img1'))
                            <span class="text-danger">{{ $errors->first('text_img1') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.reference.fields.text_img2_helper') }}</span>
                    </div>
                    <div class="form-group col-2">
                        <label for="text_img2">{{ trans('cruds.reference.fields.text_img2') }}</label>
                        <input class="form-control {{ $errors->has('text_img2') ? 'is-invalid' : '' }}" type="text"
                               name="text_img2" id="text_img2" value="{{ old('text_img2', $reference->text_img2) }}">
                        @if($errors->has('text_img2'))
                            <span class="text-danger">{{ $errors->first('text_img2') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.reference.fields.text_img2_helper') }}</span>
                    </div>
                    <div class="form-group col-2">
                        <label for="text_img3">{{ trans('cruds.reference.fields.text_img3') }}</label>
                        <input class="form-control {{ $errors->has('text_img3') ? 'is-invalid' : '' }}" type="text"
                               name="text_img3" id="text_img3" value="{{ old('text_img3', $reference->text_img3) }}">
                        @if($errors->has('text_img3'))
                            <span class="text-danger">{{ $errors->first('text_img3') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.reference.fields.text_img3_helper') }}</span>
                    </div>
                    <div class="form-group col-2">
                        <label for="text_img4">{{ trans('cruds.reference.fields.text_img4') }}</label>
                        <input class="form-control {{ $errors->has('text_img4') ? 'is-invalid' : '' }}" type="text"
                               name="text_img4" id="text_img4" value="{{ old('text_img4', $reference->text_img4) }}">
                        @if($errors->has('text_img4'))
                            <span class="text-danger">{{ $errors->first('text_img4') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.reference.fields.text_img4_helper') }}</span>
                    </div>
                </div>

                {{--            <div class="row">--}}

                {{--                <div class="form-group col-4">--}}
                {{--                    <img class="my-4" height="auto" width="100%" src="{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_1) }}" alt="{{ $reference->oldimage_1 }}">--}}
                {{--                    <div><a href="{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_1) }}">{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_1) }}</a></div>--}}
                {{--                </div>--}}

                {{--                <div class="form-group col-2">--}}
                {{--                    <img class="my-4" height="auto" width="100%" src="{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_2) }}" alt="{{ $reference->oldimage_2 }}">--}}
                {{--                    <div><a href="{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_2) }}">{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_2) }}</a></div>--}}
                {{--                </div>--}}
                {{--                <div class="form-group col-2">--}}
                {{--                    <img class="my-4" height="auto" width="100%" src="{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_3) }}" alt="{{ $reference->oldimage_3 }}">--}}
                {{--                    <div><a href="{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_3) }}">{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_3) }}</a></div>--}}
                {{--                </div>--}}
                {{--                <div class="form-group col-2">--}}
                {{--                    <img class="my-4" height="auto" width="100%" src="{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_4) }}" alt="{{ $reference->oldimage_4 }}">--}}
                {{--                    <div><a href="{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_4) }}">{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_4) }}</a></div>--}}
                {{--                </div>--}}
                {{--                <div class="form-group col-2">--}}
                {{--                    <img class="my-4" height="auto" width="100%" src="{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_5) }}" alt="{{ $reference->oldimage_5 }}">--}}
                {{--                    <div><a href="{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_5) }}">{{ url('') }}{{ asset('uploads/images/' . $reference->oldimage_5) }}</a></div>--}}
                {{--                </div>--}}

                {{--            </div>--}}


                <!-- SEO fields -->
                <div class="row p-4 my-4 seo_meta">
                    <div class="form-group col-12">
                        <label for="meta_title">Meta Title</label>
                        <input class="form-control" type="text" id="meta_title" name="meta_title"
                               value="{{ old('meta_title', $reference->meta_title ?? '') }}">
                    </div>
                    <div class="form-group col-12">
                        <label for="meta_description">Meta Description</label>
                        <textarea class="form-control" id="meta_description"
                                  name="meta_description">{{ old('meta_description', $reference->meta_description ?? '') }}</textarea>
                    </div>
                </div>
                <!-- SEO fields end -->


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
                                        xhr.open('POST', '{{ route('admin.references.storeCKEditorImages') }}', true);
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
                                        data.append('crud_id', '{{ $reference->id ?? 0 }}');
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
        var uploadedPhotoSquareMap = {}
        Dropzone.options.photoSquareDropzone = {
            url: '{{ route('admin.references.storeMedia') }}',
            maxFilesize: 2, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif',
            maxFiles: 4,
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2,
                // width: 360,
                // height: 300
            },
            success: function (file, response) {
                $('form').append('<input type="hidden" name="photo_square[]" value="' + response.name + '">')
                uploadedPhotoSquareMap[file.name] = response.name
            },
            removedfile: function (file) {
                console.log(file)
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedPhotoSquareMap[file.name]
                }
                $('form').find('input[name="photo_square[]"][value="' + name + '"]').remove()
            },
            init: function () {
                @if(isset($reference) && $reference->photo_square)
                var files = {!! json_encode($reference->photo_square) !!}
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
                    $('form').append('<input type="hidden" name="photo_square[]" value="' + file.file_name + '">')
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
    <script>
        Dropzone.options.photoWideDropzone = {
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
                // width: 750,
                // height: 300
            },
            success: function (file, response) {
                $('form').find('input[name="photo_wide"]').remove()
                $('form').append('<input type="hidden" name="photo_wide" value="' + response.name + '">')
            },
            removedfile: function (file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="photo_wide"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            },
            init: function () {
                @if(isset($reference) && $reference->photo_wide)
                var file = {!! json_encode($reference->photo_wide) !!}
                this.options.addedfile.call(this, file)
                this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                file.previewElement.classList.add('dz-complete')
                $('form').append('<input type="hidden" name="photo_wide" value="' + file.file_name + '">')
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
