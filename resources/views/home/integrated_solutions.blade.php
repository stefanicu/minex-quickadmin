<section id="internal-pg-nav1" class="container-fluid px-0 mx-0 section section__right section__primary pt-4 section2">
    <figure class="d-flex align-items-center section--figure">
        <picture data-alt="s1">
            <source data-srcset="{{ asset('/img/home/s1/xl-min.jpg') }}" media="(min-width: 1200px)">
            <source data-srcset="{{ asset('/img/home/s1/lg-min.jpg') }}" media="(min-width: 992px)">
            <source data-srcset="{{ asset('/img/home/s1/md-min.jpg') }}" media="(min-width: 576px)">
            <source data-srcset="{{ asset('/img/home/s1/sm-min.jpg') }}" media="(max-width: 576px)">
            <img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ asset('/img/home/s1/xl-min.jpg') }}" alt="s1" data-loaded="true">
            <noscript>
                <img class="img-fluid lozad" src="{{ asset('/img/home/s1/xl-min.jpg') }}" alt="s1">
            </noscript>
        </picture>
    </figure>
    <div class="section--content d-flex align-items-center">
        <div class="v-alighn px-4">
            <h2 class="h3">{{ $integrated_solutions->name }}</h2>
            {!! $integrated_solutions->first_text !!}
            <hr>
            <p class="text-center">{{ trans('frontend.see_what_we_have_done') }}</p>

            <ul class="d-flex mx-auto list-unstyled justify-content-center flex-wrap row-icons">

                <?php
                if(app()->getLocale() == 'bg'){ $wrap_russian = 'wrap_russian'; }else{ $wrap_russian = ''; }
//                foreach ($industrii as $ind) {
//                    echo "<li class='py-3'>
//                              <a href='url('')referinte#tab-a" . $ind['id_ind'] . "' class='d-flex flex-column text-center'>
//                                  <img data-src='ind_image' alt='ind_nume' class='row-icons--ico-img mx-auto lozad img-fluid'>
//                                  <p class='row-icons--desc px-2 mt-4 $wrap_russian'>ind_nume</p>
//                              </a>
//                          </li>";
//                }
                ?>
            </ul>
            <a href="{{ url('') }}branduri/" class="btn btn-light">{{ $integrated_solutions->button }}</a>
        </div>
    </div>
</section>
<section class="container-fluid section__primary py-4 ">
    <div class="container my-2">
        <div class="row">
            <div class="col col-lg-5">{!! $integrated_solutions->second_text !!}</div>
            <div class="col col-lg-7">
                <blockquote class="blockquote-home  blockquote__secondary">{!! $integrated_solutions->quote !!}</blockquote>
            </div>
        </div>
    </div>
</section>
