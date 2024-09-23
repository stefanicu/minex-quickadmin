@extends('layouts.frontend')
@section('content')

    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('menu.home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ trans('menu.brands') }}</li>
            </ol>
        </nav>

        <figure class="d-flex align-items-center section--figure">
            <picture data-alt="Branduri">
                <source data-srcset="{{ asset('/img/headers/testimoniale-xl.jpg') }}" media="(min-width: 1200px)">
                <source data-srcset="{{ asset('/img/headers/testimoniale-lg.jpg') }}" media="(min-width: 992px)">
                <source data-srcset="{{ asset('/img/headers/testimoniale-md.jpg') }}" media="(min-width: 576px)">
                <source data-srcset="{{ asset('/img/headers/testimoniale-sm.jpg') }}" media="(max-width: 576px)">
                <img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ asset('/img/headers/testimoniale-xl.jpg') }}" alt="Branduri" data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ asset('/img/headers/testimoniale-xl.jpg') }}" alt="Branduri">
                </noscript>
            </picture>
        </figure>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="h2">{{ trans('menu.brands') }}</h1>
                <hr>
                <ul class="list-unstyled row justify-content-md main-row--grid">

                    @foreach($brands as $brand)
                        @php
                            $image_url = '';
                        @endphp
                        @if($brand->getPhotoAttribute())
                            @php
                                $image_url = $brand->getPhotoAttribute()->getUrl()
                            @endphp
                        @endif
                        <li class="col-6 col-md-4 text-center">
                            <a href="{{ url('') }}/{{ trans('pages_slugs.brand') }}/{{ $brand->slug }}">
                                <figure class="brand_image">
                                    <img
                                        srcset="{{ $image_url }}"
                                        alt="{{ $brand->name }}"
                                        title="{{ $brand->name }}"
                                        class="img-hover lozad img-fluid lazy-fade">
                                </figure>
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
    <script src="({{ asset('/js/validate/jquery.validate.min.js') }}))"></script>
    <script src="{{ asset('/js/validate/set.validate.js') }}"></script>
    <script src="<{{ asset('/js/countries.js') }}"></script>
    <script>populateCountries("country", "county");</script>
    <script async defer src="//maps.googleapis.com/maps/api/js?key=AIzaSyCRHi8eiqWm--iQQ-fNTq3AWKev7xCj2RA&callback=initialize"></script>
    <script src="{{ asset('/js/map.js?v=274625') }}"></script>
@endsection
