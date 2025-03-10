<section class="container-fluid section--contact py-4 section3">
    <div class="row justify-content-center">
        <div id="contact" class="col-10">
            @if($contact_us)
                <h2 class="h1 px-0 px-md-4">{{ $contact_us->name }}</h2>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="multumim-center">{{ trans('pages.contact_thank_you') }}</div>
    @else
        <div class="row justify-content-center">
            <div class="col-12 col-lg-5">
                <form class="px-0 px-md-4" id="contactForm" autocomplete="off" action="{{ route('contact.post.'.app()->getLocale()) }}" method="post" autocomplete="off">
                    @csrf
                    @include('.partials.form')

                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group captcha-error">

                                <input type="hidden" id="district" name="district" value="">
                                <input type="hidden" id="ip" name="ip" value="{{ request()->ip() }}">

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
                    @if($contact_us)
                        <button type="submit" class="btn btn-primary btn-lg btn-block">{{ $contact_us->button }}</button>
                    @endif
                </form>
            </div>
            <div class="col-12 col-lg-5">
                <!-- harta -->
                <div id="map"></div>
            </div>
            {{--        <div class="col-12 pt-4 text-center address">--}}
            {{--            <p class="mb-0">{!! $contact_us->first_text !!}</p>--}}
            {{--        </div>--}}
        </div>
    @endif
</section>

<section class="container my-4 p-4 mx-auto">
    <div id="addresses" class="row justify-content-center">
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 text-center text-sm-left">
            <p>
                {{--                <a href="https://maps.google.com/?cid=12183351190759340993" target="_blank">Bucuresti, Romania</a><br/>--}}
                <b>Bucuresti, Romania</b><br/>
                A: Bd. Metalurgiei Nr. 85<br/>
                041832 Bucuresti, Romania<br/>
                T: +40 21 3060281
            </p>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 text-center text-sm-left">
            <p>
                {{--                <a href="https://maps.google.com/?cid=10683645034450341709" target="_blank">Sibiu, Romania</a><br/>--}}
                <b>Sibiu, Romania</b><br/>
                A: Str. Stefan cel Mare Nr. 150<br/>
                550321 Sibiu, Romania<br/>
                M: +40 (730) 018344
            </p>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 text-center text-sm-left">
            <p>
                {{--                <a href="https://maps.google.com/?cid=15534180713851440036" target="_blank">Arad, Romania</a><br/>--}}
                <b>Arad, Romania</b><br/>
                A: Str. II Nr. 3,<br/>
                Zona Industriala N-V<br/>
                310491 Arad, Romania<br/>
                M: +40 (729) 019952
            </p>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 text-center text-sm-left">
            <p>
                <b>Iasi, Romania</b><br/>
                M: +40 (730) 011240
            </p>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 text-center text-sm-left">
            <p>
                <b>Craiova, Romania</b><br/>
                M: +40 (730) 600072
            </p>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 text-center text-sm-left">
            <p>
                {{--                <a href="https://www.google.com/maps?cid=119518942697758357" target="_blank">Sofia, Bulgaria</a><br/>--}}
                <b>Minex Bulgaria OOD<br/>Sofia, Bulgaria</b><br/>
                A: Logic Park STAD:<br/>
                467 Okolovrasten pat St.<br/>
                1532 Sofia, Bulgaria<br/>
                T: +359 (2) 491 59 09
            </p>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 text-center text-sm-left">
            <p>
                {{--                <a href="https://www.google.ro/maps/place/Francuska+5,+Beograd+11000,+Serbia/@44.8171471,20.4597724,17.7z/data=!4m13!1m7!3m6!1s0x475a7ab3c73615d7:0xff115b58e47afd62!2sFrancuska+5,+Beograd+11000,+Serbia!3b1!8m2!3d44.8173921!4d20.4619684!3m4!1s0x475a7ab3c73615d7:0xff115b58e47afd62!8m2!3d44.8173921!4d20.4619684?hl=en"--}}
                {{--                   target="_blank">--}}
                {{--                    Belgrade, Serbia--}}
                {{--                </a><br/>--}}
                <b>Minex Balkans - Representative Office<br/>Belgrade, Serbia</b><br/>
                A: Francuska 5,<br/>
                11000 Belgrade, Serbia<br/>
                M: +381 (63) 507586
            </p>
        </div>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 text-center text-sm-left">
            <p>
                <b>Minex Baltics UAB<br/>Vilnius, Lithuania</b><br/>
                A: Gariūnų g. 57,<br/>
                02300 Vilnius, Lithuania<br/>
                M: +370 674 63371
            </p>
        </div>
    </div>
</section>