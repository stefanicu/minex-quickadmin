@extends('layouts.frontend')
@section('content')

    <div class="container">
        @if($blog)
            <h1 class="py-4">--- {{ $blog->name }}</h1>
            <div class="row">
                <div class="col-12">{!! $blog->content !!}</div>
            </div>
        @endif
    </div>

@endsection
@section('scripts')
    @parent
@endsection
