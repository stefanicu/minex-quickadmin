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

    var locationsmap = [
        ['<p><b>Minex Group - Romania - Headquarter</b><br/><br/>' +
        'Bd. Metalurgiei nr.85, 041832<br/>' +
        'Bucuresti, Romania</p>' +
        '<dl>' +
        '<dt>T:</dt> <dd><a href="tel:+40213060281">+40 (21) 3060281</a></dd>' +
        '<dt>F:</dt> <dd>+40 (21) 3060284</dd>' +
        '<dt>E:</dt> <dd><a href="mailto:office@minexgroup.eu">office@minexgroup.eu</a></dd>' +
        '</dl>' +
        '<p><a href="https://maps.google.com/?cid=12183351190759340993" target="_blank">View in Google Maps</a> </p>',
            44.368939, 26.137053, 1],

        ['<p><b>Minex Group - Romania - Craiova</b></p>' +
        '<dl>' +
        '<dt>M:</dt> <dd><a href="tel:+40730600072">+40 (730) 600072</a></dd>' +
        '<dt>T:</dt> <dd><a href="tel:+40213060281">+40 (21) 306 0281</a></dd>' +
        '<dt>F:</dt> <dd>+40 (21) 3060284</dd>' +
        '<dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>' +
        '</dl>',
            44.31721, 23.79475],

        ['<p><b>Minex Group - Romania - Arad</b><br/><br/>' +
        'Parc Industrial UTA, Hala 37<br/>' +
        'Str. Poetului Nr.1C, 310345<br/>' +
        ' Arad, Romania</p>' +
        '<dl>' +
        '<dt>M:</dt> <dd><a href="tel:+40729019952">+40 (729) 019952</a></dd>' +
        '<dt>T:</dt> <dd><a href="tel:+40213060281">+40 (21) 3060281</a></dd>' +
        '<dt>F:</dt> <dd>+40 (21) 3060284</dd>' +
        '<dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>' +
        '</dl>' +
        '<p><a href="https://maps.google.com/?cid=15534180713851440036" target="_blank">View in Google Maps</a></p>',
            46.1961188, 21.311336, 4],

        ['<p><b>Minex Group - Romania - Sibiu</b></p>' +
        'General Industrial Park (Incinta Hidrosib)<br/>' +
        'Str. Stefan cel Mare nr. 150, 550321' +
        ' Sibiu, Romania</p>' +
        '<dl>' +
        '<dt>M:</dt> <dd><a href="tel:+40 730 018 344">+40 (730) 018344</a></dd>' +
        '<dt>T:</dt> <dd><a href="tel:+40213060281">+40 (21) 3060281</a></dd>' +
        '<dt>F:</dt> <dd>+40 (21) 3060284</dd>' +
        '<dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>' +
        '</dl>' +
        '<p><a href="https://maps.google.com/?cid=10683645034450341709" target="_blank">View in Google Maps</a> </p>',
            45.78879478, 24.18916333, 6],

        ['<p><b>Minex Group - Romania - Iasi</b></p>' +
        '<dl>' +
        '<dt>M:</dt> <dd><a href="tel:+40730011240">+40 (730) 011240</a></dd>' +
        '<dt>T:</dt> <dd><a href="tel:+40213060281">+40 (21) 3060281</a></dd>' +
        '<dt>F:</dt> <dd>+40 (21) 3060284</dd>' +
        '<dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>' +
        '</dl>',
            47.15984827, 27.59766881, 7],

        ['<p><b>Minex Group - Romania - Cluj Napoca</b></p>' +
        '<dl>' +
        '<dt>M:</dt> <dd><a href="tel:+40724313334">+40 (724) 313334</a></dd>' +
        '<dt>T:</dt> <dd><a href="tel:+40213060281">+40 (21) 3060281</a></dd>' +
        '<dt>F:</dt> <dd>+40 (21) 3060284</dd>' +
        '<dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>' +
        '</dl>'
            , 46.786737, 23.60394, 5],

        ['<p><b>Minex Group - Bulgaria</b><br/><br/>' +
        'Logic Park â€œSTADâ€ 467 Okolovrasten pat St. 1532<br />' +
        'Sofia, Bulgaria</p>' +
        '<dl>' +
        '<dt>M:</dt> <dd><a href="tel:+3590885647622">+359 (0) 885647622</a></dd>' +
        '<dt>T:</dt> <dd><a href="tel:+359024915909">+359 (0) 24915909</a></dd>' +
        '<dt>F:</dt> <dd>+359 (0) 24915909</dd>' +
        '<dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>' +
        '</dl>' +
        '<a href="https://www.google.com/maps?cid=119518942697758357" target="_blank">View in Google Maps</a></p>',
            42.68634, 23.4532, 2],

        ['<p><b>Minex Group - Balkans Representative Office</b></p>' +
        '<dl>' +
        '<dt>M:</dt> <dd><a href="tel:+38163507586">+381 (63) 507586</a></dd>' +
        '<dt>T:</dt> <dd><a href="tel:+381113088626">+381 (11) 3088626</a></dd>' +
        '<dt>F:</dt> <dd>+381 (11) 3088626</dd>' +
        '<dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>' +
        '</dl>' +
        '<a href="https://www.google.ro/maps/place/Francuska+5,+Beograd+11000,+Serbia/@44.8171471,20.4597724,17.7z/data=!4m13!1m7!3m6!1s0x475a7ab3c73615d7:0xff115b58e47afd62!2sFrancuska+5,+Beograd+11000,+Serbia!3b1!8m2!3d44.8173921!4d20.4619684!3m4!1s0x475a7ab3c73615d7:0xff115b58e47afd62!8m2!3d44.8173921!4d20.4619684?hl=en" target="_blank">View in Google Maps</a> </p>',
            44.817604, 20.461965, 7],

        ['<p><b>Minex Baltics UAB</b></p>' +
        '<dl>' +
        '<dt>M:</dt> <dd><a href="tel:+37067463371">+370 (674) 63371</a></dd>' +
        '<dt>E:</dt> <dd><a href="mail:office@minexgroup.eu">office@minexgroup.eu</a></dd>' +
        '</dl>',
            54.69220918393462, 25.27826650204201, 7],

    ];

    var myIcon = new google.maps.MarkerImage('img/home/s7/map-marker.png', null, null, null, new google.maps.Size(30, 30));

// Create a new StyledMapType object, passing it the array of styles,
// as well as the name to be displayed on the map type control.
    var styledMap = new google.maps.StyledMapType(styles,
        {name: 'Styled Map'});

// Create a map object, and include the MapTypeId to add
// to the map type control.
    var mapOptions = {
        zoom: 5,
        center: new google.maps.LatLng(49, 24),
        zoomControl: true,
        scaleControl: false,
        scrollwheel: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
        }
    };

    var map = new google.maps.Map(document.getElementById('map'), mapOptions);

    var infowindowmap = new google.maps.InfoWindow();

    var marker2, x;

    for (x = 0; x < locationsmap.length; x++) {
        marker2 = new google.maps.Marker({
            position: new google.maps.LatLng(locationsmap[x][1], locationsmap[x][2]),
            map: map,
            icon: myIcon
        });

        google.maps.event.addListener(marker2, 'click', (function (marker2, x) {
            return function () {
                infowindowmap.setContent(locationsmap[x][0]);
                infowindowmap.open(map, marker2);
            }
        })(marker2, x));
    }
}
