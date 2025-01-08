@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.category.title_singular') }}
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

                <div class="form-group">
                    <label for="cover_photo">{{ trans('cruds.category.fields.product_image') }}</label>
                    <div class="row">

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-5 align-items-center text-center">

                            <div class="product_image_default m-auto">No Image</div>

                            <div class="h-10 pt-3">
                                <input
                                        checked
                                        type="radio"
                                        name="product_image_id" value=""
                                >
                                <span>Reset</span>
                            </div>
                        </div>

                        @foreach($product_images as $product)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-5 align-items-center text-center">

                                @if($product->getMainPhotoAttribute())
                                    <label for="image-{{ $product->id }}">
                                        <img src="{{ $product->getMainPhotoAttribute()->getUrl('thumb') }}"
                                             alt="{{ $product->name }}" style="width: 100px; height: auto;">
                                    </label>
                                @else
                                    <div class="product_image_default m-auto">No Image</div>
                                @endif

                                <div class="h-10 pt-3">
                                    <input
                                            {{ (old('product_image_id') ? old('product_image_id') : $category->product_image_id ?? '') == $product->id ? 'checked' : '' }}
                                            type="radio"
                                            name="product_image_id" value="{{ $product->id }}"
                                    >
                                    <span>{{ $product->name }}</span>
                                </div>
                            </div>
                        @endforeach

                    </div>
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
            </form>
        </div>


        @endsection

        @section('scripts')
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
