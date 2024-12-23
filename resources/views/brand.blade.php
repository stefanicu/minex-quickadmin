@extends('layouts.frontend')
@section('content')

    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('menu.home') }}</a></li>
                <li class="breadcrumb-item"><a
                            href="{{ url('') }}/{{ trans('pages_slugs.brands') }}/">{{ trans('pages.brands') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $brand->name; ?></li>
            </ol>
        </nav>

        <figure class="d-flex align-items-center section--figure">
            <picture data-alt="Branduri">
                <source data-srcset="{{ asset('/img/headers/brands-xl.jpg') }}" media="(min-width: 1200px)">
                <source data-srcset="{{ asset('/img/headers/brands-lg.jpg') }}" media="(min-width: 992px)">
                <source data-srcset="{{ asset('/img/headers/brands-md.jpg') }}" media="(min-width: 576px)">
                <source data-srcset="{{ asset('/img/headers/brands-sm.jpg') }}" media="(max-width: 576px)">
                <img class="lozad img-fluid section--hero-img lazy-fade"
                     srcset="{{ asset('/img/headers/brands-xl.jpg') }}" alt="{{ trans('pages.brands') }}"
                     data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ asset('/img/headers/brands-xl.jpg') }}"
                         alt="{{ trans('pages.brands') }}">
                </noscript>
            </picture>
        </figure>

    </div>
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h1 class="h2"><?php
                                   echo $brand->name; ?></h1>
                    <hr>
                    @if( count($products) == 0 )
                        <span class="">{{ trans('pages.no_products') }}</span>
                    @else
                        <ul id="grid3_borders"
                            class="list-unstyled row justify-content-start assets-row main-row-prod main-row--grid">
                            @foreach($products as $product)
                                @if($product->translateOrDefault(app()->getLocale()))
                                    <li class="col-12 col-md-6 col-lg-4 d-flex align-items-center list-group-item py-3">
                                        <a href="{{ route('product_brand.'.app()->getLocale(),[ 'brand_slug' => $brand->slug, 'prod_slug' => $product->translateOrDefault(app()->getLocale())->slug ]) }}"
                                           class="d-flex flex-column w-100 h-100">
                                            @if($product->getMainPhotoAttribute() !== null)
                                                <figure class="mx-auto">
                                                    <img
                                                            srcset="{{ $product->getMainPhotoAttribute()->getUrl() }}"
                                                            alt="{{ $product->name }}"
                                                            title="{{ $product->name }}"
                                                            class="img-hover lozad img-fluid lazy-fade">
                                                </figure>
                                            @else
                                                <div class="product_image_default">No image</div>
                                            @endif
                                            <p class="h5 assets-title row-icons--desc mt-4">{{ $product->name }}</p>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="col-12 col-md-4 pb-3">
                    <h2 class="h3">{{ trans('menu.brands') }}</h2>
                    <ul class="list-group">
                        @foreach ($brands as $brnd)
                            @if($brnd->cnt >0)
                                <li class="list-group-item {{ $brand->id == $brnd->id ? 'active' : '' }}">
                                    <a href="{{ route('brand.'.app()->getLocale(), ['slug' => $brnd->slug]) }}"
                                       class="d-flex justify-content-between align-items-center">
                                        {{ $brnd->name }} <span
                                                class="badge badge-primary badge-pill">{{ $brnd->cnt }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
@endsection
