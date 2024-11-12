@extends('layouts.frontend')
@section('content')

    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('menu.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ url('') }}/{{ trans('pages_slugs.brands') }}/">{{ trans('pages.brands') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$brand->name;?></li>
            </ol>
        </nav>

        <figure class="d-flex align-items-center section--figure">
            <picture data-alt="Branduri">
                <source data-srcset="{{ asset('/img/headers/brands-xl.jpg') }}" media="(min-width: 1200px)">
                <source data-srcset="{{ asset('/img/headers/brands-lg.jpg') }}" media="(min-width: 992px)">
                <source data-srcset="{{ asset('/img/headers/brands-md.jpg') }}" media="(min-width: 576px)">
                <source data-srcset="{{ asset('/img/headers/brands-sm.jpg') }}" media="(max-width: 576px)">
                <img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ asset('/img/headers/brands-xl.jpg') }}" alt="{{ trans('pages.brands') }}" data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ asset('/img/headers/brands-xl.jpg') }}" alt="{{ trans('pages.brands') }}">
                </noscript>
            </picture>
        </figure>

    </div>
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8">
                    <h1 class="h2"><?php echo $brand->name; ?></h1>
                    <hr>
                    @if( count($products) == 0 )
                        <span class="">{{ trans('pages.no_products') }}</span>
                    @else
                        <ul class="list-unstyled row justify-content-start assets-row main-row-prod main-row--grid">
                            @foreach ($products as $product)

                                <li class="col-6 col-sm-4">
                                    <a href="{{ url('') }}/{{ trans('pages_slugs.product') }}/{{ $product->slug }}" alt="{{ $product->name }}" class="d-flex flex-column">
                                        <figure class="w-100">
                                            @if($product && $product->getPhotoAttribute()->all() !== null && $product->getPhotoAttribute()->count()>0)
                                                <img
                                                    srcset="{{ $product->getPhotoAttribute()->all()[0]->getUrl() }}"
                                                    alt="{{ $product->name }}"
                                                    title="{{ $product->name }}"
                                                    width="400" height="400"
                                                    class="img-hover lozad img-fluid lazy-fade mx-auto">
                                            @else
                                                <div class="w-100 p-3 my-3 text-center" style="background-color:#eee; height: 200px; color:#777;">NO IMAGE</div>
                                            @endif
                                            <p class="h5 assets-title row-icons--desc mt-4">{{ $product->name }}</p>
                                        </figure>
                                    </a>
                                </li>

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
                                    <a href="{{ url('') }}/{{ trans('pages_slugs.brand') }}/{{ $brnd->slug }}" class="d-flex justify-content-between align-items-center">
                                        {{ $brnd->name }} <span class="badge badge-primary badge-pill">{{ $brnd->cnt }}</span>
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
