<html>
<head>
    <title>Directions Service</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <!-- playground-hide -->
    <style>
        html, body, #map {
            height: 500px;
            width: 500px;
            margin: 0px;
            padding: 0px
        }

    </style>
</head>
<body>

<div id="map" style="border: 2px solid #3872ac;"></div>
<div id="directions_panel"></div>

{{--<script--}}
{{--    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg&callback=initMap&v=weekly"--}}
{{--    defer--}}
{{--></script>--}}
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABRrVCGnFHr6UT-ZvJIDNXr2N1cOR6wgQ"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>


<script>
    var MapPoints = '[{"address":' +
        '{"address":"plac Grzybowski, Warszawa, Polska","lat":"30.709486497623683","lng":"76.68951298413609"},"title":"Warszawa"},' +
        '{"address":{"address":"Jana Paw\u0142a II, Warszawa, Polska","lat":"30.7110730653509","lng":"76.69172312444269"},"title":"Wroc\u0142aw"},' +
        '{"address":{"address":"Wawelska, Warszawa, Polska","lat":"30.700510661342307","lng":"76.71443592418963"},"title":"O\u015bwi\u0119cim"}]';

    var MY_MAPTYPE_ID = 'custom_style';
    var directionsDisplay;
    var directionsService = new google.maps.DirectionsService();
    var map;

    function initialize() {
        directionsDisplay = new google.maps.DirectionsRenderer({
            suppressMarkers: true
        });

        if (jQuery('#map').length > 0) {

            var locations = jQuery.parseJSON(MapPoints);

            map = new google.maps.Map(document.getElementById('map'), {
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: false
            });
            directionsDisplay.setMap(map);

            var infowindow = new google.maps.InfoWindow();
            var flightPlanCoordinates = [];
            var bounds = new google.maps.LatLngBounds();

            for (i = 0; i < locations.length; i++) {
                if(i != '1') {
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(locations[i].address.lat, locations[i].address.lng),
                        map: map
                    });
                }else{
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(locations[i].address.lat, locations[i].address.lng),
                        // map: map
                    });
                }
                    flightPlanCoordinates.push(marker.getPosition());
                    bounds.extend(marker.position);

                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                            infowindow.setContent(locations[i]['title']);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                // }
            }

            map.fitBounds(bounds);

            // directions service configuration
            var start = flightPlanCoordinates[0];
            var end = flightPlanCoordinates[flightPlanCoordinates.length - 1];
            var waypts = [];
            for (var i = 1; i < flightPlanCoordinates.length - 1; i++) {
                waypts.push({
                    location: flightPlanCoordinates[i],
                    stopover: true
                });
            }
            calcRoute(start, end, waypts);
        }
    }

    function calcRoute(start, end, waypts) {
        var request = {
            origin: start,
            destination: end,
            waypoints: waypts,
            optimizeWaypoints: true,
            travelMode: google.maps.TravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                var route = response.routes[0];
                var summaryPanel = document.getElementById('directions_panel');
                summaryPanel.innerHTML = '';
                // For each route, display summary information.
                for (var i = 0; i < route.legs.length; i++) {
                    var routeSegment = i + 1;
                    summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment + '</b><br>';
                    summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
                    summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
                    summaryPanel.innerHTML += route.legs[i].distance.text + '<br><br>';
                }
            }
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>



</body>
</html>
