<section class="container-fluid mx-0 px-0 section section__left pt-4 section section5">
    <figure class="d-flex align-items-center section--figure">
        <picture data-alt="s5">
            <source data-srcset="{{ asset('/img/home/s5/map-xl.jpg') }}" media="(min-width: 1200px)">
            <source data-srcset="{{ asset('/img/home/s5/map-lg.jpg') }}" media="(min-width: 992px)">
            <source data-srcset="{{ asset('/img/home/s5/map-md.jpg') }}" media="(min-width: 576px)">
            <source data-srcset="{{ asset('/img/home/s5/map-sm.jpg') }}" media="(max-width: 576px)">
            <img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ asset('/img/home/s5/map-xl.jpg') }}"
                 alt="s5" data-loaded="true">
            <noscript>
                <img class="img-fluid lozad" src="{{ asset('/img/home/s5/map-xl.jpg') }}" alt="s5">
            </noscript>
        </picture>
    </figure>
    <div class="section--content d-flex align-items-center">
        <div class="v-alighn px-4">
            @if($references)
                <h3 class="h3">{{ $references->name }}</h3>
                <ul class="d-flex mx-auto list-unstyled justify-content-center flex-wrap row-icons">
                    @foreach($references_industries as $references_industry)
                        @php
                            if( in_array($references_industry->id, [8, 12, 4, 6]) ){
                                $ind_tab = '#ind_'.$references_industry->id;
                            }else{
                                $ind_tab = '#ind_0';
                            }
                        @endphp
                        <li class='py-3'>
                            <a href="{{ route('references.'.app()->getLocale()).$ind_tab }}"
                               class="d-flex flex-column text-center">
                                @if($references_industry->getPhotoAttribute())
                                    <img data-src="{{ $references_industry->getPhotoAttribute()->getUrl() }}"
                                         alt="{{ $references_industry->name }}"
                                         class="svg_gray row-icons--ico-img mx-auto lozad img-fluid">
                                @endif
                                <p class="row-icons--desc px-2 mt-4 {{ app()->getLocale() === 'bg' ? 'wrap_russian' : '' }}">{{ $references_industry->name }}</p>
                            </a>
                        </li>
                    @endforeach

                </ul>
            @endif
        </div>
    </div>
</section>
<section class="container-fluid py-4 ">
    <div class="container my-2">
        <div class="row py-4">
            <div class="col">
                @if($references)
                    <ul class="list-unstyled img-grid">
                        @foreach($references_references as $references_reference)
                            <li class="img-grid--item px-4">
                                <a href="{{ route('reference.'.app()->getLocale(), ['slug' => $references_reference->slug]) }}"
                                   class="text-center d-flex flex-column">
                                    @if($references_reference->getPhotoSquareAttribute()->all() !== null && $references_reference->getPhotoSquareAttribute()->count()>=2)
                                        <img
                                                srcset="{{ $references_reference->getPhotoSquareAttribute()->all()[1]->getUrl() }}"
                                                alt="{{ $references_reference->name }}"
                                                title="{{ $references_reference->name }}"
                                                class="mx-auto img-fluid lozad img-hover">
                                    @else
                                        <div class="reference_image_default">No image</div>
                                    @endif
                                    <p class="my-4">{{ $references_reference->name }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="text-center">
                        <a href="{{ route('references.'.app()->getLocale()) }}" class="btn btn-primary">{{ $references->button }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
