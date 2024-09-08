<section class="container-fluid mx-0 px-0 section section__left pt-4 section section5">
	<figure class="d-flex align-items-center section--figure">
		<picture data-alt="s5">
            <source data-srcset="{{ asset('/img/home/s5/map-xl.jpg') }}" media="(min-width: 1200px)">
            <source data-srcset="{{ asset('/img/home/s5/map-lg.jpg') }}" media="(min-width: 992px)">
            <source data-srcset="{{ asset('/img/home/s5/map-md.jpg') }}" media="(min-width: 576px)">
            <source data-srcset="{{ asset('/img/home/s5/map-sm.jpg') }}" media="(max-width: 576px)">
			<img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ asset('/img/home/s5/map-xl.jpg') }}" alt="s5" data-loaded="true">
			<noscript>
				<img class="img-fluid lozad" src="{{ asset('/img/home/s5/map-xl.jpg') }}" alt="s5">
			</noscript>
		</picture>
	</figure>
	<div class="section--content d-flex align-items-center">
		<div class="v-alighn px-4">
			<h2 class="h3">{{ $references->name }}</h2>
            <ul class="d-flex mx-auto list-unstyled justify-content-center flex-wrap row-icons">
                <?php
                //dd($industries);
                if(app()->getLocale() == 'bg'){ $wrap_russian = 'wrap_russian'; }else{ $wrap_russian = ''; }
                foreach ($industries as $ind) {
                    $image_url = '';
                    if($ind->getPhotoAttribute())
                        $image_url = $ind->getPhotoAttribute()->getUrl();
                    echo "<li class='py-3'>
                          <a href='referinte#tab-a" . $ind['industry_id'] . "' class='d-flex flex-column text-center'>
                              <img data-src='" .  $image_url . "' alt='" . $ind['name'] . "' class='svg_gray row-icons--ico-img mx-auto lozad img-fluid'>
                              <p class='row-icons--desc px-2 mt-4 $wrap_russian'>" . $ind['name'] . "</p>
                          </a>
                      </li>";
                }
                ?>
            </ul>
		</div>
	</div>
</section>
<section class="container-fluid py-4 ">
	<div class="container my-2">
		<div class="row py-4">
			<div class="col">
				<ul class="list-unstyled img-grid">
				<?php
					//foreach ($referinte12 as $r12) {
						//echo '<li class="img-grid--item px-4"><a href="' . base_url() . $referinta_x . '/' . $r12['slug'] . '" class="text-center d-flex flex-column"><img data-src="' . HTTP_UPLOADS_PATH . 'images/' . $r12['img'] . '" alt="" class="mx-auto img-fluid lozad img-hover"><p class="my-4">' . $r12['nume'] . '</p></a></li>';
					//}
					?>
				</ul>
				<div class="text-center">
					<a href="{{ url('') }}/references/" class="btn btn-primary">{{ $references->button }}</a>
				</div>
			</div>
		</div>
	</div>
</section>
