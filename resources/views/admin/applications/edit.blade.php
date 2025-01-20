@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between">

            <div class="w-50">{{ trans('global.edit') }} {{ trans('cruds.application.title_singular') }}</div>
            <div class="w-50 text-right">
                @if(array_key_exists(app()->getLocale(), config('panel.available_languages')) && $application->translate(app()->getLocale()))
                    <a class="blue" href="{{ route('categories.'.app()->getLocale(), ['app_slug' => $application->slug]) }}" target="_blank">
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
            <form method="POST" action="{{ route("admin.applications.update", [$application->id]) }}"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf
                {{--            <div class="form-group">--}}
                {{--                <div class="form-check {{ $errors->has('online') ? 'is-invalid' : '' }}">--}}
                {{--                    <input type="hidden" name="online" value="0">--}}
                {{--                    <input class="form-check-input" type="checkbox" name="online" id="online" value="1" {{ $application->online || old('online', 0) === 1 ? 'checked' : '' }}>--}}
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
                               name="name" id="name" value="{{ old('name', $application->name) }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.name_helper') }}</span>
                    </div>
                    <div class="form-group col-6">
                        <label class="required" for="slug">{{ trans('cruds.application.fields.slug') }}</label>
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                               name="slug" id="slug" value="{{ old('slug', $application->slug) }}" required>
                        @if($errors->has('slug'))
                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.slug_helper') }}</span>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="form-group col-12 align-items-center">
                        <label for="image">{{ trans('cruds.application.fields.image') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}"
                             id="image-dropzone"></div>
                        @if($errors->has('image'))
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.image_helper') }}</span>
                    </div>
                </div>

                {{--                <div class="form-group">--}}
                {{--                    <label for="categories">{{ trans('cruds.application.fields.categories') }}</label>--}}
                {{--                    <div style="padding-bottom: 4px">--}}
                {{--                        <span class="btn btn-info btn-xs select-all"--}}
                {{--                              style="border-radius: 0">{{ trans('global.select_all') }}</span>--}}
                {{--                        <span class="btn btn-info btn-xs deselect-all"--}}
                {{--                              style="border-radius: 0">{{ trans('global.deselect_all') }}</span>--}}
                {{--                    </div>--}}
                {{--                    <select class="form-control select2 {{ $errors->has('categories') ? 'is-invalid' : '' }}"--}}
                {{--                            name="categories[]" id="categories" multiple>--}}
                {{--                        @foreach($categories as $id => $category)--}}
                {{--                            <option value="{{ $id }}" {{ (in_array($id, old('categories', [])) || $application->categories->contains($id)) ? 'selected' : '' }}>{{ $category }}</option>--}}
                {{--                        @endforeach--}}
                {{--                    </select>--}}
                {{--                    @if($errors->has('categories'))--}}
                {{--                        <span class="text-danger">{{ $errors->first('categories') }}</span>--}}
                {{--                    @endif--}}
                {{--                    <span class="help-block">{{ trans('cruds.application.fields.categories_helper') }}</span>--}}
                {{--                </div>--}}


                <!-- SEO fields -->
                <div class="row p-4 my-4 seo_meta">
                    <div class="form-group col-12">
                        <label for="meta_title">Meta Title</label>
                        <input class="form-control" type="text" id="meta_title" name="meta_title"
                               value="{{ old('meta_title', $application->meta_title ?? '') }}">
                    </div>
                    <div class="form-group col-12">
                        <label for="meta_description">Meta Description</label>
                        <textarea class="form-control" id="meta_description"
                                  name="meta_description">{{ old('meta_description', $application->meta_description ?? '') }}</textarea>
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

            <form id="translateButtonForm" method="POST" class="" action="{{ route("admin.translation.granular") }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="form-check {{ $errors->has('online') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="id" id="id" value="{{ $application->id }}">
                        <input type="hidden" name="model_translation" id="model_translation" value="application_translations">
                        <input type="hidden" name="foreign_key" id="foreign_key" value="application_id">
                        <input type="hidden" name="language" id="language" value="{{ app()->getLocale() }}">
                        <button type="submit" class="btn btn-success">{{ trans('admin.translation_button', ['language' => strtoupper(app()->getLocale())]) }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/slugs.js') }}"></script>
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
