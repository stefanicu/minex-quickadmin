@extends('layouts.frontend')
@section('content')
    <!-- Hero Section -->
    <div class="animate-area sectionHero">
        <a href="#internal-pg-nav1" class="internal-nav internal-nav__animate-area">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
			<polygon fill="#FFFFFF" points="77.8,44.4 50.5,60 23.2,44.4 23.2,31 50.5,46.6 77.8,31 	"></polygon>
		</svg>
        </a>
        <div class="animate-area--text text-center">
            <h1 class="animate-area--title">
                @php echo strip_tags($hero->first_text,'<br><strong><b>') @endphp
            </h1>
            <p class="animate-area--sub">
                @php echo strip_tags($hero->second_text,'<br><strong><b>') @endphp
            </p>
        </div>
        <div class="animate-area--cover"></div>
        <div class="animate-area--static"></div>
        <div class="animate-area--dinamic"></div>
    </div>



    <!----><?php //require('home/solutii.php');?>
<!--    --><?php //require('home/consultanta.php');?>
<!--    --><?php //require('home/montaj.php');?>
<!--    --><?php //require('home/referinte.php');?>
<!--    --><?php //require('home/solutii_complete.php');?>
<!--    --><?php //require('home/contact.php');?><!---->



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
