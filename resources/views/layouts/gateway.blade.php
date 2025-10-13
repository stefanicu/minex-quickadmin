<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @foreach(config('panel.available_languages') as $langLocale => $langName)
        @if($langLocale ===  'en')
            <link rel="alternate" hreflang="{{ $langLocale }}" href="{{ route('home.' . $langLocale) }}"/>
        @else
            <link rel="alternate" hreflang="{{ $langLocale.'-'.languageToCountryCode($langLocale) }}" href="{{ route('home.' . $langLocale) }}"/>
        @endif
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ url('') }}"/>
    <link rel="canonical" href="{{ url('') }}"/>

    <meta name="robots" content="max-snippet:-1,max-image-preview:large,max-video-preview:-1">
    <link href="/img/favicon.png" rel="shortcut icon">

    <title>Minex</title>
    <meta name="description" content="Choose your language - Изберете език - Odaberite jezik - Vali keel - Odaberite jezik - Válassza ki a nyelvet - Pasirinkite kalbą - Izvēlieties valodu - Изберете јазик - Alege limba - Izberite jezik - Izaberite jezik - Виберіть мову">

    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
</head>
<body class="relative min-h-screen w-screen flex items-center justify-center bg-black/70 p-4">
<!-- Orange top bar -->
<div class="absolute top-0 left-0 w-full bg-orange-500 xopacity-60 z-20 py-2 px-4">
    <!-- Logo SVG -->
    <svg class="logo-svg" version="1" viewBox="0 0 139 100">
        <path fill="#FFF" d="M117 91l7 9h-7l-4-5-6 5h-9l12-10-7-9h8l3 5 6-5h8l-11 10zm-84 9h8l5-19h-7l-6 19zm32-8l-6-11h-7l-5 19h6l4-11 6 11h7l5-19h-7l-3 11zm31 0l2-4H86l1-3h12l1-4H80l-5 19h21l1-4H83l2-4h11zM24 81l-8 12-1-12H6l-5 19h5l4-14 1 14h6l9-14-4 14h6l5-19h-9z"></path>
        <path fill="#F7931E" d="M110 50h8l10-2-3-6h-3l-15-4h-1l-5 8v4z"></path>
        <path fill="#FFF" d="M89 55l-2-1-2-2-4-8s3-4 7-4l8 1 2 20-2 2s-7-1-8-6c-1-1 1-2 1-2zm-4-30c7 0 12 4 12 4L80 16l-2 12s3-3 7-3zm13 0L81 13l-1 2 17 12 1-2zm0-4l-1-1 2-8h1l1-1c-4-3-13-5-18-5l-1 6 16 11v-2zm5 1l2-9h-4l-1 8 3 1zM94 0c-7-1-10 5-10 5s12 1 17 5c0-3-1-8-7-10zM80 61c-2-1-2 0-3 1l6 1h5v-2h-8zm18 4s-1 2-4 0c-4-3-4-2-5-1-2 1-8 1-12-1-1-1-4 6-4 6h33l-3-8-5 4zm41-21v3h-8s0 2-2 2h-2s1 3-2 2l-5 1s-2 2-4 0l-1-1h-2l-13 11-3-22s-4-3-10-2c-5 2-7 6-7 6l3 8 4 4v4c-4 0-8 1-10-4l-4-12c0-4 4-17 13-17s17 10 17 10l3 1-2 10-3-2v4l5-1v-3l3 2 1-3h-4l1-7 15 4s3-1 5 1l3 1h9zm-20-2v1h5v-1h-5zm1 5l-7 2 1 1h-1 3l3-2 1-1zm5 0v-3h-11l-3 1v2l1 1 13-2v1z"></path>
        <path fill="#F7931E" d="M81 50h7l10-2-2-6h-4l-14-4h-1l-5 8v4z"></path>
        <path fill="#FFF" d="M59 55l-1-1s-2 0-2-2l-4-8s3-4 7-4c3-1 7 1 7 1l3 20-2 2s-7-1-9-6l1-2zm-3-30c7 0 11 4 11 4h1L50 16l-1 12s2-3 7-3zm13 0L52 13l-1 2 17 12 1-2zm0-4l-1-1 1-8h2v-1c-3-3-12-5-17-5l-2 6 17 11v-2zm5 1l1-9h-3l-2 8 4 1zM64 0c-6-1-10 5-10 5s12 1 17 5c0-3 0-8-7-10zM51 61c-2-1-3 0-3 1l5 1h6v-2h-8zm17 4s-1 2-4 0c-4-3-4-2-5-1s-7 1-11-1c-2-1-4 6-4 6h33l-4-8-5 4zm42-21v3h-8s0 2-2 2h-2s1 3-3 2l-5 1s-2 2-3 0l-1-1h-3L71 62l-3-22s-5-3-10-2c-6 2-8 5-8 5l4 9s1 3 3 3c0 3-1 4 1 5-4 0-8 1-10-4l-4-12c0-4 4-17 13-17s17 10 17 10l3 1-2 10-3-2v4l5-1v-3l2 2 1-3h-3l1-7 14 4s4-1 6 1l2 1h10zm-20-2v1h5v-1h-5zm0 5l-6 2v1h2l4-2v-1zm6 0v-3H85l-4 1 1 2 1 1 13-2v1z"></path>
        <path fill="#F7931E" d="M52 50h8l10-2-3-6h-3l-15-4h-1l-5 8v4z"></path>
        <path fill="#FFF" d="M129 78H0v-6h129v6zM30 57c1 5 8 6 8 6l2-2-2-20s-4-2-8-1-7 4-7 4l4 8 2 2 2 1s-2 1-1 2zM20 28l2-12 17 13s-5-4-12-4c-5 0-7 3-7 3zm19-1L22 15l1-2 17 12-1 2zm1-4L24 12l1-6c5 0 14 2 18 5l-1 1h-1l-2 8 1 1v2zm2-2l1-8h4l-2 9-3-1zm1-11c-5-4-17-5-17-5s3-6 10-5c6 2 7 7 7 10zM30 61v2h-5l-6-1c1-1 1-2 3-1h8zm15 0l3 8H15s2-7 4-6c4 2 10 2 12 1 1-1 1-2 5 1 3 2 4 0 4 0l5-4zm27-17l-3-2h-5l-15-4-1 7h4l-1 3-3-2v3l-5 1v-4l3 2 2-10-3-1s-8-10-17-10-13 13-13 17l4 12c2 5 6 4 10 4v-5c-2 0-4-3-4-3l-3-9s1-3 7-5c5-1 10 2 10 2l3 22 13-11h2l1 1c1 2 4 0 4 0s1-1 5-1c3 1 2-2 2-2h2c2 0 2-2 2-2h8v-3h-9zm-6-2v1h-5v-1h5zm-5 6l-4 2h-2v-1a84 84 0 0 1 6-1zm6-1v-1l-13 2-1-1v-2l3-1h11v3z"></path>
    </svg>
