@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.translationCenter.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.translation-centers.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="form-check {{ $errors->has('online') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="online" value="0">
                    <input class="form-check-input" type="checkbox" name="online" id="online" value="1" {{ old('online', 0) == 1 || old('online') === null ? 'checked' : '' }}>
                    <label class="form-check-label" for="online">{{ trans('cruds.translationCenter.fields.online') }}</label>
                </div>
                @if($errors->has('online'))
                    <span class="text-danger">{{ $errors->first('online') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.translationCenter.fields.online_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.translationCenter.fields.language') }}</label>
                <select class="form-control {{ $errors->has('language') ? 'is-invalid' : '' }}" name="language" id="language" required>
                    <option value disabled {{ old('language', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\TranslationCenter::LANGUAGE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('language', 'en') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('language'))
                    <span class="text-danger">{{ $errors->first('language') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.translationCenter.fields.language_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.translationCenter.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.translationCenter.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="slug">{{ trans('cruds.translationCenter.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', '') }}" required>
                @if($errors->has('slug'))
                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.translationCenter.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.translationCenter.fields.section') }}</label>
                <select class="form-control {{ $errors->has('section') ? 'is-invalid' : '' }}" name="section" id="section" required>
                    <option value disabled {{ old('section', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\TranslationCenter::SECTION_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('section', 'strings') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('section'))
                    <span class="text-danger">{{ $errors->first('section') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.translationCenter.fields.section_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection