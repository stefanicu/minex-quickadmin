@extends('layouts.frontend')
@section('content')

    <h1>BRANDS</h1>

    <div class="row">
        @foreach($brands as $slug=>$brand)
            <div class="col-3"><a href="{{ $slug }}/">{{ $brand }}</a></div>
        @endforeach
    </div>

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
