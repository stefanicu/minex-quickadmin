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
    <script>var selectedCountry = "{{ old('country') }}";var selectedState = "{{ old('county') }}";</script>
    <script src="{{ asset('/js/countries.js') }}?x={{ rand(398473,298379283647847) }}"></script>
    <script>populateCountries("country", "county");</script>
    <script async defer src="//maps.googleapis.com/maps/api/js?key=AIzaSyCRHi8eiqWm--iQQ-fNTq3AWKev7xCj2RA&callback=initialize"></script>
    <script src="{{ asset('/js/map.js?v=274625') }}"></script>
    <script>
        $(document).ready(function(){$(function(){$('.bxslider').bxSlider({mode:'fade',slideWidth:400});});
            $(function(){$('.bxslider-related').bxSlider({minSlides:1,maxSlides:3,slideWidth:360,slideMargin:5,pager:false});});});
        $(function(){$('.bxslider-img').bxSlider({mode:'fade',slideWidth:1110,pager:false,controls:true});});
    </script>
@endsection
