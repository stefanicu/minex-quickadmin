@extends('layouts.frontend')
@section('content')
    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('menu.home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ trans('pages.search') }}</li>
            </ol>
        </nav>

        <figure class="d-flex align-items-center section--figure">
            <picture data-alt="Branduri">
                <source data-srcset="{{ asset('/img/headers/aplicatie-xl.jpg') }}" media="(min-width: 1200px)">
                <source data-srcset="{{ asset('/img/headers/aplicatie-lg.jpg') }}" media="(min-width: 992px)">
                <source data-srcset="{{ asset('/img/headers/aplicatie-md.jpg') }}" media="(min-width: 576px)">
                <source data-srcset="{{ asset('/img/headers/aplicatie-sm.jpg') }}" media="(max-width: 576px)">
                <img class="lozad img-fluid section--hero-img lazy-fade"
                     srcset="{{ asset('/img/headers/aplicatie-xl.jpg') }}" alt="{{ trans('pages.product') }}"
                     data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ asset('/img/headers/aplicatie-xl.jpg') }}"
                         alt="{{ trans('pages.product') }}">
                </noscript>
            </picture>
        </figure>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8">
                <h1 class="h2"><small>{{ trans('pages.search_for') }}: </small>{{ $search }}</h1>
                <hr>
                @if(strlen($search)<3)
                    <h5>{{ trans('pages.search_error') }}</h5>
                @elseif($products->count() + $blogs->count() === 0)
                    <h5>{{ trans('pages.search_not_found') }}</h5>
                @else
                    @if($products->count()>0)
                        <h2>{{ trans('pages.products') }}</h2>
                        <ul id="grid3_borders"
                            class='list-unstyled row justify-content-start assets-row main-row-prod main-row--grid'>
                            @foreach($products as $product)
                                <li class="col-12 col-md-6 col-lg-4 d-flex align-items-center list-group-item py-3">
                                    <a href="{{ url('') }}/{{ trans('pages_slugs.product') }}/{{ $product->slug }}"
                                       class="d-flex flex-column">
                                        @if($product->getMainPhotoAttribute())
                                            <figure class="mx-auto">
                                                <img srcset="{{ $product->getMainPhotoAttribute()->getUrl() }}"
                                                     class="mx-auto lozad img-fluid"
                                                     alt="{{ $product->name }}">
                                            </figure>
                                        @endif
                                        <p class="h5 assets-title row-icons--desc mt-4">{{ $product->name }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if($blogs->count()>0)
                        <h2>{{ trans('pages.articles') }}</h2>
                        <ul id="grid3_borders"
                            class='list-unstyled row justify-content-start assets-row main-row-prod main-row--grid'>
                            @foreach($blogs as $blog)
                                <li class="col-12 col-md-6 col-lg-4 d-flex align-items-center list-group-item py-3">
                                    <a href="{{ url('') }}/{{ trans('pages_slugs.blog') }}/{{ $blog->slug }}"
                                       class="d-flex flex-column">
                                        @if($blog->getImageAttribute() !== null && $blog->getImageAttribute()->count()>0)
                                            <img srcset="{{ $blog->getImageAttribute()->getUrl() }}"
                                                 alt="{{ $blog->name }}"
                                                 class="mx-auto lozad img-fluid">
                                        @else
                                            <div class="blog_slider_image_default">No image</div>
                                        @endif
                                        <p class="h5 assets-title row-icons--desc mt-4">{{ $blog->name }}</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
@endsection
