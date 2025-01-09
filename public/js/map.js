function initialize() {
    // Create an array of styles.
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
            info: `<p><b>Minex Group - Romania - Craiova</b></p>
                <dl>
                <dt>M:</dt> <dd><a href="tel:+40730600072">+40 (730) 600072</a></dd>
                <dt>T:</dt> <dd><a href="tel:+40213060281">+40 (21) 306 0281</a></dd>
                <dt>F:</dt> <dd>+40 (21) 3060284</dd>
                <dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>
                </dl>`,
            lat: 44.31721,
            lng: 23.79475,
        },
        {
            info: `<p><b>Minex Group - Romania - Arad</b><br/><br/>
                Parc Industrial UTA, Hala 37<br/>
                Str. Poetului Nr.1C, 310345<br/>
                Arad, Romania</p>
                <dl>
                <dt>M:</dt> <dd><a href="tel:+40729019952">+40 (729) 019952</a></dd>
                <dt>T:</dt> <dd><a href="tel:+40213060281">+40 (21) 3060281</a></dd>
                <dt>F:</dt> <dd>+40 (21) 3060284</dd>
                <dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>
                </dl>
                <p><a href="https://maps.google.com/?cid=15534180713851440036" target="_blank">View in Google Maps</a></p>`,
            lat: 46.1961188,
            lng: 21.311336, // 4
        }

        ,
        {
            info: `<p><b>Minex Group - Romania - Sibiu</b></p>
                    General Industrial Park (Incinta Hidrosib)<br/>
                    Str. Stefan cel Mare nr. 150, 550321
                     Sibiu, Romania</p>
                    <dl>
                    <dt>M:</dt> <dd><a href="tel:+40 730 018 344">+40 (730) 018344</a></dd>
                    <dt>T:</dt> <dd><a href="tel:+40213060281">+40 (21) 3060281</a></dd>
                    <dt>F:</dt> <dd>+40 (21) 3060284</dd>
                    <dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>
                    </dl>
                    <p><a href="https://maps.google.com/?cid=10683645034450341709" target="_blank">View in Google Maps</a> </p>`,
            lat: 45.78879478,
            lng: 24.18916333, // 6
        },
        {
            info: `<p><b>Minex Group - Romania - Iasi</b></p>
                    <dl>
                    <dt>M:</dt> <dd><a href="tel:+40730011240">+40 (730) 011240</a></dd>
                    <dt>T:</dt> <dd><a href="tel:+40213060281">+40 (21) 3060281</a></dd>
                    <dt>F:</dt> <dd>+40 (21) 3060284</dd>
                    <dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>
                    </dl>`,
            lat: 47.15984827,
            lng: 27.59766881, // 7
        },
        {
            info: `<p><b>Minex Group - Bulgaria</b><br/><br/>
                    Logic Park STAD: 467 Okolovrasten pat St. 1532<br />
                    Sofia, Bulgaria</p>
                    <dl>
                    <dt>M:</dt> <dd><a href="tel:+3590885647622">+359 (0) 885647622</a></dd>
                    <dt>T:</dt> <dd><a href="tel:+359024915909">+359 (0) 24915909</a></dd>
                    <dt>F:</dt> <dd>+359 (0) 24915909</dd>
                    <dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>
                    </dl>
                    <a href="https://www.google.com/maps?cid=119518942697758357" target="_blank">View in Google Maps</a></p>`,
            lat: 42.68634,
            lng: 23.4532, // 2
        },
        {
            info: `<p><b>Minex Group - Balkans Representative Office</b></p>
                    <dl>
                    <dt>M:</dt> <dd><a href="tel:+38163507586">+381 (63) 507586</a></dd>
                    <dt>T:</dt> <dd><a href="tel:+381113088626">+381 (11) 3088626</a></dd>
                    <dt>F:</dt> <dd>+381 (11) 3088626</dd>
                    <dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>
                    </dl>
                    <a href="https://maps.app.goo.gl/7YBvcQbvGjsCVjNV9" target="_blank">View in Google Maps</a> </p>`,
            lat: 44.817604,
            lng: 20.461965, // 7
        },
        {
            info: `<p><b>Minex Baltics UAB</b></p>
                    <dl>
                    <dt>M:</dt> <dd><a href="tel:+37067463371">+370 (674) 63371</a></dd>
                    <dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>`,
            lat: 54.69220918393462,
            lng: 25.27826650204201, // 7
        }
    ];

    // Map options
    const mapOptions = {
        zoom: 5,
        center: {lat: 49, lng: 24}, // Ensure center coordinates are numbers
        zoomControl: true,
        scaleControl: false,
        scrollwheel: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'styled_map']
        }
    };

    // Initialize the map
    const map = new google.maps.Map(document.getElementById('map'), mapOptions);

    // Apply custom styles
    const styledMapType = new google.maps.StyledMapType(styles, {name: 'Styled Map'});
    map.mapTypes.set('styled_map', styledMapType);
    map.setMapTypeId('styled_map');

    // Custom marker icon
    const icon = {
        url: 'img/home/s7/map-marker.png',
        scaledSize: new google.maps.Size(30, 30) // Adjust size if needed
    };

    // Info window instance
    const infoWindow = new google.maps.InfoWindow();

    // Add markers to the map
    locations.forEach((location) => {
        if (typeof location.lat !== 'number' || typeof location.lng !== 'number') {
            console.error(`
        }Invalid lat/lng for location: ${JSON.stringify(location)}`);
            return; // Skip invalid location
        }

        const marker = new google.maps.Marker({
            position: {lat: location.lat, lng: location.lng},
            map: map,
            icon: icon
        });

        // Add click event to open info window
        marker.addListener('click', () => {
            infoWindow.setContent(location.info);
            infoWindow.open(map, marker);
        });
    });
}
