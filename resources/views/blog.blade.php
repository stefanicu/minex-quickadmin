@extends('layouts.frontend')
@section('content')
    {{--    @dd($blog, $blogs)--}}


    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('menu.home') }}</a></li>
                <li class="breadcrumb-item"><a
                            href="{{ route('blogs.'.app()->getLocale()) }}">{{ trans('pages.blog') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $blog->name }}</li>
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

    <div class="container content">
        <div class="row">
            <div class="col-12 col-lg-8 pb-4 mb-4">
                <h1 class="h2">{{ $blog->name }}<br> <small
                            class="data">{{ Carbon\Carbon::parse($blog->created_at)->format('d.m.Y') }}</small></h1>

                @if($blog->getImageAttribute() !== null && $blog->getImageAttribute()->count()>0)
                    <img
                            srcset="{{ $blog->getImageAttribute()->getUrl() }}"
                            alt="{{ $blog->name }}"
                            class="img-fluid">
                @endif
                <hr>
                {!! $blog->content !!}
            </div>
            <div class="d-none d-lg-block col-md-4 pb-4 mb-4">
                <h2 class="h3">{{ trans('pages.articles') }}</h2>
                <ul class="list-group">
                    @foreach ($blogs10 as $bg)
                        <li class="list-group-item flex-row {{ $blog->id === $bg->id ? 'active' : '' }}">
                            <div class="d-flex w-100 justify-content-between">
                                @if($bg->getImageAttribute() !== null && $bg->getImageAttribute()->count()>0)
                                    <div class="blog_image">
                                        <img
                                                srcset="{{ $bg->getImageAttribute()->getUrl('thumb') }}"
                                                alt="{{ $bg->name }}"
                                                class="lozad">
                                    </div>
                                @endif
                                <a href="{{ route('blog.'.app()->getLocale(), ['slug' => $bg->slug, 'page' => request()->get('page')]) }}"
                                   class="flex m-auto">
                                    {{ $bg->name }}
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
                {!! $blogs10->onEachSide(3)->links() !!}
            </div>
        </div>
    </div>

    <div class="container d-block d-lg-none">
        <hr/>
        <div class="bxslider-related mx-auto pt-4">
            @foreach ($blogs as $blg)
                <div class="d-flex slider-image-container">
                    <a href="{{ route('blog.'.app()->getLocale(), ['slug' => $blg->slug ?? null, 'page' => request()->get('page')]) }}"
                       class="blogim position-relative">
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
    <script src="{{ asset('/js/bxSlider/blog_setup.js') }}"></script>
@endsection
