function initialize()
{
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

    var myLatLng = {lat: 44.369208, lng: 26.137183};
    var myIcon = new google.maps.MarkerImage('/assets/images/map-marker.png', null, null, null, new google.maps.Size(70, 70));

// Create a new StyledMapType object, passing it the array of styles,
// as well as the name to be displayed on the map type control.
    var styledMap = new google.maps.StyledMapType(styles,
        {name: 'Styled Map'});

// Create a map object, and include the MapTypeId to add
// to the map type control.
    var mapOptions = {
        zoom: 14,
        center: myLatLng,
        zoomControl: true,
        scaleControl: false,
        scrollwheel: false,
        mapTypeControlOptions: {
            mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
        }
    };
    var map = new google.maps.Map(document.getElementById('map'),
        mapOptions);

    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        flat: true,
        title: 'Minex Group',
        icon: myIcon
    });

//Associate the styled map with the MapTypeId and set it to display.
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');



    //references

    var locations = [
            ['<p>Damen Shipyard Galaţi<br/>' +
                '<a href="tel:+40236154511">+40 236 154 511</a><br/>' +
                '<a href="http://www.damen.ro" target="_blank">www.damen.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=8242896504020321089" target="_blank">View in Google Maps</a> </p>',45.4443538,28.091220300000032,1],
            ['<p>ArcelorMittal<br/>' +
                '<a href="tel:+40236801331">+40 236 801 331</a><br/>' +
                '<a href="http://galati.arcelormittal.com" target="_blank">www.arcelormittal.com</a><br/>' +
                '<a href="https://maps.google.com/?cid=15556503440466111507" target="_blank">View in Google Maps</a> </p>',45.43993080000001,27.971480199999974,2],
            ['<p>DMHI-Daewoo-Mangalia Heavy Industries<br/>' +
                '<a href="tel:+40372411300">+40 372 411 300</a><br/>' +
                '<a href="http://www.dmhi.ct.ro" target="_blank">www.dmhi.ct.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=4907324306509614005" target="_blank">View in Google Maps</a> </p>',43.7980464,28.569482300000004,3],
            ['<p>Vard Tulcea<br/>' +
                '<a href="tel:+40240501000">+40 240 501 000</a><br/>' +
                '<a href="http://www.vard.com" target="_blank">www.vard.com</a><br/>' +
                '<a href="https://maps.google.com/?cid=11344849452182356622" target="_blank">View in Google Maps</a> </p>',45.1935671,28.784680200000025,4],
            ['<p>Aker Brăila S.A.<br/>' +
                '<a href="tel:+40239607002">+40 239 607 002</a><br/>' +
                '<a href="https://maps.google.com/?cid=16295084187565696260" target="_blank">View in Google Maps</a> </p>',45.2508,27.95769999999993,5],
            ['<p>Șantierul Naval Constanța<br/>' +
                '<a href="tel:+40241505500">+40 241 505 500</a><br/>' +
                '<a href="http://www.snc.ro" target="_blank">www.snc.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=2354869817928452687" target="_blank">View in Google Maps</a> </p>',44.16535950000001,28.640638599999992,6],
            ['<p>BOG\'ART STEEL<br/>' +
                '<a href="tel:+40311020584">+40 31 102 0584</a><br/>' +
                '<a href="http://www.bogartsteel.ro" target="_blank">www.bogartsteel.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=18049674615393230808" target="_blank">View in Google Maps</a> </p>',44.399295,26.202650100000028,8],
            ['<p>Orion Auto Invest<br/>' +
                '<a href="tel:+40248206046">+40 248 206 046</a><br/>' +
                '<a href="http://www.orioninvest.ro" target="_blank">www.orioninvest.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=15621900759773540680" target="_blank">View in Google Maps</a> </p>',44.9030672,24.8800812,9],
            ['<p>Girueta S.A.<br/>' +
                '<a href="tel:+40724263070">+40 724 263 070</a><br/>' +
                '<a href="https://maps.google.com/?cid=6499950528970730937" target="_blank">View in Google Maps</a> </p>',44.3532,26.084870000000024,10],
            ['<p>Apex Group<br/>' +
                '<a href="tel:+40268471199">+40 268 471 199</a><br/>' +
                '<a href="http://www.apexgroup.eu" target="_blank">www.apexgroup.eu</a><br/>' +
                '<a href="https://maps.google.com/?cid=5369292245690502378" target="_blank">View in Google Maps</a> </p>',45.6446595,25.51287709999997,11],
            ['<p>24 Ianuarie<br/>' +
                '<a href="tel:+40244526350">+40 244 526 350</a><br/>' +
                '<a href="http://www.24january.ro" target="_blank">www.24january.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=12453224036620608462" target="_blank">View in Google Maps</a> </p>',44.95236999999999,26.027279200000066,12],
            ['<p>Confind S.R.L.<br/>' +
                '<a href="tel:+40244333160">+40 244 333 160</a><br/>' +
                '<a href="http://www.confind.ro" target="_blank">www.confind.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=13661852919357465677" target="_blank">View in Google Maps</a> </p>',45.1259837,25.746811699999967,13],
            ['<p>Floris Steel Expert<br/>' +
                '<a href="tel:+40722288966">+40 722 288 966</a><br/>' +
                '<a href="http://www.florisconstruct.ro" target="_blank">www.florisconstruct.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=3853861083974633602" target="_blank">View in Google Maps</a> </p>',44.4056341,26.24015810000003,14],
            ['<p>MCM Steel S.A.<br/>' +
                '<a href="tel:+40255525378">+40 255 525 378</a><br/>' +
                '<a href="https://maps.google.com/?cid=16473618587588085865" target="_blank">View in Google Maps</a> </p>',45.37698,21.720370000000003,15],
            ['<p>KMM<br/>' +
                '<a href="tel:+35954892020">+359 54 892 020</a><br/>' +
                '<a href="http://www.kmmbg.com" target="_blank">www.kmmbg.com</a><br/>' +
                '<a href="https://maps.google.com/?cid=11864052640795868849" target="_blank">View in Google Maps</a> </p>',43.26377069999999,26.951991799999973,16],
            ['<p>Eurometal<br/>' +
                '<a href="tel:+40255207884">+40 255 207 884</a><br/>' +
                '<a href="https://maps.google.com/?cid=15832969344046872027" target="_blank">View in Google Maps</a> </p>',45.3007,21.882600000000025,17],
            ['<p>Lufkin Industries<br/>' +
                '<a href="tel:+40372499110">+40 372 499 110</a><br/>' +
                '<a href="https://maps.google.com/?cid=4359047727604121529" target="_blank">View in Google Maps</a> </p>',44.95026300000001,25.897939599999972,18],
            ['<p>Ghimbav-Brasov<br/>' +
                '<a href="https://maps.google.com/?cid=16190675625069179457" target="_blank">View in Google Maps</a> </p>',45.6669206,25.508837699999958,19],
            ['<p>Gormet<br/>' +
                '<a href="tel:+40728139820">+40 728 139 820</a><br/>' +
                '<a href="http://www.gormet.eu" target="_blank">www.gormet.eu</a><br/>' +
                '<a href="https://maps.google.com/?cid=508677087720209518" target="_blank">View in Google Maps</a> </p>',46.79757,23.61337000000003,20],
            ['<p>Cummins Generator Technologies Romania<br/>' +
                '<a href="tel:+40351443200">+40 351 443 200</a><br/>' +
                '<a href="https://maps.google.com/?cid=3009329757395496815" target="_blank">View in Google Maps</a> </p>',44.3102123,23.831728200000043,21],
            ['<p>Marub SA<br/>' +
                '<a href="tel:+40268334505">+40 268 334 505</a><br/>' +
                '<a href="http://www.marub.ro" target="_blank">www.marub.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=957084705649478110" target="_blank">View in Google Maps</a> </p>',45.666424,25.62963000000002,22],
            ['<p>Astra Vagoane<br/>' +
                '<a href="tel:+40257233651">+40 257 233 651</a><br/>' +
                '<a href="http://astra-passengers.ro" target="_blank">astra-passengers.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=1734135610132898503" target="_blank">View in Google Maps</a> </p>',46.192170000000004,21.331050000000005,23],
            ['<p>Colombo Dockyard PLC<br/>' +
                '<a href="tel:+94112429000">+94 11 2 429000</a><br/>' +
                '<a href="http://www.cdl.lk" target="_blank">www.cdl.lk</a><br/>' +
                '<a href="https://maps.google.com/?cid=17913869269697176492" target="_blank">View in Google Maps</a> </p>',6.956975000000001,79.85733270000003,24],
            ['<p>Westcon<br/>' +
                '<a href="http://www.westcon.no" target="_blank">www.westcon.no</a><br/>' +
                '<a href="https://maps.google.com/?cid=7417382028836231287" target="_blank">View in Google Maps</a> </p>',66.2736458,13.181237099999976,25],
            ['<p>Gemak Gemi İnşaat Sanayi<br/>' +
                '<a href="tel:+902165812300">+90 216 581 23 00</a><br/>' +
                '<a href="http://www.gemak.com" target="_blank">www.gemak.com</a><br/>' +
                '<a href="https://maps.google.com/?cid=15469401143269778084" target="_blank">View in Google Maps</a> </p>',40.837774,29.269271000000003,26],
            ['<p>BOS SHELF LLC<br/>' +
                '<a href="tel:+994124449922">+994 12 444 99 22</a><br/>' +
                '<a href="https://maps.google.com/?cid=14384929721774153387" target="_blank">View in Google Maps</a> </p>',40.2789058,49.668594100000064,27],
            ['<p>Moldocim S.A.<br/>' +
                '<a href="tel:+40233254221">+40 233 254 221</a><br/>' +
                '<a href="https://maps.google.com/?cid=12533363930594300355" target="_blank">View in Google Maps</a> </p>',46.90124,26.07278999999994,28],
            ['<p>Auto Dacia S.A.<br/>' +
                '<a href="tel:+40248219262">+40 248 219 262</a><br/>' +
                '<a href="https://maps.google.com/?cid=11693663952915527189" target="_blank">View in Google Maps</a> </p>',44.84793,24.879519999999957,29],
            ['<p>European Food S.A.<br/>' +
                '<a href="https://maps.google.com/?q=European+Food+S.A.&ftid=0x4748b5d2ef9754dd:0x8c269e97daf2f582" target="_blank">View in Google Maps</a> </p>',46.5410458,22.46382440000002,30],
            ['<p>Qualicaps<br/>' +
                '<a href="tel:+40372193200">+40 372 193 200</a><br/>' +
                '<a href="http://www.qualicaps.ro" target="_blank">www.qualicaps.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=13182170874267209826" target="_blank">View in Google Maps</a> </p>',44.49444219999999,26.001184500000022,31],
            ['<p>Gedeon Richter<br/>' +
                '<a href="tel:+40265268297">+40 265 268 297</a><br/>' +
                '<a href="http://www.gedeon-richter.ro" target="_blank">www.gedeon-richter.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=13056436207660768960" target="_blank">View in Google Maps</a> </p>',46.52437940000001,24.603854599999977,32],
            ['<p>Ursus Breweries<br/>' +
                '<a href="tel:+40238413962">+40 238 413 962</a><br/>' +
                '<a href="http://www.ursus-breweries.ro" target="_blank">www.ursus-breweries.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=4620441132905376892" target="_blank">View in Google Maps</a> </p>',45.16285939999999,26.788036799999986,33],
            ['<p>Alro<br/>' +
                '<a href="https://maps.google.com/?q=Alro&ftid=0x40ad381382205e1f:0x25c869784e82a9d3" target="_blank">View in Google Maps</a> </p>',44.44703010000001,24.388865500000065,34],
            ['<p>Oțelinox<br/>' +
                '<a href="tel:+40245209100">+40 245 209 100</a><br/>' +
                '<a href="http://www.otelinox.ro" target="_blank">www.otelinox.ro</a><br/>' +
                '<a href="https://maps.google.com/?cid=1329052170810744552" target="_blank">View in Google Maps</a> </p>',44.9069483,25.442419500000028,35],
            ['<p>Horizont Ivanov Ltd.<br/>' +
                '<a href="tel:+35964882862">+359 64 882 862</a><br/>' +
                '<a href="http://www.horizontivanov.com/wp/bg" target="_blank">www.horizontivanov.com</a><br/>' +
                '<a href="https://maps.google.com/?cid=5289896874188698894" target="_blank">View in Google Maps</a> </p>',43.40673770000001,24.619349199999988,36],
            ['<p>Efacec Capital, S.G.P.S., SA<br/>' +
                '<a href="tel:+351229562300">+351 22 956 2300</a><br/>' +
                '<a href="http://www.efacec.pt" target="_blank">www.efacec.pt</a><br/>' +
                '<a href="https://maps.google.com/?cid=9277452026195442992" target="_blank">View in Google Maps</a> </p>',41.1975191,-8.628504700000008,37],
            ['<p>Agility group AS<br/>' +
                '<a href="tel:+4722064901">+47 22 06 49 01</a><br/>' +
                '<a href="https://maps.google.com/?cid=14164686946558620302" target="_blank">View in Google Maps</a> </p>',59.17497219999999,10.213671100000056,38]
    ];

    var refIcon = new google.maps.MarkerImage('assets/images/map-marker.png', null, null, null, new google.maps.Size(25, 25));

    var mapref = new google.maps.Map(document.getElementById("mapref"), {
        zoom: 4,
        disableDefaultUI: true,
        scrollwheel: false,
        zoomControl: true,
        center: new google.maps.LatLng(45, 24),
        mapTypeId: "roadmap"
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: mapref,
            icon: refIcon
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }
}

//google.maps.event.addDomListener(window, 'load', initialize);

/*

*/
