@extends('layouts.frontend')
@section('content')

    <div class="container">
        <h1 class="py-4">TESTIMONIALS</h1>
        <div class="row">
            @foreach($testimonials as $testimonial)
                <div class="col-sm-12 col-md-6 col-lg-4 py-4">{{ $testimonial->company }}</div>
            @endforeach
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    <script src="({{ asset('/js/validate/jquery.validate.min.js') }}))"></script>
    <script src="{{ asset('/js/validate/set.validate.js') }}"></script>
    <script src="<{{ asset('/js/countries.js') }}"></script>
    <script>populateCountries("country", "county");</script>
    <script async defer src="//maps.googleapis.com/maps/api/js?key=AIzaSyCRHi8eiqWm--iQQ-fNTq3AWKev7xCj2RA&callback=initialize"></script>
    <script src="{{ asset('/js/map.js?v=274625') }}"></script>
@endsection
