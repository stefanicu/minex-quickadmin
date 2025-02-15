@extends('layouts.frontend')
@section('content')
    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('pages.home') }}</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('brands.'.app()->getLocale()) }}">{{ trans('pages.brands') }}</a></li>
                @if($brand)
                    <li class="breadcrumb-item">
                        <a href="{{ route('brand.'.app()->getLocale(), ['slug' => $brand->slug]) }}">{{ $brand->name }}</a>
                    </li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>


        @php
            $cover_image_url = asset('/img/headers/aplicatie-xl.jpg');
        @endphp

        <figure class="d-flex align-items-center section--figure">
            <picture data-alt="Branduri">
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-xl.jpg') }}" media="(min-width: 1200px)">--}}
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-lg.jpg') }}" media="(min-width: 992px)">--}}
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-md.jpg') }}" media="(min-width: 576px)">--}}
                {{--                <source data-srcset="{{ asset('/img/headers/aplicatie-sm.jpg') }}" media="(max-width: 576px)">--}}
                <img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ $cover_image_url }}" alt="{{ trans('pages.product') }}" data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ $cover_image_url }}" alt="{{ trans('pages.product') }}">
                </noscript>
            </picture>
        </figure>
    </div>
    <div class="container pb-4">
        <div class="row">
            <div class="col-12 col-md-8">

                <div class="d-flex justify-content-between">
                    <h1 class="h2">{{ $product->name }}</h1>

                    @if(auth()->check())
                        <a class="position-absolute mr-2" href="{{ url('').'/admin/products/' . $product->id . '/edit' }}" target="_blank">Edit</a>
                    @endif
                </div>

                <hr>

                @if(!$brand || $brand->online === 1)
                    {!! $product->description !!}
                @else
                    {!! $brandOfflineMessage !!}
                @endif
            </div>
            <div class="col-12 col-md-4">
                @if($product->getMainPhotoAttribute())
                    <div class="mx-auto">
                        <div class="bxslider-img mx-auto">
                            <div>
                                <figure class="mx-auto">
                                    <img srcset="{{ $product->getMainPhotoAttribute()->getUrl() }}"
                                         class="mx-auto lozad img-fluid" alt="No Image">
                                </figure>
                            </div>

                            @if($product->getPhotoAttribute() !== null && $product->getPhotoAttribute()->count()>0)
                                @foreach ($product->getPhotoAttribute()->all() as $image)
                                    <div>
                                        <figure class="mx-auto">
                                            <img srcset="{{ $image->getUrl() }}" class="mx-auto lozad img-fluid"
                                                 alt="{{ $product->name }} {{ $loop->iteration }}">
                                        </figure>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="container pt-4">
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="container mt-1 mb-5">
                    <!-- Tab Buttons -->
                    <div class="nav nav-tabs" role="tablist">
                        @if($product->specifications)
                            <button class="navprod-item navprod-link active"
                                    id="{{ trans('pages.specifications') }}-btn"
                                    type="button"
                                    role="tab" aria-controls="{{ trans('pages.specifications') }}" aria-selected="true">
                                {{ trans('pages.specifications') }}
                            </button>
                        @endif
                        @if($product->advantages)
                            <button class="navprod-item navprod-link" id="{{ trans('pages.advantages') }}-btn"
                                    type="button"
                                    role="tab"
                                    aria-controls="{{ trans('pages.advantages') }}"
                                    aria-selected="false">
                                {{ trans('pages.advantages') }}
                            </button>
                        @endif
                        @if($product->usages)
                            <button class="navprod-item navprod-link" id="{{ trans('pages.usages') }}-btn" type="button"
                                    role="tab"
                                    aria-controls="{{ trans('pages.usages') }}"
                                    aria-selected="false">
                                {{ trans('pages.usages') }}
                            </button>
                        @endif
                        @if($product->accessories)
                            <button class="navprod-item navprod-link" id="{{ trans('pages.accessories') }}-btn"
                                    type="button"
                                    role="tab"
                                    aria-controls="{{ trans('pages.accessories') }}"
                                    aria-selected="false">
                                {{ trans('pages.accessories') }}
                            </button>
                        @endif
                        @if($references && $references->count()>0)
                            <button class="navprod-item navprod-link" id="{{ trans('pages.references') }}-btn"
                                    type="button"
                                    role="tab"
                                    aria-controls="{{ trans('pages.references') }}"
                                    aria-selected="false">
                                {{ trans('pages.references') }}
                            </button>
                        @endif
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content mt-3" id="navprod-tabContent">
                        @if($product->specifications)
                            <div class="tab-pane fade show active" id="{{ trans('pages.specifications') }}"
                                 role="tabpanel" aria-labelledby="{{ trans('pages.specifications') }}-btn">
                                {!! $product->specifications !!}
                            </div>
                        @endif
                        @if($product->advantages)
                            <div class="tab-pane fade" id="{{ trans('pages.advantages') }}" role="tabpanel" aria-labelledby="{{ trans('pages.advantages') }}-btn">
                                {!! $product->advantages !!}
                            </div>
                        @endif
                        @if($product->usages)
                            <div class="tab-pane fade" id="{{ trans('pages.usages') }}" role="tabpanel" aria-labelledby="{{ trans('pages.usages') }}-btn">
                                {!! $product->usages !!}
                            </div>
                        @endif
                        @if($product->accessories)
                            <div class="tab-pane fade" id="{{ trans('pages.accessories') }}" role="tabpanel" aria-labelledby="{{ trans('pages.accessories') }}-btn">
                                {!! $product->accessories !!}
                            </div>
                        @endif
                        @if($references && $references->filter(function($reference) {return $reference->hasTranslation(app()->getLocale());})->count() > 0)
                            <div class="tab-pane fade" id="{{ trans('pages.references') }}" role="tabpanel" aria-labelledby="{{ trans('pages.references') }}-btn">
                                <ul>
                                    @foreach($references as $reference)
                                        <li>
                                            <a href="{{ route('reference.'.app()->getLocale(), ['slug' => $reference->slug]) }}">{{ $reference->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    @if($files)
                        <ul>
                            @foreach($files as $file)
                                <li><a href='{{ url('') }}/uploads/files/{{ $file->name }}'>{{ $file->title }}</a></li>
                            @endforeach
                        </ul>
                    @endif

                    @if(auth()->check())
                        <a class="position-absolute mr-2" href="{{ url('').'/admin/products/' . $product->id . '/edit' }}" target="_blank">Edit</a>
                    @endif
                </div>

                <p class="text-center">{{ trans('pages.ask_our_experts') }}</p>
                <div class="panel panel-default">
                    <a href="tel:{{ trans('pages.phone') }}" class="btn btn-primary" style="width:100%" id="form">
                        {{ trans('pages.call_us') }} {{ trans('pages.phone') }}
                    </a>
                </div>


                @if(session('success'))
                    <div id="contact" class="multumim-center">{{ trans('pages.contact_thank_you') }}</div>
                @else
                    <div class="card my-4">
                        <div class="card-header">
                            <h4 class="panel-title">{{ trans('pages.contact_form') }}</h4>
                        </div>
                        <div class="card-body">
                            <form class="px-0 px-md-4" id="detaliiprodus" autocomplete="off" action="{{ route('contact.post.'.app()->getLocale()) }}" method="post">
                                @csrf
                                @include('.partials.form')

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12">
                                        <div class="form-group captcha-error">
                                            <input type="hidden" id="product" name="product" value="{{ $product->id }}">
                                            <input type="hidden" id="district" name="district" value="">
                                            <input type="hidden" id="ip" name="ip" value="{{ request()->ip() }}">

                                            @if(app()->isProduction())
                                                <input type="hidden" class="hiddenRecaptcha form-control" name="hiddenRecaptcha" id="hiddenRecaptcha">
                                                <div class="g-recaptcha" data-sitekey="6Lc-mScTAAAAAIoo8LHTWRuJhPUXdDFJoGV4ptBg"></div>
                                            @endif

                                            @foreach($errors as $error)
                                                {{$error}}
                                            @endforeach

                                            @if (session('error'))
                                                <div class="alert alert-danger">{{ session('error') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">{{ trans('pages.send_form') }}</button>
                            </form>
                        </div>
                    </div>
                @endif


                @if($similar_products && $similar_products->count() > 0)
                    <h4>{{ trans('pages.similar_products') }}</h4>
                    <div class="bxslider-related mx-auto">
                        @foreach($similar_products as $similar_product)
                            <div>
                                <a href="{{ route('product_brand.'.app()->getLocale(), ['brand_slug' => $brand->slug, 'prod_slug' => $similar_product->slug]) }}" class="bwWrapper">
                                    @if($similar_product->getMainPhotoAttribute())
                                        <img src="{{ $similar_product->getMainPhotoAttribute()->getUrl() }}" alt="{{ $similar_product->name }}" class="mx-auto img-fluid img-hover">
                                    @endif
                                    <p class="h5 assets-title row-icons--desc mt-4">{{ $similar_product->name }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($brands && $brands->count() >0)
                <div class="col-12 col-md-4 pb-3">
                    <h2 class="h3 mt-0">{{ trans('pages.brands') }}</h2>
                    <ul class="list-group">
                        @foreach ($brands as $brnd)
                            @if($brnd->cnt >0)
                                <li class="list-group-item {{ $brand->id == $brnd->id ? 'active' : '' }}">
                                    <a href="{{ route('brand.'.app()->getLocale(), ['slug' => $brnd->slug]) }}" class="d-flex justify-content-between align-items-center">
                                        {{ $brnd->name }}
                                        <span class="badge badge-primary badge-pill">{{ $brnd->cnt }}</span>
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script src="{{ asset('/js/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/js/validate/setprodus.validate.js') }}"></script>
    <script src="{{ asset('/js/countries.js') }}"></script>
    <script src="{{ asset('/js/bxSlider/product_setup.js') }}"></script>
    <script src="{{ asset('/js/tabs.js') }}"></script>
@endsection
