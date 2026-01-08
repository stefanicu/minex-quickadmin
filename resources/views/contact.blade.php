@extends('layouts.frontend')
@section('content')

    <h1>CONTACT</h1>

@endsection
@section('scripts')
    @parent
    <script src="({{ asset('/js/validate/jquery.validate.min.js') }}))"></script>
    <script src="{{ asset('/js/validate/set.validate.js') }}"></script>
    <script src="<{{ asset('/js/countries.js') }}"></script>
    <script>populateCountries("country", "county");</script>
    <script async defer src="//maps.googleapis.com/maps/api/js?key=AIzaSyC2lR8NWEGdgk2MM7Xw3Kj5YUKcz4iG-A0&callback=initialize"></script>
    <script src="{{ asset('/js/map.js?v=274625') }}"></script>
@endsection
