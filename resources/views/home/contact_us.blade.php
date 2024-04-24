<section class="container-fluid section--contact py-4 section3">
    <div class="row justify-content-center">
        <div class="col-10">
            <h2 class="h1 px-0 px-md-4">{{ $contact_us->name }}</h2>
            <a name="contact" id="contact"></a>
        </div>
    </div>

@if(session('success'))
    <div class="multumim-center">{{ trans('frontend.contact_thank_you') }}</div>
@else
    <div class="row justify-content-center">
        <div class="col-12 col-lg-5">
            <form class="px-0 px-md-4" id="contactForm" autocomplete="off" action="{{ url('') }}/contact/" method="post" autocomplete="off">
                @csrf
                @include('.partials.form')

                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group captcha-error">

                            <input type="hidden" id="district" name="district" value="">
                            <input type="hidden" id="ip" name="ip" value="<?=$_SERVER['REMOTE_ADDR']; ?>">

                            @if(app()->isProduction())
                                <input type="hidden" class="hiddenRecaptcha form-control" name="hiddenRecaptcha" id="hiddenRecaptcha">
                                <div class="g-recaptcha" data-sitekey="6Lc-mScTAAAAAIoo8LHTWRuJhPUXdDFJoGV4ptBg"></div>
                            @endif

                            @foreach($errors as $error)
                                {{$error}}
                            @endforeach

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">{{ $contact_us->button }}</button>
            </form>
        </div>
        <div class="col-12 col-lg-5">
            <!-- harta -->
            <div id="map"></div>
        </div>
        <div class="col-12 pt-4 text-center address">
            <p class="mb-0">{!! $contact_us->first_text !!}</p>
        </div>
    </div>
@endif
</section>
