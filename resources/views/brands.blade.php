@extends('layouts.frontend')
@section('content')

    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('menu.home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ trans('pages.brands') }}</li>
            </ol>
        </nav>

        <figure class="d-flex align-items-center section--figure">
            <picture data-alt="Branduri">
                <source data-srcset="{{ asset('/img/headers/testimoniale-xl.jpg') }}" media="(min-width: 1200px)">
                <source data-srcset="{{ asset('/img/headers/testimoniale-lg.jpg') }}" media="(min-width: 992px)">
                <source data-srcset="{{ asset('/img/headers/testimoniale-md.jpg') }}" media="(min-width: 576px)">
                <source data-srcset="{{ asset('/img/headers/testimoniale-sm.jpg') }}" media="(max-width: 576px)">
                <img class="lozad img-fluid section--hero-img lazy-fade"
                     srcset="{{ asset('/img/headers/testimoniale-xl.jpg') }}" alt="{{ trans('pages.brands') }}"
                     data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ asset('/img/headers/testimoniale-xl.jpg') }}"
                         alt="{{ trans('pages.brands') }}">
                </noscript>
            </picture>
        </figure>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="h2">{{ trans('pages.brands') }}</h1>
                <hr>
                <ul id="brd_id" class="justify-content-md list-group list-group-flush row flex-row flex-wrap">
                    @foreach($brands as $brand)
                        <li class="col-6 col-md-4 col-lg-3 text-center d-flex align-items-center list-group-item">
                            <a href="{{ route('brand.'.app()->getLocale(), ['slug' => $brand->slug]) }}" class="w-100">
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
        </div>
    </div>

@endsection
@section('scripts')
    @parent
@endsection
