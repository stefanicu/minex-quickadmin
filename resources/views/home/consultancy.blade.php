<section class="container-fluid mx-0 px-0 section section__left pt-4 section3">
	<figure class="d-flex align-items-center section--figure">
		<picture data-alt="s2">
			<source data-srcset="{{ asset('/img/home/s3/xl-min.jpg') }}" media="(min-width: 1200px)">
			<source data-srcset="{{ asset('/img/home/s3/xl-min.jpg') }}" media="(min-width: 992px)">
			<source data-srcset="{{ asset('/img/home/s3/xl-min.jpg') }}" media="(min-width: 576px)">
			<source data-srcset="{{ asset('/img/home/s3/xl-min.jpg') }}" media="(max-width: 576px)">
			<img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ asset('/img/home/s3/xl-min.jpg') }}" alt="s2" data-loaded="true">
			<noscript>
				<img class="img-fluid lozad" src="{{ asset('/img/home/s3/xl-min.jpg') }}" alt="s2">
			</noscript>
		</picture>
	</figure>
	<div class="section--content d-flex align-items-center">
		<div class="v-alighn px-4">
			<h2 class="h3">{{ $consultancy->name }}</h2>
			<p>{{ trans('frontend.see_what_we_have_done') }}:</p>
			<ul>
				<?php
					// foreach ($proiecte as $pro) {
					// 	echo "<li><a href=" . base_url() . $referinta_x . "/" . $pro['slug'] . ">" . $pro['nume'] . "</a></li>";
					// }
				?>
			</ul>
		</div>
	</div>
</section>

<section class="container-fluid py-4 ">
	<div class="container my-2">
		<div class="row">
			<div class="col col-lg-5">{!! $consultancy->first_text !!}</div>
			<div class="col col-lg-7">
				<blockquote class="blockquote-home blockquote__primary">{!! $consultancy->quote !!}</blockquote>
				<img data-src="{{ asset('/img/home/s3/broken-nat-min.jpg') }}" class="img-fluid lozad lazy-fade" alt="PASSION IS EVERYTHING">
			</div>
		</div>
	</div>
</section>
