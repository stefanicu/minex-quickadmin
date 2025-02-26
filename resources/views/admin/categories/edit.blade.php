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
                        <label class="required" for="slug">{{ trans('cruds.category.fields.slug') }}</label>
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                               name="slug" id="slug" value="{{ old('slug', $category->slug) }}" required>
                        @if($errors->has('slug'))
                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.category.fields.slug_helper') }}</span>
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

                <div class="form-group col-12 col-sm-2">
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
                    <button class="btn btn-danger" type="submit">
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
                            <button type="submit" class="btn btn-warning">{{ trans('admin.translation_button_all') }}</button>
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
                        <button type="submit" class="btn btn-success">{{ trans('admin.translation_button', ['language' => strtoupper(app()->getLocale())]) }}</button>
                    </div>
                </div>
            </form>
        </div>


        @endsection

        @section('scripts')
            <script src="{{ asset('js/slugs.js') }}"></script>
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
