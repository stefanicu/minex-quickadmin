@extends('layouts.frontend')
@section('content')
    <!-- Hero Section -->
    @include('home.hero')

    <!-- Integrated Solutions Section -->
    @include('home.integrated_solutions')

    <!-- Consultancy Section-->
    @include('home.consultancy')

    <!-- Maintenance Section-->
    @include('home.maintenance')

    <!-- References Section -->
    @include('home.references')

    <!-- About Us Section -->
    @include('home.about_us')

    <!-- Contact Us Section -->
    @include('home.contact_us')

@endsection
@section('scripts')
    @parent
    <script src="{{ asset('/js/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('/js/validate/set.validate.js') }}"></script>
    <script src="{{ asset('/js/countries.js') }}?x={{ rand(398473,298379283647847) }}"></script>
    <script nonce="{{ session('csp_nonce') }}"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRHi8eiqWm--iQQ-fNTq3AWKev7xCj2RA&callback=initialize&v=weekly&libraries=marker"
            async defer></script>
    <script src="{{ asset('/js/map.js?v=274623') }}"></script>
    <script src="{{ asset('/js/bxSlider/home_setup.js') }}"></script>
@endsection
