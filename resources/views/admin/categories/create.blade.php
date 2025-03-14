@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.category.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.categories.store") }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    {{--                <div class="form-check {{ $errors->has('online') ? 'is-invalid' : '' }}">--}}
                    {{--                    <input type="hidden" name="online" value="0">--}}
                    {{--                    <input class="form-check-input" type="checkbox" name="online" id="online" value="1" {{ old('online', 0) == 1 || old('online') === null ? 'checked' : '' }}>--}}
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
                               name="name" id="name" value="{{ old('name', '') }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.category.fields.name_helper') }}</span>
                    </div>

                    <div class="form-group col-12 col-xl-5">
                        <label class="required" for="slug">{{ trans('cruds.category.fields.slug') }}</label>
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                               name="slug" id="slug" value="{{ old('slug', '') }}" required>
                        @if($errors->has('slug'))
                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.category.fields.slug_helper') }}</span>
                    </div>

                    <div class="form-group col-12 col-xl-2">
                        <label for="page_views">{{ trans('cruds.category.fields.page_views') }}</label>
                        <input disabled class="form-control {{ $errors->has('page_views') ? 'is-invalid' : '' }}"
                               type="number" name="page_views" id="page_views" value="{{ old('page_views', 0) }}"
                               step="1">
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
                    <select class="form-control select2 {{ $errors->has('application') ? 'is-invalid' : '' }}" name="application_id" id="application_id" {{ isset($_GET['application_id']) ? 'disabled' : '' }} required>
                        <option value="" {{ old('application_id', $_GET['application_id'] ?? '') == '' ? 'selected' : '' }}>
                            -- Please select --
                        </option>

                        @foreach($applications as $id => $entry)
                            <option value="{{ $id }}" {{ (old('application_id') ? old('application_id') : $_GET['application_id'] ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('application'))
                        <span class="text-danger">{{ $errors->first('application') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.application_helper') }}</span>
                </div>


                {{--            <div class="form-group">--}}
                {{--                <label for="product_image_id">{{ trans('cruds.category.fields.product_image') }}</label>--}}
                {{--                <select class="form-control select2 {{ $errors->has('product_image') ? 'is-invalid' : '' }}" name="product_image_id" id="product_image_id">--}}
                {{--                    @foreach($product_images as $id => $entry)--}}
                {{--                        <option value="{{ $id }}" {{ old('product_image_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>--}}
                {{--                    @endforeach--}}
                {{--                </select>--}}
                {{--                @if($errors->has('product_image'))--}}
                {{--                    <span class="text-danger">{{ $errors->first('product_image') }}</span>--}}
                {{--                @endif--}}
                {{--                <span class="help-block">{{ trans('cruds.category.fields.product_image_helper') }}</span>--}}
                {{--            </div>--}}

                {{--            <div class="form-group">--}}
                {{--                <label for="applications">{{ trans('cruds.category.fields.applications') }}</label>--}}
                {{--                <div style="padding-bottom: 4px">--}}
                {{--                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>--}}
                {{--                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>--}}
                {{--                </div>--}}
                {{--                <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}" name="applications[]" id="applications" multiple>--}}
                {{--                    @foreach($applications as $id => $application)--}}
                {{--                        <option value="{{ $id }}" {{ in_array($id, old('applications', [])) ? 'selected' : '' }}>{{ $application }}</option>--}}
                {{--                    @endforeach--}}
                {{--                </select>--}}
                {{--                @if($errors->has('applications'))--}}
                {{--                    <span class="text-danger">{{ $errors->first('applications') }}</span>--}}
                {{--                @endif--}}
                {{--                <span class="help-block">{{ trans('cruds.category.fields.applications_helper') }}</span>--}}
                {{--            </div>--}}

                <div class="form-group">
                    @if(isset($_GET['application_id']))
                        <input type="hidden" name="application_id" value="{{ $_GET['application_id'] }}">
                        <input type="hidden" name="form_application" value="1">
                    @endif
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

    </script>
@endsection
