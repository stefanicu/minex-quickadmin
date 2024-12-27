@extends('layouts.frontend')
@section('content')
    <div class="container-fluid cover p-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/">{{ trans('menu.home') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ trans('pages.references') }}</li>
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
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="h2">{{ trans('pages.references') }}</h1>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @foreach($industries as $industry)
                            <li class="nav-item">
                                <a class="nav-link {{ $loop->iteration==1 ? 'active' : '' }}"
                                   id="tab-a{{ $industry->id }}-tab" data-toggle="tab" href="#ind_{{ $industry->id }}"
                                   role="tab" aria-controls="tab-a{{ $industry->id }}">
                                    {{ $industry->name }}
                                </a>
                            </li>
                        @endforeach

                        <li class="nav-item"><a class="nav-link" id="tab-alte-tab" data-toggle="tab" href="#ind_0"
                                                role="tab" aria-controls="tab-alte">Altele</a></li>
                    </ul>

                    <div class="tab-content py-4" id="myTabContent">


                        @foreach($industries as $industry)
                            <div class="tab-pane py-4 fade {{ $loop->iteration== 1 ? 'show active' : '' }}"
                                 id="ind_{{ $industry->id }}" role="tabpanel"
                                 aria-labelledby="tab{{ $industry->id }}-tab">
                                <ul class="list-unstyled img-grid">
                                    @foreach($references as $reference)
                                        @if($reference->industries_id == $industry->id)
                                            <li class="img-grid--item px-4">
                                                <a href="{{ route('reference.'.app()->getLocale(), ['slug' => $reference->slug]) }}"
                                                   class="text-center d-flex flex-column">
                                                    @if($reference->getPhotoSquareAttribute()->all() !== null && $reference->getPhotoSquareAttribute()->count()>=2)
                                                        <img
                                                                srcset="{{ $reference->getPhotoSquareAttribute()->all()[1]->getUrl() }}"
                                                                alt="{{ $reference->name }}"
                                                                title="{{ $reference->name }}"
                                                                class="mx-auto img-fluid lozad img-hover">
                                                    @else
                                                        <div class="reference_image_default">No image</div>
                                                    @endif
                                                    <p class="my-4">{{ $reference->name }}</p>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach

                        {{-- Other Industries --}}
                        <div class="tab-pane py-4 fade" id="ind_0" role="tabpanel" aria-labelledby="tab-alte-tab">
                            <ul class="list-unstyled img-grid">
                                @foreach($references as $reference)
                                    @if(!in_array($reference->industries_id,$industries_in_tab))
                                        <li class="img-grid--item px-4">
                                            <a href="{{ route('reference.'.app()->getLocale(), ['slug' => $reference->slug]) }}"
                                               class="text-center d-flex flex-column">
                                                @if($reference->getPhotoSquareAttribute()->all() !== null && $reference->getPhotoSquareAttribute()->count()>=2)
                                                    <img
                                                            srcset="{{ $reference->getPhotoSquareAttribute()->all()[1]->getUrl() }}"
                                                            alt="{{ $reference->name }}"
                                                            title="{{ $reference->name }}"
                                                            class="mx-auto img-fluid lozad img-hover">
                                                @else
                                                    <div class="reference_image_default">No image</div>
                                                @endif
                                                <p class="my-4">{{ $reference->name }}</p>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script src="{{ asset('/js/references-tabs.js') }}"></script>
@endsection
