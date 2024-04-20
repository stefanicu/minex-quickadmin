<section class="container-fluid section--contact py-4 section3">
    <div class="row justify-content-center">
        <div class="col-10">
            <h2 class="h1 px-0 px-md-4">{{ trans('frontend.contact_us') }}</h2>
            <a name="contact" id="contact"></a>
        </div>
    </div>

@if(isset($_GET['ct']) && $_GET['ct'] == 1)
    <div class="multumim-center">{{ trans('frontend.contact_thank_you') }}</div>
@else
    <div class="row justify-content-center">
        <div class="col-12 col-lg-5">
            <form class="px-0 px-md-4" id="contactForm" autocomplete="off" action="{{ url('') }}/contact/'" method="post">
                @include('.partials.form')
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                        <div class="form-group captcha-error">

                            <input type="hidden" id="ip" name="ip" value="<?=$_SERVER['REMOTE_ADDR']; ?>">

                            <?php //if(ENVIRONMENT == 'production'): ?>
                                <input type="hidden" class="hiddenRecaptcha form-control" name="hiddenRecaptcha" id="hiddenRecaptcha">
                                <!--<div class="g-recaptcha" data-sitekey="6Le4vigTAAAAAA2rn1tgsj056Bevy-TThTWdMUCp"></div>-->
                                <div class="g-recaptcha" data-sitekey="6Lc-mScTAAAAAIoo8LHTWRuJhPUXdDFJoGV4ptBg"></div>
                            <?php //endif; ?>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block"><?php //echo $buton_formular; ?></button>
            </form>
        </div>
        <div class="col-12 col-lg-5">
            <!-- harta -->
            <div id="map"></div>
        </div>
        <div class="col-12 pt-4 text-center address">
            <p class="mb-0"><?php //echo $text_formular ?></p>
        </div>
    </div>
@endif
</section>
