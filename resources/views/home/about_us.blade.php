<section class="container-fluid px-0 mx-0 section section__right section__primary pt-4 section6">
    <figure class="d-flex align-items-center section--figure">
        <picture data-alt="s6">
            {{--            <source data-srcset="{{ asset('/img/home/s6/minex-group-office.jpg') }}" media="(min-width: 1200px)">--}}
            {{--            <source data-srcset="{{ asset('/img/home/s6/lg-min.jpg') }}" media="(min-width: 992px)">--}}
            {{--            <source data-srcset="{{ asset('/img/home/s6/md-min.jpg') }}" media="(min-width: 576px)">--}}
            {{--            <source data-srcset="{{ asset('/img/home/s6/sm-min.jpg') }}" media="(max-width: 576px)">--}}
            <img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ asset('/img/home/s6/minex-group-office.jpg') }}"
                 alt="s6" data-loaded="true">
            <noscript>
                <img class="img-fluid lozad" src="{{ asset('/img/home/s6/minex-group-office.jpg') }}" alt="s6">
            </noscript>
        </picture>
    </figure>
    <div class="section--content d-flex align-items-center">
        <div class="v-alighn px-4">
            @if($about_us)
                <h2 class="h3">{{ $about_us->name }}</h2>
                {!! $about_us->first_text !!}
            @endif
        </div>
    </div>
</section>

<section class="container-fluid section__primary py-4 ">
    <div class="container">
        <div class="bxslider--section">
            @if($about_us)
                <div class="wysiwyg">
                    {!! $about_us->second_text !!}
                </div>
            @endif
            <div class="pl-0 pl-md-4 home-slider">
                <div class="bxslider">
                    <div>
                        <img src="{{ asset('/img/home/s6/slider/despre-noi1-min.png') }}" class="img-fluid" alt="Minex 1" title="Minex 1">
                    </div>
                    <div>
                        <img src="{{ asset('/img/home/s6/slider/despre-noi2-min.png') }}" class="img-fluid" alt="Minex 2" title="Minex 2">
                    </div>
                    <div>
                        <img src="{{ asset('/img/home/s6/slider/despre-noi3-min.png') }}" class="img-fluid" alt="Minex 3" title="Minex 3">
                    </div>
                    <div>
                        <img src="{{ asset('/img/home/s6/slider/despre-noi4-min.png') }}" class="img-fluid" alt="Minex 4" title="Minex 4">
                    </div>
                    <div>
                        <img src="{{ asset('/img/home/s6/slider/despre-noi5-min.png') }}" class="img-fluid" alt="Minex 5" title="Minex 5">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
