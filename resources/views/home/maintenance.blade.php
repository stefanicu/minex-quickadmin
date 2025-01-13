<section class="container-fluid px-0 mx-0 section section__right section__primary pt-4 section4">
    <figure class="d-flex align-items-center section--figure">
        <picture data-alt="s4">
            <source data-srcset="{{ asset('/img/home/s4/xl-min.jpg') }}" media="(min-width: 1200px)">
            <source data-srcset="{{ asset('/img/home/s4/lg-min.jpg') }}" media="(min-width: 992px)">
            <source data-srcset="{{ asset('/img/home/s4/md-min.jpg') }}" media="(min-width: 576px)">
            <source data-srcset="{{ asset('/img/home/s4/sm-min.jpg') }}" media="(max-width: 576px)">
            <img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ asset('/img/home/s4/xl-min.jpg') }}"
                 alt="s4" data-loaded="true">
            <noscript>
                <img class="img-fluid lozad" src="{{ asset('/img/home/s4/xl-min.jpg') }}" alt="s4">
            </noscript>
        </picture>
    </figure>
    <div class="section--content d-flex align-items-center">
        <div class="v-alighn px-4">
            @if($maintenance)
                <h2 class="h3">{{ $maintenance->name }}</h2>
                {!! $maintenance->first_text !!}<br>
                <a href="{{ route('testimonials.'.app()->getLocale()) }}" class="btn btn btn-light">{{ $maintenance->button }}</a>
            @endif
        </div>
    </div>
</section>
<section class="container-fluid section__primary py-4 ">
    <div class="container my-2">
        <div class="row">
            @if($maintenance)
                <div class="col col-lg-8">
                    {!! $maintenance->second_text !!}
                </div>
                <div class="col col-lg-4">
                    <blockquote class="blockquote-home blockquote__secondary">
                        {!! $maintenance->quote !!}
                    </blockquote>
                </div>
            @endif
        </div>
    </div>
</section>
