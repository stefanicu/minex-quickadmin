@extends('layouts.frontend')
@section('content')

    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('').'/'.app()->getLocale() }}">{{ trans('pages.home') }}</a>
                </li>
                <li class="breadcrumb-item">{{ trans('pages.blog') }}</li>
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
            <div class="col-12 pb-4 mb-4">
                <h1 class="h2">
                    {{ trans('pages.blog') }}
                    @if(request()->has('page') && request('page') > 1)
                        &nbsp;&nbsp;&nbsp;<small class="catapp font-weight-lighter">page {{ request('page') }}</small>
                    @endif
                </h1>
            </div>
        </div>
        <div class="row blog">
            @foreach ($blogs as $blg)
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-image d-flex align-items-center justify-content-center overflow-hidden">
                            <a href="{{ route('blog.'.app()->getLocale(), ['slug' => $blg->slug ?? null]) }}"
                               class="d-flex align-items-center w-100 h-100">
                                @if($blg->getImageAttribute() !== null && $blg->getImageAttribute()->count()>0)
                                    <img srcset="{{ $blg->getImageAttribute()->getUrl('preview') }}"
                                         data-loaded="true"
                                         alt="{{ $blg->name }}"
                                         class="mx-auto card-img lozad lazy-fade img-cover">
                                @else
                                    <div class="blog_slider_image_default">No image</div>
                                @endif
                            </a>
                        </div>

                        <div class="card-body">
                            <a href="{{ route('blog.'.app()->getLocale(), ['slug' => $blg->slug ?? null]) }}"
                               class="card-title">{{ $blg->name }}</a>
                        </div>
                        <div class="card-footer text-muted d-flex justify-content-between bg-transparent border-top-0">
                            <div class="views">{{ Carbon\Carbon::parse($blg->created_at)->format('d.m.Y') }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="mx-auto my-4">
                {{ $blogs->links() }}
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script src="{{ asset('/js/bxSlider/blog_setup.js') }}"></script>
@endsection
