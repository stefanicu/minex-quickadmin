<section class="container-fluid px-0 mx-0 section section__right section__primary pt-4 section4">
	<figure class="d-flex align-items-center section--figure">
		<picture data-alt="s4">
			<source data-srcset="{{ asset('/img/home/s4/map-xl.jpg') }}" media="(min-width: 1200px)">
			<source data-srcset="{{ asset('/img/home/s4/map-lg.jpg') }}" media="(min-width: 992px)">
			<source data-srcset="{{ asset('/img/home/s4/map-md.jpg') }}" media="(min-width: 576px)">
			<source data-srcset="{{ asset('/img/home/s4/map-sm.jpg') }}" media="(max-width: 576px)">
			<img class="lozad img-fluid section--hero-img lazy-fade" srcset="{{ asset('/img/home/s4/map-xl.jpg') }}" alt="s4" data-loaded="true">
			<noscript>
				<img class="img-fluid lozad" src="{{ asset('/img/home/s4/map-xl.jpg') }}" alt="s4">
			</noscript>
		</picture>
	</figure>
	<div class="section--content d-flex align-items-center">
		<div class="v-alighn px-4">
			<h2 class="h3">{{ $maintenance->name }}</h2>
			{!! $maintenance->first_text !!}<br>
			<a href="{{ url('') }}/{{ trans('fronted.testimonials') }}" class="btn btn btn-light">{{ $maintenance->button }}</a>
		</div>
	</div>
</section>
<section class="container-fluid section__primary py-4 ">
	<div class="container my-2">
		<div class="row">
			<div class="col col-lg-8">
				{!! $maintenance->second_text !!}
			</div>
			<div class="col col-lg-4">
				<blockquote class="blockquote-home blockquote__secondary">
					{!! $maintenance->quote !!}
				</blockquote>
			</div>
		</div>
	</div>
</section>
