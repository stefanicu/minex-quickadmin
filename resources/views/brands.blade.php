@extends('layouts.frontend')
@section('content')

    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('pages.home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ trans('pages.brands') }}</li>
            </ol>
        </nav>
        @php
            if($page && $page->getImageAttribute()){
                $image_url = $page->getImageAttribute()->getUrl();
            }else{
                $image_url = asset('/img/headers/aplicatie-xl.jpg');
            }
        @endphp

        <figure class="d-flex align-items-center section--figure">
            <picture data-alt="Category">
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-xl.jpg') }}" media="(min-width: 1200px)">--}}
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-lg.jpg') }}" media="(min-width: 992px)">--}}
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-md.jpg') }}" media="(min-width: 576px)">--}}
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-sm.jpg') }}" media="(max-width: 576px)">--}}
                <img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ $image_url }}"
                     alt="{{ trans('pages.page') }}" data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ $image_url }}" alt="{{ $page ? $page->name : trans('pages.brands') }}">
                </noscript>
            </picture>
        </figure>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="h2">{{ trans('pages.brands') }}</h1>
                <hr>
                <div class="py-4">
                    {!! optional($page)->content !!}
                </div>

                <ul id="brd_id" class="justify-content-md list-group list-group-flush row flex-row flex-wrap">
                    @foreach($brands as $brand)
                        <li class="col-6 col-md-4 col-lg-3 text-center d-flex align-items-center list-group-item">
                            <a href="{{ route('pages.'.app()->getLocale(), ['slug' => $brand->slug]) }}" class="w-100">
                                @if($brand->getPhotoAttribute() !== null)
                                    <figure class="brand_image">
                                        <img
                                                srcset="{{ $brand->getPhotoAttribute()->getUrl() }}"
                                                alt="{{ $brand->name }}"
                                                title="{{ $brand->name }}"
                                                class="img-hover lozad img-fluid lazy-fade">
                                    </figure>
                                @else
                                    <div class="brand_image_default">No image<br>{{ $brand->name }}</div>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if($page && $page->call_to_action)
                <div class="w-100 py-4">
                    <p class="text-center py-2 px-4 my-4">
                        <a href="{{ url('') . '/' . app()->getLocale() . '/' . $page->call_to_action_link }}" target="_blank" class="btn btn-primary btn-lg p-4 mx-auto">{{ $page->call_to_action }}</a>
                    </p>
                </div>
            @endif
        </div>
    </div>

@endsection
@section('scripts')
    @parent
@endsection
