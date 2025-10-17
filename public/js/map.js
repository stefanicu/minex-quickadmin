function initialize() {
    // Custom map styles
    var styles = [
        {
            featureType: 'road',
            elementType: 'geometry',
            stylers: [
                {lightness: 100},
                {visibility: 'simplified'}
            ]
        }, {
            featureType: 'road',
            elementType: 'labels',
            stylers: [
                {visibility: 'on'}
            ]
        }
    ];

    // Location data
    var locations = [
        {
            info: `<p><b>Minex Group - Romania - Headquarter</b><br/><br/>
                Bd. Metalurgiei nr.85, 041832<br/>
                Bucuresti, Romania</p>
                <dl>
                <dt>T:</dt> <dd><a href="tel:+40213060281">+40 (21) 3060281</a></dd>
                <dt>F:</dt> <dd>+40 (21) 3060284</dd>
                <dt>E:</dt> <dd><a href="mailto:office@minexgroup.eu">office@minexgroup.eu</a></dd>
                </dl>
                <p><a href="https://maps.google.com/?cid=12183351190759340993" target="_blank">View in Google Maps</a> </p>`,
            lat: 44.368939,
            lng: 26.137053,
        },
        {
            info: `<p><b>Minex Group - Romania - Arad</b><br/><br/>
                Str. II Nr. 3, Zona Industriala N-V, 310491<br/>
                Arad, Romania</p>
                <dl>
                <dt>M:</dt> <dd><a href="tel:+40729019952">+40 (729) 019952</a></dd>
                <dt>T:</dt> <dd><a href="tel:+40213060281">+40 (21) 3060281</a></dd>
                <dt>F:</dt> <dd>+40 (21) 3060284</dd>
                <dt>E:</dt> <dd><a href="mailto:office@minexgroup.eu">office@minexgroup.eu</a></dd>
                </dl>
                <p><a href="https://maps.google.com/?cid=15534180713851440036" target="_blank">View in Google Maps</a></p>`,
            lat: 46.1961188,
            lng: 21.311336,
        },
        {
            info: `<p><b>Minex Group - Romania - Sibiu</b><br/><br/>
                    General Industrial Park (Incinta Hidrosib)<br/>
                    Str. Stefan cel Mare nr. 150, 550321<br/>
                     Sibiu, Romania</p>
                    <dl>
                    <dt>M:</dt> <dd><a href="tel:+40730018344">+40 (730) 018344</a></dd>
                    <dt>T:</dt> <dd><a href="tel:+40213060281">+40 (21) 3060281</a></dd>
                    <dt>F:</dt> <dd>+40 (21) 3060284</dd>
                    <dt>E:</dt> <dd><a href="mailto:office@minexgroup.eu">office@minexgroup.eu</a></dd>
                    </dl>
                    <p><a href="https://maps.google.com/?cid=10683645034450341709" target="_blank">View in Google Maps</a> </p>`,
            lat: 45.78879478,
            lng: 24.18916333,
        },
        {
            info: `<p><b>Minex Group - Bulgaria</b><br/><br/>
                    Logic Park STAD: 467 Okolovrasten pat St. 1532<br />
                    Sofia, Bulgaria</p>
                    <dl>
                    <dt>M:</dt> <dd><a href="tel:+3590885647622">+359 (0) 885647622</a></dd>
                    <dt>T:</dt> <dd><a href="tel:+359024915909">+359 (0) 24915909</a></dd>
                    <dt>F:</dt> <dd>+359 (0) 24915909</dd>
                    <dt>E:</dt> <dd><a href="mailto:office@minexgroup.eu">office@minexgroup.eu</a></dd>
                    </dl>
                    <p><a href="https://www.google.com/maps?cid=119518942697758357" target="_blank">View in Google Maps</a></p>`,
            lat: 42.68634,
            lng: 23.4532,
        },
        {
            info: `<p><b>Minex Bulgaria OOD</b><br/><br/>
                    West Industrial Zone<br />
                    6010 Stara Zagora, Bulgaria</p>
                    <dl>
                    <dt>M:</dt> <dd><a href="tel:+359896588773">+359 89 6588773</a></dd>
                    <dt>E:</dt> <dd><a href="mailto:galin.georgiev@minexgroup.eu">galin.georgiev@minexgroup.eu</a></dd>
                    </dl>
                    <p><a href="https://maps.app.goo.gl/QGK2LpjRZtiZRNyL8" target="_blank">View in Google Maps</a></p>`,
            lat: 42.409376770303176,
            lng: 25.608345391730982,
        },
        {
            info: `<p><b>Minex Group - Balkans Representative Office</b></p>
                    <dl>
                    <dt>M:</dt> <dd><a href="tel:+38163507586">+381 (63) 507586</a></dd>
                    <dt>T:</dt> <dd><a href="tel:+381113088626">+381 (11) 3088626</a></dd>
                    <dt>F:</dt> <dd>+381 (11) 3088626</dd>
                    <dt>E:</dt> <dd><a href="mailto:office@minexgroup.eu">office@minexgroup.eu</a></dd>
                    <dt></dt> <dd><a href="mailto:musan.imamovic@minexgroup.eu">musan.imamovic@minexgroup.eu</a></dd>
                    </dl>
                    <p><a href="https://maps.app.goo.gl/7YBvcQbvGjsCVjNV9" target="_blank">View in Google Maps</a> </p>`,
            lat: 44.817604,
            lng: 20.461965,
        },
        {
            info: `<p><b>Minex Baltics UAB</b></p>
                    <dl>
                    <dt>M:</dt> <dd><a href="tel:+37067463371">+370 (674) 63371</a></dd>
                    <dt>E:</dt> <dd><a href="mailto:office@minexgroup.eu">office@minexgroup.eu</a></dd>
                    </dl>`,
            lat: 54.69220918393462,
            lng: 25.27826650204201,
        }
    ];

    // Map options
    const mapOptions = {
        zoom: 5,
        center: {lat: 48.5, lng: 24}, // Mutat mai sus pentru a lăsa spațiu în jos
        zoomControl: true,
        zoomControlOptions: {
            position: google.maps.ControlPosition.RIGHT_CENTER
        },
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
            position: google.maps.ControlPosition.TOP_RIGHT
        },
        streetViewControl: true,
        streetViewControlOptions: {
            position: google.maps.ControlPosition.RIGHT_CENTER
        },
        fullscreenControl: true,
        fullscreenControlOptions: {
            position: google.maps.ControlPosition.RIGHT_TOP
        },
        scaleControl: true,
        scrollwheel: true,
        gestureHandling: 'greedy', // Permite scroll liber fără Ctrl
        mapId: '95f351d503268e4bddf8ae5d' // Pentru Advanced Markers
    };

    // Initialize the map
    const map = new google.maps.Map(document.getElementById("map"), mapOptions);

    // Forțează refresh-ul hărții după încărcare
    google.maps.event.addListenerOnce(map, 'idle', function () {
        google.maps.event.trigger(map, 'resize');
    });

    // Info window instance
    const infoWindow = new google.maps.InfoWindow();

    // Add markers to the map
    locations.forEach((location) => {
        // Validate coordinates
        if (typeof location.lat !== 'number' || typeof location.lng !== 'number' ||
            !isFinite(location.lat) || !isFinite(location.lng)) {
            console.error(`Invalid lat/lng for location: ${JSON.stringify(location)}`);
            return;
        }

        // Create marker image element
        const markerImg = document.createElement("img");
        markerImg.src = "img/home/s7/map-marker.png";
        markerImg.style.width = "30px";
        markerImg.style.height = "30px";

        // Create Advanced Marker
        const marker = new google.maps.marker.AdvancedMarkerElement({
            map: map,
            position: {lat: location.lat, lng: location.lng},
            content: markerImg,
            title: location.info.match(/<b>(.*?)<\/b>/)?.[1] || "Location"
        });

        // Add click event to open info window
        marker.addListener('click', () => {
            infoWindow.setContent(location.info);
            infoWindow.open(map, marker);
        });
    });
}

// Initialize when Google Maps API is loaded
window.initialize = initialize;