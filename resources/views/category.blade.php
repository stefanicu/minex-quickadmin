@extends('layouts.frontend')
@section('content')
    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('pages.home') }}</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('pages.'.app()->getLocale(), ['slug' =>$application->slug]) }}">{{ $application->name }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>

        @php
            if($category->getCoverPhotoAttribute()){
                $cover_image_url = $category->getCoverPhotoAttribute()->getUrl();
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
                     alt="{{ trans('pages.category') }}" data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ $cover_image_url }}" alt="{{ trans('pages.category') }}">
                </noscript>
            </picture>
        </figure>
    </div>

    <section class="new">


        <div class="container">
            <div class="row">
                @if($products->count() === 0)
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <h1 class="h2">
                                {{ $category->name }}
                                <small class="catapp font-weight-lighter">{{ trans('pages.category') }}</small>
                            </h1>
                            @if(auth()->check())
                                <a class="position-absolute mr-2"
                                   href="{{ url('').'/admin/categories/'.$category->id.'/edit' }}"
                                   target="_blank">Edit</a>
                            @endif
                        </div>
                        <hr>
                        <div class="py-4">
                            {!! $category->content !!}
                        </div>
                        <p>{{ __('pages.no_products') }}</p>

                    </div>
                @else
                    <div class="col-12 col-md-8">
                        <div class="d-flex justify-content-between">
                            <h1 class="h2">
                                {{ $category->name }}
                                <small class="catapp font-weight-lighter">{{ trans('pages.category') }}</small>
                            </h1>
                            @if(auth()->check())
                                <a class="position-absolute mr-2"
                                   href="{{ url('').'/admin/categories/'.$category->id.'/edit' }}"
                                   target="_blank">Edit</a>
                            @endif
                        </div>
                        <hr>
                        <div class="py-4">
                            {!! $category->content !!}
                        </div>
                        <ul id="grid3_borders" class="list-unstyled row justify-content-start assets-row main-row-prod main-row--grid">
                            @foreach($products as $product)
                                @if($product->translateOrDefault(app()->getLocale()))
                                    <li class="col-12 col-md-6 col-lg-4 d-flex align-items-center list-group-item py-3">
                                        <a href="{{ route('product.'.app()->getLocale(),[ 'app_slug'  => $application->slug,'cat_slug' => $category->slug,'prod_slug' => $product->translateOrDefault(app()->getLocale())->slug ]) }}"
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

                        @if($category->call_to_action)
                            <div class="w-100 py-4">
                                <p class="text-center py-2 px-4 my-4">
                                    <a href="{{ url('') . '/' . app()->getLocale() . '/' . $category->call_to_action_link }}" target="_blank" class="btn btn-primary btn-lg p-4 mx-auto">{{ $category->call_to_action }}</a>
                                </p>
                            </div>
                        @endif
                    </div>
                    <div id="categories_list" class="col-12 col-md-4">
                        <h2 class="h3">{{ trans('pages.product_categories') }}</h2>
                        <ul class="list-group">
                            @foreach($categories as $cat)
                                <li class="list-group-item {{ $category->id == $cat->id ? 'active' : '' }}">
                                    <a href="{{ route('category.'.app()->getLocale(), ['app_slug' => $application->slug,'cat_slug' => $cat->slug]) }}"
                                       class="d-flex justify-content-between align-items-center category_count">
                                        {{ $cat->name }}<span
                                                class="badge badge-primary badge-pill">{{ $cat->products_count }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            @if(auth()->check())
                <a class="position-absolute mr-2" href="{{ url('').'/admin/categories/'.$category->id.'/edit' }}"
                   target="_blank">Edit</a>
            @endif
            <br/>
            <br/>
        </div>
    </section>

@endsection
@section('scripts')
    @parent
@endsection
