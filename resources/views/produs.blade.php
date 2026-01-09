@extends('layouts.frontend')
@section('content')



@endsection
@section('scripts')
    @parent
    <script src="({{ asset('/js/validate/jquery.validate.min.js') }}))"></script>
    <script src="{{ asset('/js/validate/set.validate.js') }}"></script>
    <script src="<{{ asset('/js/countries.js') }}"></script>
    <script>populateCountries("country", "county");</script>
    <script src="{{ asset('/js/map.js?v=274625') }}"></script>
@endsection
