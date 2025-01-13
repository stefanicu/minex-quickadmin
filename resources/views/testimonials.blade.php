@extends('layouts.frontend')
@section('content')

    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('pages.home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ trans('pages.testimonials') }}</li>
            </ol>
        </nav>

        <figure class="d-flex align-items-center section--figure">
            <picture data-alt="Branduri">
                <source data-srcset="{{ asset('/img/headers/testimoniale-xl.jpg') }}" media="(min-width: 1200px)">
                <source data-srcset="{{ asset('/img/headers/testimoniale-lg.jpg') }}" media="(min-width: 992px)">
                <source data-srcset="{{ asset('/img/headers/testimoniale-md.jpg') }}" media="(min-width: 576px)">
                <source data-srcset="{{ asset('/img/headers/testimoniale-sm.jpg') }}" media="(max-width: 576px)">
                <img class="lozad img-fluid section--hero-img lazy-fade"
                     srcset="{{ asset('/img/headers/testimoniale-xl.jpg') }}" alt="{{ trans('pages.testimonials') }}"
                     data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ asset('/img/headers/testimoniale-xl.jpg') }}"
                         alt="{{ trans('pages.testimonials') }}">
                </noscript>
            </picture>
        </figure>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="h2">{{ trans('pages.testimonials') }}</h1>
                <p>{{ trans('pages.testimonial_question') }}</p>
                <hr>
                <ul class="list-unstyled row justify-content-md-center main-row--grid">
                    @foreach($testimonials as $testimonial)
                        @if($testimonial->getLogoAttribute() !== null)
                            <li class="col-6 col-md-4 text-center">
                                <a href="#tab{{ $testimonial->id }}">
                                    <figure class="">
                                        <img
                                                srcset="{{ $testimonial->getLogoAttribute()->getUrl() }}"
                                                alt="{{ $testimonial->company }}"
                                                class="img-hover lozad img-fluid lazy-fade">
                                    </figure>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>


    <section class="container">
        <div class="row">

            @foreach($testimonials as $testimonial)
                <div class="col-12 py-4"><a id="tab{{ $testimonial->id }}"></a>
                    <blockquote class="blockquote blockquote__primary">
                        {!! $testimonial->content !!}<br/>
                        @if($testimonial->name)
                            <p class="font-italic">
                                {{ $testimonial->name }}<br/>
                                {{ $testimonial->job }}
                            </p>
                        @endif
                        <strong>{{ $testimonial->company }}</strong>
                    </blockquote>

                    @if(auth()->check())
                        <a class="position-absolute" style="left: 45px; bottom: 10px"
                           href="{{ url('').'/admin/testimonials/'.$testimonial->id.'/edit' }}"
                           target="_blank">Edit</a>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

@endsection
@section('scripts')
    @parent
@endsection
