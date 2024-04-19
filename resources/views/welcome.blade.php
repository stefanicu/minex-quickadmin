@extends('layouts.frontend')
@section('content')
    <!-- Hero Section -->
    @include('home.hero')

    <!-- Integrated Solutions Section -->
    @include('home.integrated_solutions')

    <!-- Integrated Consultancy -->
    @include('home.consultancy')

    <!-- Integrated Maintenance -->
    @include('home.maintenance')

    <!-- Integrated References -->
    @include('home.references')



<?php //require('home/referinte.php');?>
<?php //require('home/solutii_complete.php');?>
<?php //require('home/contact.php');?>



@endsection
@section('scripts')
    @parent
    <script src="({{ asset('/js/validate/jquery.validate.min.js') }}))"></script>
    <script src="{{ asset('/js/validate/set.validate.js') }}"></script>
    <script src="<{{ asset('/js/countries.js') }}"></script>
    <script>populateCountries("tara", "jud");</script>
    <script async defer src="//maps.googleapis.com/maps/api/js?key=AIzaSyCRHi8eiqWm--iQQ-fNTq3AWKev7xCj2RA&callback=initialize"></script>
    <script src="{{ asset('/js/map.js?v=274625') }}"></script>
@endsection
