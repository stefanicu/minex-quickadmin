<div class="animate-area sectionHero">
    <button id="scrollButton" class="internal-nav internal-nav__animate-area">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
             x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
			<polygon fill="#FFFFFF" points="77.8,44.4 50.5,60 23.2,44.4 23.2,31 50.5,46.6 77.8,31 	"></polygon>
		</svg>
    </button>
    <div class="animate-area--text text-center">
        @if($hero)
            {!! $hero->first_text !!}
            <p class="animate-area--sub">
                @php echo strip_tags($hero->second_text,'<br><strong><b>') @endphp
            </p>
        @endif
    </div>
    <div class="animate-area--cover"></div>
    <div class="animate-area--static"></div>
    <div class="animate-area--dinamic"></div>
</div>
