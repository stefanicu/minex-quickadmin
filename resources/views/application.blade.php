@extends('layouts.frontend')
@section('content')

    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('').'/'.app()->getLocale() }}">{{ trans('pages.home') }}</a>
                </li>
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
                <img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ $cover_image_url }}" alt="{{ trans('pages.application') }}" data-loaded="true">
                <noscript>
                    <img class="img-fluid lozad" src="{{ $cover_image_url }}" alt="{{ trans('pages.application') }}">
                </noscript>
            </picture>
            <figcaption class="overlay-text">
                <div class="heroTitle">
                    {{ $application->name }}
                    {{--                    <small class="catapp font-weight-lighter">{{ trans('pages.application') }}</small>--}}
                </div>
            </figcaption>
        </figure>
    </div>

    <section class="new">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">

                    @if(auth()->check())
                        <a class="position-absolute mr-2" href="{{ url('').'/admin/applications/'.$application->id.'/edit?lang='.app()->getLocale() }}" target="_blank">Edit</a>
                    @endif

                    @if($application->title)
                        <h1>{{ $application->title }}</h1>
                        @if( $application->subtitle )
                            <p class="subtitle">{{ $application->subtitle }}</p>
                        @endif
                    @else
                        <h1>{{ $application->name }}</h1>
                        <p class="subtitle">{{ trans('pages.application') }}</p>
                    @endif
                    <hr>

                    <ul class="list-unstyled row justify-content-start full-row-prod assets-row mt-4 grid">
                        @foreach($categories as $category)
                            <li class="col-12 col-sm-6 col-md-4 py-2">
                                <a href="{{ route('category.'.app()->getLocale(), ['app_slug' => $application->slug ?? null,'cat_slug' => $category->slug ?? null]) }}" class="d-flex flex-column text-center pb-2">

                                    @if($category->getCoverPhotoAttribute() !== null)
                                        <figure class="category_image">
                                            <img
                                                    srcset="{{ $category->getCoverPhotoAttribute()->getUrl() }}"
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

                    <div class="py-4">
                        {!! $application->content !!}
                    </div>
                </div>

                @if($application->call_to_action)
                    <div class="w-100 py-4">
                        <p class="text-center p-4 my-4">
                            <a href="{{ url('') . '/' . app()->getLocale() . '/' . $application->call_to_action_link }}" target="_blank" class="btn btn-primary btn-lg py-2 px-4 mx-auto">{{ $application->call_to_action }}</a>
                        </p>
                    </div>
                @endif
            </div>

            @if(auth()->check())
                <a class="position-absolute mr-2" href="{{ url('').'/admin/applications/'.$application->id.'/edit?lang='.app()->getLocale() }}" target="_blank">Edit</a>
                <br>
                <br>
            @endif
        </div>
    </section>

@endsection
@section('scripts')
    @parent
@endsection
