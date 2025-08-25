@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="w-50">{{ trans('global.edit') }} {{ trans('cruds.product.title_singular') }}</div>
            <div class="w-50 text-right">
                @if(array_key_exists(app()->getLocale(), config('panel.available_languages')) && $product->translate(app()->getLocale()) && isset($application->slug) && isset($category->slug) && $product->online)
                    <a class="blue" href="{{ route('product.'.app()->getLocale(), ['app_slug'=>$application->slug, 'cat_slug'=>$category->slug, 'prod_slug'=>$product->slug]) }}" target="_blank">
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
            <form method="POST" action="{{ route("admin.products.update", [$product->id]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <div class="form-check {{ $errors->has('online') ? 'is-invalid' : '' }}">
                        <input type="hidden" name="online" value="0">
                        <input class="form-check-input" type="checkbox" name="online" id="online" value="1" {{ old('online', optional($product->translations->firstWhere('locale', app()->getLocale()))->online) ? 'checked' : '' }}>
                        <label class="form-check-label" for="online">{{ trans('cruds.product.fields.online') }}</label>
                    </div>
                    @if($errors->has('online'))
                        <span class="text-danger">{{ $errors->first('online') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.product.fields.online_helper') }}</span>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-xl-4">
                        <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name',$product->name) }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
                    </div>
                    <div class="form-group col-12 col-xl-4">
                        <label class="required" for="slug">{{ trans('cruds.product.fields.slug') }}</label>
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug',$product->slug) }}" required>
                        @if($errors->has('slug'))
                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.slug_helper') }}</span>
                    </div>
                    <div class="form-group col-12 col-xl-4">
                        <label class="required" for="brand_id">{{ trans('cruds.product.fields.brand') }}</label>
                        <select class="form-control select2 {{ $errors->has('brand') ? 'is-invalid' : '' }}"
                                name="brand_id" id="brand_id" required>
                            @foreach($brands as $id => $entry)
                                <option value="{{ $id }}" {{ (old('brand_id') ? old('brand_id') : $product->brand->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('brand'))
                            <span class="text-danger">{{ $errors->first('brand') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.brand_helper') }}</span>
                    </div>
                </div>


                <div class="row">
                    <div id="applications" class="form-group col-12 col-xl-4">
                        <label for="applications">{{ trans('cruds.product.fields.application') }}</label>
                        <select class="form-control select2 {{ $errors->has('application') ? 'is-invalid' : '' }}" name="application_id" id="application_id">
                            <option value="" disabled {{ old('application_id', $product->application_id) ? '' : 'selected' }}>
                                -- Select an application --
                            </option>
                            @foreach($applications as $id => $application)
                                <option value="{{ $id }}" {{ (old('application_id', $product->application_id) == $id) ? 'selected' : '' }}>
                                    {{ $application }}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('applications'))
                            <span class="text-danger">{{ $errors->first('applications') }}</span>
                        @endif
                        <span class="help-block"></span>
                    </div>
                    <div id="categories" class="form-group col-12 col-xl-4">
                        <label for="categories">{{ trans('cruds.product.fields.category') }}</label>
                        <select class="form-control select2 {{ $errors->has('categories') ? 'is-invalid' : '' }}" name="category_id" id="category_id">
                            <option value="" disabled {{ old('category_id', $product->category_id) ? '' : 'selected' }}>
                                -- Select a category --
                            </option>
                            @foreach($categories as $id => $category)
                                <option value="{{ $id }}" {{ (old('category_id', $product->category_id) == $id) ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('categories'))
                            <span class="text-danger">{{ $errors->first('categories') }}</span>
                        @endif
                        <span class="help-block"></span>
                    </div>
                    <div id="filters" class="form-group col-12 col-xl-4">
                        <label for="filters">{{ trans('cruds.product.fields.filter') }}</label>
                        <select class="form-control select2 {{ $errors->has('filters') ? 'is-invalid' : '' }}" name="filter_id" id="filter_id">
                            <option value="" disabled {{ old('filter_id', $product->filter_id) ? '' : 'selected' }}>
                                -- Select a filter --
                            </option>
                            @foreach($filters as $id => $filter)
                                <option value="{{ $id }}" {{ (old('filter_id', $product->filter_id) == $id) ? 'selected' : '' }}>
                                    {{ $filter }}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('filters'))
                            <span class="text-danger">{{ $errors->first('filters') }}</span>
                        @endif
                        <span class="help-block"></span>
                    </div>
                </div>


                <div class="row">
                    <ul class="nav nav-tabs mt-2 col-12">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#description">{{ trans('cruds.product.fields.description') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#specifications">{{ trans('cruds.product.fields.specifications') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#advantages">{{ trans('cruds.product.fields.advantages') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#usages">{{ trans('cruds.product.fields.usages') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#accessories">{{ trans('cruds.product.fields.accessories') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#references">{{ trans('cruds.product.fields.references') }}</a>
                        </li>
                    </ul>

                    <div class="tab-content pt-4 mb-2 col-12">
                        <div id="description" class="form-group tab-pane fade in active show">
                            {{--                    <label for="description">{{ trans('cruds.product.fields.description') }}</label>--}}
                            <textarea
                                    class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                    name="description"
                                    id="description">{!! old('description', $product->description) !!}</textarea>
                            @if($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.description_helper') }}</span>
                        </div>

                        <div id="specifications" class="form-group tab-pane fade in">
                            {{--                    <label for="specifications">{{ trans('cruds.product.fields.specifications') }}</label>--}}
                            <textarea
                                    class="form-control ckeditor {{ $errors->has('specifications') ? 'is-invalid' : '' }}"
                                    name="specifications"
                                    id="specifications">{!! old('specifications', $product->specifications) !!}</textarea>
                            @if($errors->has('specifications'))
                                <span class="text-danger">{{ $errors->first('specifications') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.specifications_helper') }}</span>
                        </div>

                        <div id="advantages" class="form-group tab-pane fade in">
                            {{--                    <label for="advantages">{{ trans('cruds.product.fields.advantages') }}</label>--}}
                            <textarea class="form-control ckeditor {{ $errors->has('advantages') ? 'is-invalid' : '' }}"
                                      name="advantages"
                                      id="advantages">{!! old('advantages', $product->advantages) !!}</textarea>
                            @if($errors->has('advantages'))
                                <span class="text-danger">{{ $errors->first('advantages') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.advantages_helper') }}</span>
                        </div>

                        <div id="usages" class="form-group tab-pane fade in">
                            {{--                    <label for="usages">{{ trans('cruds.product.fields.usages') }}</label>--}}
                            <textarea class="form-control ckeditor {{ $errors->has('usages') ? 'is-invalid' : '' }}"
                                      name="usages" id="usages">{!! old('usages', $product->usages) !!}</textarea>
                            @if($errors->has('usages'))
                                <span class="text-danger">{{ $errors->first('usage') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.usage_helper') }}</span>
                        </div>

                        <div id="accessories" class="form-group tab-pane fade in">
                            {{--                    <label for="usage">{{ trans('cruds.product.fields.accessories') }}</label>--}}
                            <textarea
                                    class="form-control ckeditor {{ $errors->has('accessories') ? 'is-invalid' : '' }}"
                                    name="accessories"
                                    id="accessories">{!! old('accessories', $product->accessories) !!}</textarea>
                            @if($errors->has('accessories'))
                                <span class="text-danger">{{ $errors->first('accessories') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.accessories_helper') }}</span>
                        </div>
                        <div id="references" class="form-group tab-pane fade in">
                            {{--                    <label for="references">{{ trans('cruds.product.fields.references') }}</label>--}}
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all"
                                      style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all"
                                      style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 {{ $errors->has('references') ? 'is-invalid' : '' }}"
                                    name="references[]" id="references" multiple>
                                @foreach($references as $id => $reference)
                                    <option value="{{ $id }}" {{ (in_array($id, old('references', [])) || $product->references->contains($id)) ? 'selected' : '' }}>{{ $reference }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('references'))
                                <span class="text-danger">{{ $errors->first('references') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.product.fields.references_helper') }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12 align-items-center">
                        <label for="main_photo">{{ trans('cruds.product.fields.main_photo') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('main_photo') ? 'is-invalid' : '' }}"
                             id="main_photo-dropzone">
                        </div>
                        @if($errors->has('main_photo'))
                            <span class="text-danger">{{ $errors->first('main_photo') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.main_photo_helper') }}</span>
                    </div>

                    <div class="form-group col-12 align-items-center">
                        <label for="photo">{{ trans('cruds.product.fields.photo') }}</label>
                        <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}"
                             id="photo-dropzone">
                        </div>
                        @if($errors->has('photo'))
                            <span class="text-danger">{{ $errors->first('photo') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.photo_helper') }}</span>
                    </div>
                </div>


                <!-- SEO fields -->
                <div class="row p-4 my-4 seo_meta">
                    <div class="form-group col-12">
                        <label for="meta_title">Meta Title</label>
                        <input class="form-control" type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title ?? '') }}">
                    </div>
                    <div class="form-group col-12">
                        <label for="meta_description">Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                    </div>
                </div>
                <!-- SEO fields end -->


                <div class="form-group">
                    <input type="hidden" name="locale" value="{{ app()->getLocale() }}">
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
                            <input type="hidden" name="id" id="id" value="{{ $product->id }}">
                            <input type="hidden" name="model_translation" id="model_translation" value="product_translations">
                            <input type="hidden" name="foreign_key" id="foreign_key" value="product_id">
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
                        <input type="hidden" name="id" id="id" value="{{ $product->id }}">
                        <input type="hidden" name="model_translation" id="model_translation" value="product_translations">
                        <input type="hidden" name="foreign_key" id="foreign_key" value="product_id">
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
        $(document).ready(function () {
            var selectedCategoryId = "{{ $product->category_id }}";
            var selectedFilterId = "{{ $product->filter_id }}";
            var isInitialLoad = true;

            // Fetch categories based on application
            function fetchCategories(applicationId, selectedCategoryId = null) {
                $('#category_id').html('<option value="">-- Select a category --</option>');
                $('#filter_id').html('<option value="">-- Select a filter --</option>');

                if (!applicationId) return;

                $.get("{{ route('admin.get.categories') }}", {application_id: applicationId}, function (data) {
                    data.forEach(function (category) {
                        $('#category_id').append(
                            `<option value="${category.category_id}">${category.name}</option>`
                        );
                    });

                    if (selectedCategoryId) {
                        $('#category_id').val(selectedCategoryId).trigger('change');
                    }
                });
            }

            // Fetch filters based on category
            function fetchFilters(categoryId, selectedFilterId = null) {
                $('#filter_id').html('<option value="">-- Select a filter --</option>');

                if (!categoryId) return;

                $.get("{{ route('admin.get.filters') }}", {category_id: categoryId}, function (data) {
                    data.forEach(function (filter) {
                        $('#filter_id').append(
                            `<option value="${filter.filter_id}">${filter.name}</option>`
                        );
                    });

                    if (selectedFilterId) {
                        $('#filter_id').val(selectedFilterId);
                    }
                });
            }

            // On application change
            $('#application_id').on('change', function () {
                fetchCategories($(this).val());
            });

            // On category change
            $('#category_id').on('change', function () {
                var categoryId = $(this).val();
                fetchFilters(categoryId, isInitialLoad ? selectedFilterId : null);
                isInitialLoad = false;
            });

            // Initial load
            var selectedApplicationId = $('#application_id').val();
            if (selectedApplicationId) {
                fetchCategories(selectedApplicationId, selectedCategoryId);
            }
        });
    </script>
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
                                    xhr.open('POST', '{{ route('admin.products.storeCKEditorImages') }}', true);
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
                                    data.append('crud_id', '{{ $product->id ?? 0 }}');
                                    xhr.send(data);
                                });
                            })
                        }
                    };
                }
            }

            var allEditors = document.querySelectorAll('.ckeditor');
            for (var i = 0; i < allEditors.length; ++i) {
                ClassicEditor.create(allEditors[i], {
                    extraPlugins: [SimpleUploadAdapter]
                });
            }
        });
    </script>
    <script>
        var uploadedPhotoMap = {}
        Dropzone.options.photoDropzone = {
            url: '{{ route('admin.products.storeMedia') }}', maxFilesize: 1, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif', maxFiles: 20, addRemoveLinks: true, headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }, params: {
                size: 1,
            }, success: function (file, response) {
                $('form').append('<input type="hidden" name="photo[]" value="' + response.name + '">')
                uploadedPhotoMap[file.name] = response.name
            }, removedfile: function (file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedPhotoMap[file.name]
                }
                $('form').find('input[name="photo[]"][value="' + name + '"]').remove()
            }, init: function () {
                @if(isset($product) && $product->photo)
                var files = {!! json_encode($product->photo) !!}
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
                    $('form').append('<input type="hidden" name="photo[]" value="' + file.file_name + '">')
                }
                @endif
            }, error: function (file, response) {
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
        Dropzone.options.mainPhotoDropzone = {
            url: '{{ route('admin.products.storeMedia') }}', maxFilesize: 1, // MB
            acceptedFiles: '.jpeg,.jpg,.png,.gif', maxFiles: 1, addRemoveLinks: true, headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }, params: {
                size: 1,
            }, success: function (file, response) {
                $('form').find('input[name="main_photo"]').remove()
                $('form').append('<input type="hidden" name="main_photo" value="' + response.name + '">')
            }, removedfile: function (file) {
                file.previewElement.remove()
                if (file.status !== 'error') {
                    $('form').find('input[name="main_photo"]').remove()
                    this.options.maxFiles = this.options.maxFiles + 1
                }
            }, init: function () {
                @if(isset($product) && $product->main_photo)
                var file = {!! json_encode($product->main_photo) !!}
                this.options.addedfile.call(this, file)
                this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                file.previewElement.classList.add('dz-complete')
                $('form').append('<input type="hidden" name="main_photo" value="' + file.file_name + '">')
                this.options.maxFiles = this.options.maxFiles - 1
                @endif
            }, error: function (file, response) {
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
