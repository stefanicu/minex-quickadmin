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
        @if($page->getImageAttribute())
            @php
                $image_url = $page->getImageAttribute()->getUrl();
            @endphp
            <figure class="d-flex align-items-center section--figure">
                <picture data-alt="Category">
                    {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-xl.jpg') }}" media="(min-width: 1200px)">--}}
                    {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-lg.jpg') }}" media="(min-width: 992px)">--}}
                    {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-md.jpg') }}" media="(min-width: 576px)">--}}
                    {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-sm.jpg') }}" media="(max-width: 576px)">--}}
                    <img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ $image_url }}"
                         alt="{{ trans('pages.category') }}" data-loaded="true">
                    <noscript>
                        <img class="img-fluid lozad" src="{{ $image_url }}" alt="{{ $page->name }}">
                    </noscript>
                </picture>
            </figure>
        @endif
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

                {{--                @if($page->getImageAttribute() !== null && $page->getImageAttribute()->count()>0)--}}
                {{--                    <img--}}
                {{--                            srcset="{{ $page->getImageAttribute()->getUrl() }}"--}}
                {{--                            alt="{{ $page->name }}"--}}
                {{--                            class="img-fluid">--}}
                {{--                @endif--}}
                <hr>
                {!! $page->content !!}

                @if($page->call_to_action)
                    <p class="text-center py-2 px-4 my-4">
                        <a href="{{ url('') . '/' . app()->getLocale() . '/' . $page->call_to_action_link }}" target="_blank" class="btn btn-primary btn-lg p-4 mx-auto">{{ $page->call_to_action }}</a>
                    </p>
                @endif

                @if(auth()->check())
                    <p>
                        <a class="position-absolute mr-2" href="{{ url('').'/admin/pages/'.$page->id.'/edit' }}" target="_blank">Edit</a>
                    </p>
                @endif
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script src="{{ asset('/js/bxSlider/page_setup.js') }}"></script>
@endsection
