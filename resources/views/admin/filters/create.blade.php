@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.filter.title_singular') }}
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route("admin.filters.store") }}" enctype="multipart/form-data">
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
                               name="name" id="name" value="{{ old('name', '') }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.filter.fields.name_helper') }}</span>
                    </div>

                    <div class="form-group col-12 col-xl-5">
                        <label for="slug">{{ trans('cruds.filter.fields.slug') }}</label>
                        <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text"
                               name="slug" id="slug" value="{{ old('slug', '') }}">
                        @if($errors->has('slug'))
                            <span class="text-danger">{{ $errors->first('slug') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.filter.fields.slug_helper') }}</span>
                    </div>

                    <div class="form-group col-12 col-xl-2">
                        <label class="required" for="brand_id">{{ trans('cruds.product.fields.category') }}</label>
                        <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id" {{ isset($_GET['category_id']) ? 'disabled' : '' }} required>
                            <option value="" {{ old('category_id', $_GET['category_id'] ?? '') == '' ? 'selected' : '' }}>
                                -- Please select --
                            </option>

                            @foreach($categories as $id => $entry)
                                <option value="{{ $id }}" {{ (old('category_id') ? old('category_id') : $_GET['category_id'] ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('category'))
                            <span class="text-danger">{{ $errors->first('category') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.product.fields.category_helper') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    @if(isset($_GET['category_id']))
                        <input type="hidden" name="category_id" value="{{ $_GET['category_id'] }}">
                        <input type="hidden" name="form_category" value="1">
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

@endsection
