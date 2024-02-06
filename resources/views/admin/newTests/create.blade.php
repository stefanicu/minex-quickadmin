@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.newTest.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.new-tests.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nume">{{ trans('cruds.newTest.fields.nume') }}</label>
                <input class="form-control {{ $errors->has('nume') ? 'is-invalid' : '' }}" type="text" name="nume" id="nume" value="{{ old('nume', '') }}">
                @if($errors->has('nume'))
                    <span class="text-danger">{{ $errors->first('nume') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newTest.fields.nume_helper') }}</span>
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