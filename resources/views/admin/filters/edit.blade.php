@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="w-50">{{ trans('global.edit') }} {{ trans('cruds.filter.title_singular') }}</div>
            <div class="w-50 text-right">
                @if(array_key_exists(app()->getLocale(), config('panel.available_languages')) && $filter->translate(app()->getLocale()) && $category)
                    <a class="blue" href="{{ route('category.'.app()->getLocale(), ['app_slug' => $application->slug, 'cat_slug' => $category->slug]) }}?brand={{ 'xxx' }}&filter={{ $filter->name }}" target="_blank">
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
            <form method="POST" action="{{ route("admin.filters.update", [$filter->id]) }}"
                  enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="form-group">
                    @if($errors->has('online'))
                        <span class="text-danger">{{ $errors->first('online') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.filter.fields.online_helper') }}</span>
                </div>

                <div class="row">

                    <div class="form-group col-12 col-xl-5">
                        <label class="required" for="name">{{ trans('cruds.filter.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                               name="name" id="name" value="{{ old('name', $filter->name) }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.filter.fields.name_helper') }}</span>
                    </div>

                    <div class="form-group col-12 col-xl-5">
                        <label for="slug">{{ trans('cruds.filter.fields.slug') }}</label>
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                               name="slug" id="slug" value="{{ old('slug', $filter->slug) }}">
                        @if($errors->has('slug'))
                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.filter.fields.slug_helper') }}</span>
                    </div>

                    <div class="form-group col-12 col-xl-2">
                        <label class="required" for="brand_id">{{ trans('cruds.product.fields.category') }}</label>
                        <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id" required>
                            <option value="" {{ old('category_id', $filter->category_id ?? '') == '' ? 'selected' : '' }}>
                                -- Please select --
                            </option>

                            @foreach($categories as $id => $entry)
                                <option value="{{ $id }}" {{ (old('category_id') ? old('category_id') : $filter->category_id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('category'))
                            <span class="text-danger">{{ $errors->first('category') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.category_helper') }}</span>
                    </div>
                </div>

                <!-- SEO fields -->
                <div class="row p-4 my-4 seo_meta">
                    <div class="form-group col-12">
                        <label for="meta_title">Meta Title</label>
                        <input class="form-control" type="text" id="meta_title" name="meta_title"
                               value="{{ old('meta_title', $filter->meta_title ?? '') }}">
                    </div>
                    <div class="form-group col-12">
                        <label for="meta_description">Meta Description</label>
                        <textarea class="form-control" id="meta_description"
                                  name="meta_description">{{ old('meta_description', $filter->meta_description ?? '') }}</textarea>
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
                            <input type="hidden" name="id" id="id" value="{{ $filter->id }}">
                            <input type="hidden" name="model_translation" id="model_translation" value="filter_translations">
                            <input type="hidden" name="foreign_key" id="foreign_key" value="filter_id">
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
                        <input type="hidden" name="id" id="id" value="{{ $filter->id }}">
                        <input type="hidden" name="model_translation" id="model_translation" value="filter_translations">
                        <input type="hidden" name="foreign_key" id="foreign_key" value="filter_id">
                        <input type="hidden" name="language" id="language" value="{{ app()->getLocale() }}">
                        <button type="submit" class="btn btn-success">{{ trans('admin.translation_button', ['language' => strtoupper(app()->getLocale())]) }}</button>
                    </div>
                </div>
            </form>
        </div>

@endsection

@section('scripts')
@endsection
