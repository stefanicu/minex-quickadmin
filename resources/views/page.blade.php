@extends('layouts.frontend')
@section('content')
    {{--    @dd($page, $pages)--}}

    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('pages.home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $page->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="container content">
        <div class="row">
            <div class="col-12pb-4 mb-4">
                <div class="d-flex justify-content-between">
                    <h1 class="h2">{{ $page->name }}</h1>
                    @if(auth()->check())
                        <a class="position-absolute mr-2" href="{{ url('').'/admin/pages/'.$page->id.'/edit' }}" target="_blank">Edit</a>
                    @endif
                </div>

                @if($page->getImageAttribute() !== null && $page->getImageAttribute()->count()>0)
                    <img
                            srcset="{{ $page->getImageAttribute()->getUrl() }}"
                            alt="{{ $page->name }}"
                            class="img-fluid">
                @endif
                <hr>
                {!! $page->content !!}

                @if(auth()->check())
                    <a class="position-absolute mr-2" href="{{ url('').'/admin/pages/'.$page->id.'/edit' }}"
                       target="_blank">Edit</a>
                @endif
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script src="{{ asset('/js/bxSlider/page_setup.js') }}"></script>
@endsection
