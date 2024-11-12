@extends('layouts.frontend')
@section('content')

    <div class="container">
        <h1 class="py-4">{{ $blog->name }}</h1>
        <div class="row">
                <div class="col-12">{!! $blog->content !!}</div>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
@endsection
