@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="w-50">{{ trans('global.edit') }} {{ trans('cruds.category.title_singular') }}</div>
            <div class="w-50 text-right">
                @if(array_key_exists(app()->getLocale(), config('panel.available_languages')) && $category->translate(app()->getLocale()) && $application)
                    <a class="blue" href="{{ route('category.'.app()->getLocale(), ['app_slug' => $application->slug, 'cat_slug' => $category->slug]) }}" target="_blank">
                        <svg class="mr-1" width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="Interface / External_Link">
                                <path id="Vector" d="M10.0002 5H8.2002C7.08009 5 6.51962 5 6.0918 5.21799C5.71547 5.40973 5.40973 5.71547 5.21799 6.0918C5 6.51962 5 7.08009 5 8.2002V15.8002C5 16.9203 5 17.4801 5.21799 17.9079C5.40973 18.2842 5.71547 18.5905 6.0918 18.7822C6.5192 19 7.07899 19 8.19691 19H15.8031C16.921 19 17.48 19 17.9074 18.7822C18.2837 18.5905 18.5905 18.2839 18.7822 17.9076C19 17.4802 19 16.921 19 15.8031V14M20 9V4M20 4H15M20 4L13 11" stroke="#003eff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                        </svg>
                        Preview
                    </a>
                @else
                    {{ __('admin.no_page_link_yet') }}
                @endif
            </div>
        </div>

        @if(session('success'))
            <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success! 🎉</strong> {{ session('success') }}
            </div>

            <script>
                setTimeout(function () {
                    let alert = document.getElementById('success-alert');
                    if (alert) {
                        alert.classList.remove('show');
                        alert.classList.add('fade');
                        setTimeout(() => alert.remove(), 500); // Remove it completely after fading
                    }
                }, 5000); // 3 seconds
            </script>
        @endif

        <div class="card-body">
            <form method="POST" action="{{ route("admin.categories.update", [$category->id]) }}"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    {{--                <div class="form-check {{ $errors->has('online') ? 'is-invalid' : '' }}">--}}
                    {{--                    <input type="hidden" name="online" value="0">--}}
                    {{--                    <input class="form-check-input" type="checkbox" name="online" id="online" value="1" {{ $category->online || old('online', 0) === 1 ? 'checked' : '' }}>--}}
                    {{--                    <label class="form-check-label" for="online">{{ trans('cruds.category.fields.online') }}</label>--}}
                    {{--                </div>--}}
                    @if($errors->has('online'))
                        <span class="text-danger">{{ $errors->first('online') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.category.fields.online_helper') }}</span>
                </div>

                <div class="row">

                    <div class="form-group col-12 col-xl-5">
                        <label class="required" for="name">{{ trans('cruds.category.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                               name="name" id="name" value="{{ old('name', $category->name) }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.category.fields.name_helper') }}</span>
                    </div>

                    <div class="form-group col-12 col-xl-5">
                        <label for="slug">{{ trans('cruds.category.fields.slug') }}</label>
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                               name="slug" id="slug" value="{{ old('slug', $category->slug) }}">
                        @if($errors->has('slug'))
                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.category.fields.slug_helper') }}</span>
                    </div>

                    <div class="form-group col-12 col-xl-2">
                        <label class="required" for="brand_id">{{ trans('cruds.product.fields.application') }}</label>
                        <select class="form-control select2 {{ $errors->has('application') ? 'is-invalid' : '' }}" name="application_id" id="application_id" required>
                            <option value="" {{ old('application_id', $category->application_id ?? '') == '' ? 'selected' : '' }}>
                                -- Please select --
                            </option>

                            @foreach($applications as $id => $entry)
                                <option value="{{ $id }}" {{ (old('application_id') ? old('application_id') : $category->application_id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('application'))
                            <span class="text-danger">{{ $errors->first('application') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.application_helper') }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-5">
                        <label class="required" for="title">{{ trans('cruds.category.fields.title') }}</label>
                        <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $category->title) }}" required>
                        @if($errors->has('title'))
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.category.fields.title_helper') }}</span>
                    </div>

                    <div class="form-group col-5">
                        <label for="subtitle">{{ trans('cruds.category.fields.subtitle') }}</label>
                        <input class="form-control {{ $errors->has('subtitle') ? 'is-invalid' : '' }}" type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $category->subtitle) }}">
                        @if($errors->has('subtitle'))
                            <span class="text-danger">{{ $errors->first('subtitle') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.category.fields.subtitle_helper') }}</span>
                    </div>

                    <div class="form-group col-12 col-xl-2">
                        <label for="page_views">{{ trans('cruds.category.fields.page_views') }}</label>
                        <input disabled class="form-control {{ $errors->has('page_views') ? 'is-invalid' : '' }}"
                               type="number" name="page_views" id="page_views"
                               value="{{ old('page_views', $category->page_views) }}" step="1">
                        @if($errors->has('page_views'))
                            <span class="text-danger">{{ $errors->first('page_views') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.category.fields.page_views_helper') }}</span>
                    </div>
                </div>


                <div class="form-group">
                    <label for="content">{{ trans('cruds.category.fields.content') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('content') ? 'is-invalid' : '' }}" rows="200" name="content" id="content">{!! old('content', $category->content) !!}</textarea>
                    @if($errors->has('content'))
                        <span class="text-danger">{{ $errors->first('content') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.category.fields.content_helper') }}</span>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-xl-4">
                        <label for="call_to_action">{{ trans('cruds.category.fields.call_to_action') }}</label>
                        <input class="form-control {{ $errors->has('call_to_action') ? 'is-invalid' : '' }}" type="text" name="call_to_action" id="call_to_action" value="{{ old('call_to_action', $category->call_to_action ?? '') }}">
                        @if($errors->has('call_to_action'))
                            <span class="text-danger">{{ $errors->first('call_to_action') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.category.fields.call_to_action_helper') }}</span>
                    </div>
                    <div class="form-group col-12 col-xl-8">
                        <label for="call_to_action_link">{{ trans('cruds.category.fields.call_to_action_link') }}</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ url('') . '/' . app()->getLocale() . '/' }}</span>
                            </div>
                            <input class="form-control {{ $errors->has('call_to_action_link') ? 'is-invalid' : '' }}" type="text" name="call_to_action_link" id="call_to_action_link" value="{{ old('call_to_action_link', $category->call_to_action_link ?? '') }}">
                        </div>
                        @if($errors->has('call_to_action_link'))
                            <span class="text-danger">{{ $errors->first('call_to_action_link') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.category.fields.call_to_action_link_helper') }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 align-items-center">
                        <label for="cover_photo">{{ trans('cruds.category.fields.cover_photo') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('cover_photo') ? 'is-invalid' : '' }}"
                             id="cover_photo-dropzone">
                        </div>
                        @if($errors->has('cover_photo'))
                            <span class="text-danger">{{ $errors->first('cover_photo') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.category.fields.cover_photo_helper') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="filters">{{ trans('cruds.category.fields.filters') }}</label><a class="pl-4" href="{{ route('admin.filters.create', ['category_id' => $category->id]) }}">{{ trans('cruds.category.fields.add_filter') }}</a>
                    <select class="form-control select2 {{ $errors->has('filters') ? 'is-invalid' : '' }}" name="filters[]" id="filters" multiple>
                        @foreach($filters as $filter)
                            <option value="{{ $filter->id }}"
                                    {{ $category->filters->contains('id', $filter->id) || in_array($filter->id, old('filters', [])) ? 'selected' : '' }}>
                                {{ $filter->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('filters'))
                        <span class="text-danger">{{ $errors->first('filters') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.category.fields.filters_helper') }}</span>
                </div>

                <!-- SEO fields -->
                <div class="row p-4 my-4 seo_meta">
                    <div class="form-group col-12">
                        <label for="meta_title">Meta Title</label>
                        <input class="form-control" type="text" id="meta_title" name="meta_title"
                               value="{{ old('meta_title', $category->meta_title ?? '') }}">
                    </div>
                    <div class="form-group col-12">
                        <label for="meta_description">Meta Description</label>
                        <textarea class="form-control" id="meta_description"
                                  name="meta_description">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
                    </div>
                </div>
                <!-- SEO fields end -->


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
                            <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                            <input type="hidden" name="model_translation" id="model_translation" value="category_translations">
                            <input type="hidden" name="foreign_key" id="foreign_key" value="category_id">
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
                        <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                        <input type="hidden" name="model_translation" id="model_translation" value="category_translations">
                        <input type="hidden" name="foreign_key" id="foreign_key" value="category_id">
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
                                            xhr.open('POST', '{{ route('admin.categories.storeCKEditorImages') }}', true);
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
                                            data.append('crud_id', '{{ $category->id ?? 0 }}');
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
                                        {
                                            model: 'heading1',
                                            view: 'h1',
                                            title: 'Heading 1',
                                            class: 'ck-heading_heading1'
                                        },
                                        {
                                            model: 'heading2',
                                            view: 'h2',
                                            title: 'Heading 2',
                                            class: 'ck-heading_heading2'
                                        },
                                        {
                                            model: 'heading3',
                                            view: 'h3',
                                            title: 'Heading 3',
                                            class: 'ck-heading_heading3'
                                        },
                                        {
                                            model: 'heading4',
                                            view: 'h4',
                                            title: 'Heading 4',
                                            class: 'ck-heading_heading4'
                                        }
                                    ]
                                }
                            }
                        );
                    }
                });
            </script>
            <script>
                Dropzone.options.coverPhotoDropzone = {
                    url: '{{ route('admin.categories.storeMedia') }}',
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
                        $('form').find('input[name="cover_photo"]').remove()
                        $('form').append('<input type="hidden" name="cover_photo" value="' + response.name + '">')
                    },
                    removedfile: function (file) {
                        file.previewElement.remove()
                        if (file.status !== 'error') {
                            $('form').find('input[name="cover_photo"]').remove()
                            this.options.maxFiles = this.options.maxFiles + 1
                        }
                    },
                    init: function () {
                        @if(isset($category) && $category->cover_photo)
                        var file = {!! json_encode($category->cover_photo) !!}
                        this.options.addedfile.call(this, file)
                        this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                        file.previewElement.classList.add('dz-complete')
                        $('form').append('<input type="hidden" name="cover_photo" value="' + file.file_name + '">')
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

                $(document).ready(function () {
                    // Add initial border to the checked radio button's parent
                    $('input[type="radio"]:checked').closest('.product_image').css({
                        'border': '10px solid #007bff',
                        'border-radius': '20px'
                    });

                    // Listen for changes on radio buttons
                    $('input[type="radio"]').on('change', function () {
                        // Remove the border from all parent elements
                        $('.product_image').css('border', 'none');

                        // Add border to the parent of the checked radio button
                        $(this).closest('.product_image').css({
                            'border': '10px solid #007bff',
                            'border-radius': '20px'
                        });
                    });
                    $('.product_image').on('click', function () {
                        // Find the radio button inside the clicked parent div and check it
                        $(this).find('input[type="radio"]').prop('checked', true);

                        // Optional: Add border to indicate selection
                        $('.product_image').removeClass('selected'); // Remove from all
                        $(this).addClass('selected'); // Add to the current one
                    });
                });
            </script>
@endsection
