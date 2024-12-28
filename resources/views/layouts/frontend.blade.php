<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        if (isset($_POST['search'])) {
            $search = $_POST['search'];
            redirect('search/'.$search);
        }

        if (isset($data[0]['nume'])) {
            $datanume = ' | '.$data[0]['nume'];
        } else {
            if (isset($page)) {
                $datanume = ' | '.$page;
            } else {
                $datanume = "";
            }
        }
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @foreach(config('panel.available_languages') as $langLocale => $langName)
        @php
            $parameters = [];
            if(isset($app_slugs)) {
                $parameters['app_slug'] = $app_slugs[$langLocale];
            }
            if(isset($cat_slugs)) {
                $parameters['cat_slug'] = $cat_slugs[$langLocale];
            }
            if(isset($prod_slugs)) {
                $parameters['prod_slug'] = $prod_slugs[$langLocale];
            }
            if(isset($slugs)) {
                $parameters['slug'] = $slugs[$langLocale];
            }
            if(isset($brand_slugs)) {
                $parameters['brand_slug'] = $brand_slugs[$langLocale];
            }
        @endphp
        @if($langLocale == app()->getLocale())
            @if(isset($prod_slugs) && isset($brand->slug))
                <link rel="canonical"
                      href="{{ route('product_brand.'.app()->getLocale(), ['brand_slug' => $brand->slug, 'prod_slug' => $product->translateOrDefault(app()->getLocale())->slug ]) }}"/>
            @else
                <link rel="canonical" href="{{ route(currentRouteChangeName($langLocale), $parameters) }}"/>
            @endif
        @endif
        <link rel="alternate" hreflang="{{ $langLocale }}"
              href="{{ route(currentRouteChangeName($langLocale), $parameters) }}"/>
    @endforeach
    {!! seo() !!}
    <!-- Google Tag Manager -->
    <script nonce="{{ session('csp_nonce') }}">(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
            var f = d.getElementsByTagName(s)[0], j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-PM3K8LC');</script>
    <!-- End Google Tag Manager -->

    <!--link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css"-->
    <link nonce="{{ session('csp_nonce') }}" href="https://fonts.googleapis.com/css?family=Exo+2:400,800"
          rel="stylesheet"
          type="text/css">
    <link nonce="{{ session('csp_nonce') }}"
          href="https://fonts.googleapis.com/css?family=Comfortaa:300,400&amp;subset=cyrillic"
          rel="stylesheet">
    <link nonce="{{ session('csp_nonce') }}" href="https://fonts.googleapis.com/css?family=Josefin+Sans:300"
          rel="stylesheet"
          type="text/css">
    <script nonce="{{ session('csp_nonce') }}" src='https://www.google.com/recaptcha/api.js'></script>
    <link nonce="{{ session('csp_nonce') }}" rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css"/>
    <script nonce="{{ session('csp_nonce') }}"
            src="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.min.css') }}">
    <link href="{{ asset('css/minex.css') }}" rel="stylesheet">
    <link href="{{ asset('css/frontend.css') }}" rel="stylesheet">
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe nonce="{{ session('csp_nonce') }}" src="https://www.googletagmanager.com/ns.html?id=GTM-PM3K8LC" height="0"
            width="0"
            style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<div id="totop" class="main_minex">

    @include('partials.main_menu')
    @yield('content')

</div><!-- /#main_minex -->
<footer class="d-flex py-2 px-4 justify-content-between align-items-center">

    <a class="navbar-brand" href="{{ url('') }}" title="Minex Group">
        <svg class="logo-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 139 100">
            <path fill="#FFF"
                  d="M117 91l7 9h-7l-4-5-6 5h-9l12-10-7-9h8l3 5 6-5h8l-11 10zm-84 9h8l5-19h-7l-6 19zm32-8l-6-11h-7l-5 19h6l4-11 6 11h7l5-19h-7l-3 11zm31 0l2-4H86l1-3h12l1-4H80l-5 19h21l1-4H83l2-4h11zM24 81l-8 12-1-12H6l-5 19h5l4-14 1 14h6l9-14-4 14h6l5-19h-9z"></path>
            <path fill="#ced4da" d="M110 50h8l10-2-3-6h-3l-15-4h-1l-5 8v4z"></path>
            <path fill="#FFF"
                  d="M89 55l-2-1-2-2-4-8s3-4 7-4l8 1 2 20-2 2s-7-1-8-6c-1-1 1-2 1-2zm-4-30c7 0 12 4 12 4L80 16l-2 12s3-3 7-3zm13 0L81 13l-1 2 17 12 1-2zm0-4l-1-1 2-8h1l1-1c-4-3-13-5-18-5l-1 6 16 11v-2zm5 1l2-9h-4l-1 8 3 1zM94 0c-7-1-10 5-10 5s12 1 17 5c0-3-1-8-7-10zM80 61c-2-1-2 0-3 1l6 1h5v-2h-8zm18 4s-1 2-4 0c-4-3-4-2-5-1-2 1-8 1-12-1-1-1-4 6-4 6h33l-3-8-5 4zm41-21v3h-8s0 2-2 2h-2s1 3-2 2l-5 1s-2 2-4 0l-1-1h-2l-13 11-3-22s-4-3-10-2c-5 2-7 6-7 6l3 8 4 4v4c-4 0-8 1-10-4l-4-12c0-4 4-17 13-17s17 10 17 10l3 1-2 10-3-2v4l5-1v-3l3 2 1-3h-4l1-7 15 4s3-1 5 1l3 1h9zm-20-2v1h5v-1h-5zm1 5l-7 2 1 1h-1 3l3-2 1-1zm5 0v-3h-11l-3 1v2l1 1 13-2v1z"></path>
            <path fill="#ced4da" d="M81 50h7l10-2-2-6h-4l-14-4h-1l-5 8v4z"></path>
            <path fill="#FFF"
                  d="M59 55l-1-1s-2 0-2-2l-4-8s3-4 7-4c3-1 7 1 7 1l3 20-2 2s-7-1-9-6l1-2zm-3-30c7 0 11 4 11 4h1L50 16l-1 12s2-3 7-3zm13 0L52 13l-1 2 17 12 1-2zm0-4l-1-1 1-8h2v-1c-3-3-12-5-17-5l-2 6 17 11v-2zm5 1l1-9h-3l-2 8 4 1zM64 0c-6-1-10 5-10 5s12 1 17 5c0-3 0-8-7-10zM51 61c-2-1-3 0-3 1l5 1h6v-2h-8zm17 4s-1 2-4 0c-4-3-4-2-5-1s-7 1-11-1c-2-1-4 6-4 6h33l-4-8-5 4zm42-21v3h-8s0 2-2 2h-2s1 3-3 2l-5 1s-2 2-3 0l-1-1h-3L71 62l-3-22s-5-3-10-2c-6 2-8 5-8 5l4 9s1 3 3 3c0 3-1 4 1 5-4 0-8 1-10-4l-4-12c0-4 4-17 13-17s17 10 17 10l3 1-2 10-3-2v4l5-1v-3l2 2 1-3h-3l1-7 14 4s4-1 6 1l2 1h10zm-20-2v1h5v-1h-5zm0 5l-6 2v1h2l4-2v-1zm6 0v-3H85l-4 1 1 2 1 1 13-2v1z"></path>
            <path fill="#ced4da" d="M52 50h8l10-2-3-6h-3l-15-4h-1l-5 8v4z"></path>
            <path fill="#FFF"
                  d="M129 78H0v-6h129v6zM30 57c1 5 8 6 8 6l2-2-2-20s-4-2-8-1-7 4-7 4l4 8 2 2 2 1s-2 1-1 2zM20 28l2-12 17 13s-5-4-12-4c-5 0-7 3-7 3zm19-1L22 15l1-2 17 12-1 2zm1-4L24 12l1-6c5 0 14 2 18 5l-1 1h-1l-2 8 1 1v2zm2-2l1-8h4l-2 9-3-1zm1-11c-5-4-17-5-17-5s3-6 10-5c6 2 7 7 7 10zM30 61v2h-5l-6-1c1-1 1-2 3-1h8zm15 0l3 8H15s2-7 4-6c4 2 10 2 12 1 1-1 1-2 5 1 3 2 4 0 4 0l5-4zm27-17l-3-2h-5l-15-4-1 7h4l-1 3-3-2v3l-5 1v-4l3 2 2-10-3-1s-8-10-17-10-13 13-13 17l4 12c2 5 6 4 10 4v-5c-2 0-4-3-4-3l-3-9s1-3 7-5c5-1 10 2 10 2l3 22 13-11h2l1 1c1 2 4 0 4 0s1-1 5-1c3 1 2-2 2-2h2c2 0 2-2 2-2h8v-3h-9zm-6-2v1h-5v-1h5zm-5 6l-4 2h-2v-1a84 84 0 0 1 6-1zm6-1v-1l-13 2-1-1v-2l3-1h11v3z"></path>
        </svg>
    </a>

    <ul class="list-inline my-0 mr-auto ml-4">
        <li class="list-inline-item">
            <a href="https://www.linkedin.com/company/minex-group" aria-label="Linkedin Link">
                <svg class="social-ico social-ico--linked-in" x="0px" y="0px" viewBox="0 0 100 100"
                     xml:space="preserve">
                            <path fill="#fff"
                                  d="M93.75,0H6.25C2.8,0,0,2.8,0,6.25v87.5C0,97.2,2.8,100,6.25,100h87.5c3.45,0,6.25-2.8,6.25-6.25V6.25 C100,2.8,97.2,0,93.75,0z M34.38,78.13h-12.5V34.38h12.5V78.13z M28.13,31.25c-3.45,0-6.25-2.8-6.25-6.25s2.8-6.25,6.25-6.25 s6.25,2.8,6.25,6.25S31.58,31.25,28.13,31.25z M78.13,78.13h-12.5v-25c0-4.1-2.29-6.25-6.25-6.25c-4.05,0-6.25,2.2-6.25,6.25v25 h-12.5V34.38h12.5v6.25c0.71-3.07,2.21-6.25,10.93-6.25c12.44,0,14.07,9.38,14.07,21.88V78.13z"></path>
                        </svg>
                <span class="d-none">LinkedIn</span>
            </a>
        </li>
        <li class="list-inline-item">
            <a href="https://www.youtube.com/user/minextv" aria-label="Youtube Link">
                <svg class="social-ico social-ico--you-tube" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve">
                            <path fill="#fff"
                                  d="M74.72,47.57H25.97c-7.75,0-14.03,6.29-14.03,14.03v11.28c0,7.75,6.28,14.03,14.03,14.03h48.75 c7.75,0,14.03-6.29,14.03-14.03V61.6C88.75,53.85,82.47,47.57,74.72,47.57z M36.21,56.35h-4.53v22.52H27.3V56.35h-4.53v-3.83h13.44 V56.35z M49.02,78.86h-3.88v-2.13c-0.72,0.79-1.47,1.39-2.24,1.79c-0.78,0.42-1.53,0.62-2.26,0.62c-0.9,0-1.57-0.29-2.03-0.87 c-0.45-0.58-0.68-1.44-0.68-2.61V59.42h3.88v14.91c0,0.46,0.09,0.79,0.24,1c0.17,0.21,0.43,0.31,0.8,0.31 c0.28,0,0.65-0.13,1.08-0.41c0.44-0.28,0.84-0.62,1.21-1.05V59.42h3.88V78.86z M62.2,78.01c-0.61,0.74-1.49,1.1-2.64,1.1 c-0.76,0-1.44-0.14-2.03-0.42c-0.59-0.28-1.15-0.72-1.66-1.32v1.49h-3.93V52.51h3.93V61c0.53-0.59,1.08-1.04,1.66-1.35 c0.59-0.31,1.19-0.46,1.79-0.46c1.23,0,2.17,0.41,2.81,1.24c0.65,0.83,0.98,2.04,0.98,3.63v10.79h0 C63.12,76.22,62.81,77.28,62.2,78.01z M76.59,69.61h-7.43v3.66c0,1.02,0.13,1.73,0.38,2.13c0.26,0.4,0.7,0.59,1.32,0.59 c0.65,0,1.09-0.17,1.35-0.5c0.25-0.34,0.39-1.07,0.39-2.22v-0.88h4v1c0,1.99-0.48,3.49-1.47,4.51c-0.97,1-2.42,1.5-4.36,1.5 c-1.74,0-3.12-0.53-4.11-1.6c-0.99-1.06-1.5-2.54-1.5-4.41v-8.73c0-1.68,0.55-3.06,1.66-4.12c1.1-1.06,2.51-1.59,4.25-1.59 c1.78,0,3.15,0.49,4.1,1.47c0.95,0.98,1.43,2.39,1.43,4.24V69.61z M72.18,62.88c0.27,0.34,0.41,0.93,0.41,1.74v1.97h-3.43v-1.97 c0-0.82,0.13-1.4,0.4-1.74c0.27-0.36,0.71-0.54,1.33-0.54C71.48,62.34,71.92,62.52,72.18,62.88z M58.7,62.78 c0.28,0.34,0.41,0.83,0.41,1.5v10.04c0,0.62-0.11,1.06-0.33,1.34c-0.22,0.28-0.57,0.42-1.05,0.42c-0.33,0-0.64-0.08-0.94-0.21 c-0.3-0.14-0.61-0.38-0.92-0.69V63.06c0.26-0.27,0.53-0.47,0.8-0.6c0.27-0.13,0.55-0.19,0.82-0.19 C58.01,62.28,58.42,62.45,58.7,62.78z M32.36,29.31l-5.86-17.53h4.96l3.2,11.6h0.31l3.05-11.6h5l-5.73,16.99v12.04h-4.92V29.31z  M49.68,41.35c1.99,0,3.55-0.52,4.69-1.56c1.13-1.05,1.69-2.48,1.69-4.31V24.42c0-1.63-0.58-2.97-1.73-4.01 c-1.16-1.03-2.64-1.55-4.45-1.55c-1.99,0-3.57,0.49-4.75,1.47c-1.17,0.98-1.76,2.3-1.76,3.96v11.1c0,1.82,0.57,3.26,1.72,4.34 C46.24,40.81,47.77,41.35,49.68,41.35z M47.87,24.13L47.87,24.13c0-0.47,0.17-0.84,0.5-1.14c0.34-0.29,0.77-0.43,1.31-0.43 c0.58,0,1.05,0.14,1.4,0.43c0.36,0.29,0.53,0.67,0.53,1.14V35.8c0,0.57-0.17,1.03-0.53,1.35c-0.35,0.33-0.82,0.49-1.41,0.49 c-0.58,0-1.02-0.16-1.34-0.49c-0.31-0.32-0.47-0.77-0.47-1.36V24.13z M60.38,40.16c-0.51-0.63-0.76-1.59-0.76-2.86V19.4h4.37v16.42 c0,0.5,0.1,0.87,0.28,1.09c0.18,0.23,0.48,0.35,0.89,0.35c0.32,0,0.73-0.15,1.22-0.45c0.49-0.3,0.94-0.69,1.35-1.15V19.4h4.37v21.41 h-4.37v-2.37c-0.8,0.87-1.64,1.54-2.52,2c-0.87,0.45-1.71,0.69-2.54,0.69C61.65,41.13,60.9,40.8,60.38,40.16z"></path>
                        </svg>
                <span class="d-none">You tube</span>
            </a>
        </li>
        <li class="list-inline-item">
            <a href="https://twitter.com/MinexGroup" aria-label="Twitter Link">
                <svg class="social-ico social-ico--twitter" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve">
                            <path fill="#fff"
                                  d="M87.32,26.32c-2.73,1.21-5.67,2.03-8.75,2.4c3.15-1.88,5.56-4.87,6.7-8.43c-2.94,1.75-6.2,3.01-9.67,3.7 c-2.78-2.96-6.74-4.81-11.12-4.81c-8.41,0-15.23,6.82-15.23,15.23c0,1.19,0.13,2.35,0.39,3.47c-12.66-0.64-23.88-6.7-31.39-15.91 c-1.31,2.25-2.06,4.86-2.06,7.66c0,5.28,2.69,9.94,6.77,12.67c-2.5-0.08-4.84-0.77-6.9-1.91c0,0.06,0,0.13,0,0.19 c0,7.38,5.25,13.53,12.22,14.93c-1.28,0.35-2.62,0.53-4.01,0.53c-0.98,0-1.94-0.09-2.87-0.27c1.94,6.05,7.56,10.45,14.23,10.58 c-5.21,4.08-11.78,6.52-18.91,6.52c-1.23,0-2.44-0.07-3.63-0.21c6.74,4.32,14.75,6.84,23.34,6.84c28.01,0,43.33-23.2,43.33-43.33 c0-0.66-0.01-1.32-0.04-1.97C82.7,32.06,85.28,29.38,87.32,26.32z"></path>
                        </svg>
                <span class="d-none">Twitter</span>
            </a>
        </li>
        <li class="list-inline-item">
            <a href="https://www.facebook.com/MinexGroup/" aria-label="Facebook Link">
                <svg class="social-ico social-ico--facebook" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve">
                            <path fill="#fff"
                                  d="M93.75,0H6.25C2.8,0,0,2.8,0,6.25v87.5C0,97.2,2.8,100,6.25,100h46.88V62.5h-12.5V46.88h12.5V37.5 c0-12.87,7.27-21.88,18.75-21.88c5.5,0,9.38,0,12.5,0v15.63h-6.25c-6.24,0-9.38,3.13-9.38,9.38v6.25h15.63L81.25,62.5h-12.5V100h25 c3.45,0,6.25-2.8,6.25-6.25V6.25C100,2.8,97.2,0,93.75,0z"></path>
                        </svg>
                <span class="d-none">Facebook</span>
            </a>
        </li>
    </ul>

    <p class="text-right my-0 m-button">
        <a href="{{ route('gdpr.'.app()->getLocale()) }}">{{ trans('frontend.gdpr_compliance') }}</a><br>&#169; {{ trans('frontend.copyright') }}
    </p>

</footer>

<div class="internal-nav__footer">
    <button id="goToTopBtn" class="">
        <svg x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><circle
                    fill="#ff7200" stroke="#FFFFFF" stroke-width="4" stroke-miterlimit="10" cx="50" cy="50" r="45.93"/>
            <polygon fill="#FFFFFF"
                     points="49.99,36.73 25.79,50.56 25.79,62.7 49.99,48.86 74.2,62.7 74.2,50.56 "/></svg>
    </button>
</div>

{{--<div class="internal-nav__footer">--}}
{{--    <a href="#totop" class="internal-nav internal-nav__totop toTop-js">--}}
{{--        <svg x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><circle--}}
{{--                    fill="#ff7200" stroke="#FFFFFF" stroke-width="4" stroke-miterlimit="10" cx="50" cy="50" r="45.93"/>--}}
{{--            <polygon fill="#FFFFFF"--}}
{{--                     points="49.99,36.73 25.79,50.56 25.79,62.7 49.99,48.86 74.2,62.7 74.2,50.56 "/></svg>--}}
{{--    </a>--}}
{{--</div>--}}
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/customBootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.bxslider.min.js') }}"></script>
<script src="{{ asset('js/lozad.min.js') }}"></script>
<script nonce="{{ session('csp_nonce') }}"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-autohidingnavbar/4.0.0/jquery.bootstrap-autohidingnavbar.min.js"
        integrity="sha384-Qr+Xdd2EtQ9NfRBwaD8GS3FUMQ/8LcF+y5gKrsTeX8Q2R4yaDOFlPpPsma2N4qvk"
        crossorigin="anonymous"></script>
<script src="{{ asset('js/static.min.js') }}"></script>
<script src="{{ asset('js/hide-menu.js') }}"></script>
@yield('scripts')
</body>
</html>


