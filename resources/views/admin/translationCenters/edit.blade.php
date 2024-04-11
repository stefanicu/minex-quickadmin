@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.translationCenter.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.translation-centers.update", [$translationCenter->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.translationCenter.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $translationCenter->name) }}" required>
                @if(app()->getLocale() !== 'en')
                    <span class="translation_guide">
                        EN: {{ $translationCenter->{'name:en'} }}
                    </span>
                @endif

                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.translationCenter.fields.name_helper') }}</span>
            </div>

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
