@extends('layouts.frontend')
@section('content')
    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('pages.home') }}</a></li>
                <li class="breadcrumb-item active"
                    aria-current="page">{{ $application->name ?? trans('pages.all_categories') }}
                </li>
            </ol>
        </nav>

        @php
            if($application->getImageAttribute()){
                $cover_image_url = $application->getImageAttribute()->getUrl();
            }else{
                $cover_image_url = asset('/img/headers/aplicatie-xl.jpg');
            }
        @endphp

        <figure class="d-flex align-items-center section--figure">
            <picture data-alt="Category">
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-xl.jpg') }}" media="(min-width: 1200px)">--}}
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-lg.jpg') }}" media="(min-width: 992px)">--}}
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-md.jpg') }}" media="(min-width: 576px)">--}}
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-sm.jpg') }}" media="(max-width: 576px)">--}}
                <img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ $cover_image_url }}"
                     alt="{{ trans('pages.application') }}" data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ $cover_image_url }}" alt="{{ trans('pages.application') }}">
                </noscript>
            </picture>
        </figure>
    </div>

    <section class="new">
        <div class="container">
            <div class="col-xs-12">
                <h1 class="h2">
                    {{ $application->name }}
                    @if($application)
                        <small class="catapp font-weight-lighter">{{ trans('pages.categories') }}</small>
                    @endif
                </h1>
                <hr/>
                <p>{{ trans('pages.chose_category') }}</p>
                <ul class="list-unstyled row justify-content-start full-row-prod assets-row grid">
                    @foreach($categories as $category)
                        <li class="col-6 col-sm-4 col-md-3">

                            <a href="{{ route('products.'.app()->getLocale(), ['app_slug' => $application->slug ?? null,'cat_slug' => $category->slug ?? null]) }}"
                               class="d-flex flex-column text-center">

                                @if($category->product_main_image && $category->product_main_image->getMainPhotoAttribute() !== null)
                                    <figure class="category_image">
                                        <img
                                                srcset="{{ $category->product_main_image->getMainPhotoAttribute()->getUrl() }}"
                                                alt="{{ $category->name }}"
                                                title="{{ $category->name }}"
                                                class="img-hover lozad img-fluid lazy-fade">
                                    </figure>
                                @else
                                    <div class="category_image_default">No image</div>
                                @endif
                                <p class="h5 assets-title row-icons--desc px-2 mt-0">{{ $category->name }}</p>
                            </a>
                        </li>

                    @endforeach
                </ul>
            </div>
        </div>
    </section>

@endsection
@section('scripts')
    @parent
@endsection
