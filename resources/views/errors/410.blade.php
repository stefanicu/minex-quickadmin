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

    <meta name="robots" content="noindex, follow">
    <link href="/img/favicon.png" rel="shortcut icon">

    <title>410 Gone - Page Not Available</title>

    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
</head>
<body class="relative min-h-screen w-screen flex items-center justify-center bg-black/70 p-4">
<!-- Orange top bar -->
<div class="absolute top-0 left-0 w-full bg-orange-500 xopacity-60 z-20 py-2 px-4">
    <svg class="logo-svg" version="1" viewBox="0 0 139 100">
        <!-- poți păstra logo-ul existent -->
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
<div class="relative z-10 max-w-4xl w-full mt-[50px] bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl overflow-hidden">
    <div class="text-center flex flex-col items-center p-8">
        <!-- Card title -->
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mt-4 text-white drop-shadow-md">
            410 Gone
        </h1>

        <p class="mt-6 text-lg sm:text-xl lg:text-2xl text-white drop-shadow-md">
            Sorry, the page you are looking for has been permanently removed.
        </p>

        <a href="{{ url('') }}" class="mt-8 px-6 py-3 bg-orange-500/80 text-white text-lg font-semibold rounded-lg shadow-md md:hover:shadow-xl md:hover:scale-105 transition">
            Go to Homepage
        </a>
    </div>
</div>
</body>
</html>