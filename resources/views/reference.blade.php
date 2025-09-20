@extends('layouts.frontend')
@section('content')
    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('').'/'.app()->getLocale() }}">{{ trans('pages.home') }}</a>
                </li>
                <li class="breadcrumb-item"><a
                            href="{{ route('references.'.app()->getLocale()) }}">{{ trans('pages.references') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $reference->name }}</li>
            </ol>
        </nav>

        <figure class="d-flex align-items-center section--figure">
            <picture data-alt="Branduri">
                <source data-srcset="{{ asset('/img/headers/aplicatie-xl.jpg') }}" media="(min-width: 1200px)">
                <source data-srcset="{{ asset('/img/headers/aplicatie-lg.jpg') }}" media="(min-width: 992px)">
                <source data-srcset="{{ asset('/img/headers/aplicatie-md.jpg') }}" media="(min-width: 576px)">
                <source data-srcset="{{ asset('/img/headers/aplicatie-sm.jpg') }}" media="(max-width: 576px)">
                <img class="lozad img-fluid section--hero-img lazy-fade"
                     srcset="{{ asset('/img/headers/aplicatie-xl.jpg') }}" alt="{{ trans('pages.references') }}"
                     data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ asset('/img/headers/aplicatie-xl.jpg') }}"
                         alt="{{ trans('pages.references') }}">
                </noscript>
            </picture>
        </figure>

    </div>

    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="d-flex justify-content-between">
                    <h1 class="h2">{{ $reference->name }}</h1>

                    @if(auth()->check())
                        <a class="position-absolute mr-2" href="{{ url('').'/admin/references/' . $reference->id . '/edit?lang='.app()->getLocale() }}" target="_blank">Edit</a>
                    @endif
                </div>
                <hr>

                {!! $reference->content !!}

                @if(auth()->check())
                    <a class="position-absolute mr-2" href="{{ url('').'/admin/references/' . $reference->id . '/edit?lang='.app()->getLocale() }}" target="_blank">Edit</a>
                @endif
            </div>
            <div class="col-12 col-md-4">
                <div class="h3 sidebar-title">{{ trans('pages.other_references') }}</div>
                <div class="list-group references--other">
                    @foreach($references as $ref)
                        <a href="{{ route('reference.'.app()->getLocale(), ['slug' => $ref->slug]) }}"
                           class="list-group-item d-flex justify-content-between align-items-center">{{ $ref->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="container">
        <div class="row news mb-4">
            @if($reference->getPhotoSquareAttribute() !== null && $reference->getPhotoSquareAttribute()->count()>0)
                @foreach($reference->getPhotoSquareAttribute() as $image)
                    @if($loop->iteration == 1 && $reference->getPhotoWideAttribute() !== null && $reference->getPhotoWideAttribute()->count()>0)
                        <article class="news--item news--item__big">
                            <img
                                    srcset="{{ $reference->getPhotoWideAttribute()->getUrl() }}"
                                    alt="{{ $reference->name }} image wide"
                                    class="lozad lazy-fade">
                        </article>
                    @endif
                    <article class="news--item news--item__small">
                        <img
                                srcset="{{ $image->getUrl() }}"
                                alt="{{ $reference->name }} image {{ $loop->iteration }}"
                                class="lozad lazy-fade">
                    </article>
                @endforeach
            @endif
        </div>
        <div class="row">
            <div class="col-12 col-md-8">
                @if($products->count()>0)
                    <h4>{{ trans('pages.products') }}</h4>
                    <div class="bxslider-related mx-auto">
                        @foreach ($products as $product)
                            <div>
                                <a href="{{ route('product.'.app()->getLocale(),[
                                        'app_slug' => $product->application_slug,
                                        'cat_slug' => $product->category_slug,
                                        'prod_slug' => $product->slug
                                    ]) }}"
                                   class="bwWrapper">
                                    @if(!empty($product->getMainPhotoAttribute()))
                                        <img
                                                srcset="{{ $product->getMainPhotoAttribute()->getUrl() }}"
                                                alt="{{ $product->name }}"
                                                class="mx-auto img-fluid img-hover">
                                    @else
                                        <div class="product_default_image">No image</div>
                                    @endif

                                    <p class="h5 assets-title row-icons--desc mt-4">{{ $product->name }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script src="{{ asset('/js/bxSlider/reference_setup.js') }}"></script>
@endsection
