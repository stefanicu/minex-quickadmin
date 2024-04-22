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
    <script src="{{ asset('/js/countries.js') }}"></script>
    <script>populateCountries("tara", "jud");</script>
    <script async defer src="//maps.googleapis.com/maps/api/js?key=AIzaSyCRHi8eiqWm--iQQ-fNTq3AWKev7xCj2RA&callback=initialize"></script>
    <script src="{{ asset('/js/map.js?v=274625') }}"></script>
@endsection
