@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.translationCenter.title_singular') }}
        </div>

        <div class="card-body">

            <ul class="nav nav-tabs">
                @foreach($languages as $lang)
                    <li class="nav-item">
                        <a class="nav-link @if($loop->first) active @endif" href="#{{ $lang }}" data-toggle="tab">{{ strtoupper($lang) }}</a>
                    </li>
                @endforeach
            </ul>

            <div class="form-group">
                <label for="file-select">Select File:</label>
                <select id="file-select" class="form-control" onchange="location.href='?file='+this.value;">
                    <option value="">-- Select File --</option>
                    @foreach($translations[$languages[0]] as $file => $keys)
                        <option value="{{ $file }}" {{ request('file') == $file ? 'selected' : '' }}>{{ $file }}</option>
                    @endforeach
                </select>
            </div>

            @if(request('file'))
                <div class="tab-content mt-3">
                    @foreach($languages as $lang)
                        <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $lang }}">
                            <h4>{{ ucfirst($lang) }}</h4>
                            <form method="POST" action="{{ route('admin.translation.strings.update', $lang) }}">
                                @csrf
                                <input type="hidden" name="file" value="{{ request('file') }}">

                                @foreach($translations[$lang][request('file')] as $key => $value)
                                    <div class="form-group">
                                        <label>{{ $key }}</label>
                                        <input type="text" name="translations[{{ $key }}]" value="{{ $value }}" class="form-control">
                                    </div>
                                @endforeach

                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

@endsection
@section('scripts')
    @parent
@endsection
