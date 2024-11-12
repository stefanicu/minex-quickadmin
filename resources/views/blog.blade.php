@extends('layouts.frontend')
@section('content')
{{--    @dd($blog, $blogs)--}}


    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('menu.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ url('') }}/{{ trans('pages_slugs.blog') }}/">{{ trans('pages.blog') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $blog->name }}</li>
            </ol>
        </nav>

        <figure class="d-flex align-items-center section--figure">
            <picture data-alt="Branduri">
                <source data-srcset="{{ asset('/img/headers/aplicatie-xl.jpg') }}" media="(min-width: 1200px)">
                <source data-srcset="{{ asset('/img/headers/aplicatie-lg.jpg') }}" media="(min-width: 992px)">
                <source data-srcset="{{ asset('/img/headers/aplicatie-md.jpg') }}" media="(min-width: 576px)">
                <source data-srcset="{{ asset('/img/headers/aplicatie-sm.jpg') }}" media="(max-width: 576px)">
                <img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ asset('/img/headers/aplicatie-xl.jpg') }}" alt="{{ trans('pages.references') }}" data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ asset('/img/headers/aplicatie-xl.jpg') }}" alt="{{ trans('pages.references') }}">
                </noscript>
            </picture>
        </figure>

    </div>

    <div class="container content">
        <div class="row">
            <div class="col-12 col-md-8">
                <h1 class="h2">{{ $blog->name }}<br> <small class="data">{{ Carbon\Carbon::parse($blog->created_at)->format('d.m.Y') }}</small></h1>

                @if($blog->getImageAttribute() !== null && $blog->getImageAttribute()->count()>0)
                    <img
                        srcset="{{ $blog->getImageAttribute()->getUrl() }}"
                        alt="{{ $blog->name }}"
                        class="img-fluid">
                @else
                    <div class="blog_image_default">No image</div>
                @endif
                <hr>
                {!! $blog->content !!}

                <hr/>

            </div>
        </div>
    </div>

<div class="container p-4 sm:p-4">
    <div class="bxslider-related mx-auto">
        @foreach ($blogs as $blg)
            <div class="d-flex slider-image-container">
                <a href="{{ url('') }}/{{ trans('pages_slugs.blog') }}/{{ $blg->slug }}" class="blogim position-relative">
                    @if($blg->getImageAttribute() !== null && $blg->getImageAttribute()->count()>0)
                        <img
                            srcset="{{ $blg->getImageAttribute()->getUrl() }}"
                            alt="{{ $blg->name }}"
                            class="mx-auto lozad">
                    @else
                        <div class="blog_slider_image_default">No image</div>
                    @endif
                    <div class="card-img-overlay blogim_over">
                        <p class="assets-title row-icons--desc position-absolute">{{ $blg->name }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

@endsection
@section('scripts')
    @parent
    <script>
        $(document).ready(function(){$(function(){$('.bxslider').bxSlider({mode:'fade',slideWidth:400});});
            $(function(){$('.bxslider-related').bxSlider({minSlides:1,maxSlides:3,slideWidth:360,slideMargin:5,pager:false});});});
        $(function(){$('.bxslider-img').bxSlider({mode:'fade',slideWidth:1110,pager:false,controls:true});});
    </script>
@endsection