</div>

<!-- Background image -->
<div class="absolute inset-0">
    <img
            src="{{ asset('img/sfinx-background.jpg') }}"
            alt="Background"
            class="w-full h-full object-cover"
    >
    <div class="absolute inset-0 bg-black/50 xbackdrop-blur-sm"></div>
</div>

<!-- Card -->
<div class="relative z-10 max-w-5xl w-full mt-[50px] bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden">
    <div class="text-center flex flex-col items-center ">
        <!-- Card title -->
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mt-8 text-white drop-shadow-md">
            Choose your language
        </h1>

        <!-- Globe icon under title -->
        <div class="text-base sm:text-lg lg:text-2xl mt-10 bg-orange-500/50 text-white text-center w-full px-4 py-3">
            <ul class="flex flex-wrap justify-center gap-x-0 gap-y-2 divide-x divide-white/50">
                <li class="px-2">Изберете език</li>
                <li class="px-2">Odaberite jezik</li>
                <li class="px-2">Vali keel</li>
                <li class="px-2">Odaberite jezik</li>
                <li class="px-2">Válassza ki a nyelvet</li>
                <li class="px-2">Pasirinkite kalbą</li>
                <li class="px-2">Izvēlieties valodu</li>
                <li class="px-2">Изберете јазик</li>
                <li class="px-2">Alege limba</li>
                <li class="px-2">Izberite jezik</li>
                <li class="px-2">Izaberite jezik</li>
                <li class="px-2">Виберіть мову</li>
            </ul>
        </div>
    </div>

    <!-- Language grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4 sm:p-8 lg:p-10">
        <!-- English full width -->
        <a href="{{ url('') . '/en' }}" class="col-span-full block p-8 text-xl sm:text-2xl lg:text-3xl text-center bg-white/90 rounded-xl shadow-md md:hover:shadow-xl md:hover:scale-105 transition">
            🌐 English
        </a>

        @foreach(config('panel.languages_names') as $language=>$name)
            @if($language !== 'en')
                <a href="{{ url('') . '/' . $language }}" class="block p-4 text-center bg-white/70 rounded-xl shadow-md md:hover:shadow-xl md:hover:scale-105 transition">{{ $name }}</a>
            @endif
        @endforeach
    </div>
</div>
</body>
</html>