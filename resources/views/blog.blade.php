@extends('layouts.frontend')
@section('content')
    
    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('pages.home') }}</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('blogs.'.app()->getLocale()) }}">{{ trans('pages.blog') }}</a>
                </li>
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
            <div class="col-12 col-lg-8 pb-4 mb-4">
                <div class="d-flex justify-content-between">
                    <h1 class="h2">{{ $blog->name }}<br>
                        <small class="data">{{ Carbon\Carbon::parse($blog->created_at)->format('d.m.Y') }}</small></h1>
                    @if(auth()->check())
                        <a class="position-absolute mr-2" href="{{ url('').'/admin/blogs/'.$blog->id.'/edit' }}" target="_blank">Edit</a>
                    @endif
                </div>

                @if($blog->getImageAttribute() !== null && $blog->getImageAttribute()->count()>0)
                    <img srcset="{{ $blog->getImageAttribute()->getUrl() }}" alt="{{ $blog->name }}" class="img-fluid">
                @endif
                <hr>
                {!! $blog->content !!}

                @if(auth()->check())
                    <a class="position-absolute mr-2" href="{{ url('').'/admin/blogs/'.$blog->id.'/edit' }}" target="_blank">Edit</a>
                @endif
            </div>
            <div class="d-none d-lg-block col-md-4 pb-4 mb-4">
                <h2 class="h3">{{ trans('pages.articles') }}</h2>
                <ul class="list-group">
                    @foreach ($blogs as $blg)
                        <li class="list-group-item flex-row {{ $blog->id === $blg->id ? 'active' : '' }}">
                            <div class="d-flex w-100 justify-content-between">
                                @if($blg->getImageAttribute() !== null && $blg->getImageAttribute()->count()>0)
                                    <div class="blog_image">
                                        <img srcset="{{ $blg->getImageAttribute()->getUrl('thumb') }}" alt="{{ $blg->name }}" class="lozad">
                                    </div>
                                @endif
                                <a href="{{ route('blog.'.app()->getLocale(), ['slug' => $blg->slug, 'page' => request()->get('page')]) }}" class="flex m-auto">
                                    {{ $blg->name }}
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script src="{{ asset('/js/bxSlider/blog_setup.js') }}"></script>
@endsection
