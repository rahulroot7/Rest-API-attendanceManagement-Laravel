@extends('layouts.app')

@push('style-scripts')
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
@endpush

@section('style')
    <style>
        #map {
            height: 500px;
            /*width: 100%;*/
            margin: 0px;
            padding: 0px
        }
    </style>
@endsection

@section('content')
    <div class="app-content">
        <div class="side-app">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <!-- Row -->

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Employee Track</h3>
                        </div>

                        <!-- show success and unsuccess message -->
                        @include('layouts.error_display')
                        <!-- End show success and unsuccess message -->

                        <div class="card-body">
                            {{--                            <div id="directions_panel"></div>--}}
                            <div id="map" style="border: 2px solid #3872ac;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js-scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABRrVCGnFHr6UT-ZvJIDNXr2N1cOR6wgQ"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
@endpush

@section('script')
    <script>
        const MapPoints = @json($allMapPoints);
        const RoutePoints = @json($routeMapPoints);

        console.log("---- MapPoints ----");
        console.log(MapPoints);

        console.log("---- RoutePoints ----");
        console.log(RoutePoints);

        var MY_MAPTYPE_ID = 'custom_style';
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService();
        var map;

        function getAddress(lat, lng) {
            const geocoder = new google.maps.Geocoder();
            const latlng = {
                lat: parseFloat(lat),
                lng: parseFloat(lng),
            };
            let address = "ghj";
            geocoder
                .geocode({ location: latlng })
                .then((response) => {
                    if (response.results[0]) {
                        address = response.results[0].formatted_address;
                    }
                    console.log("address "+ address);
                    return address;
                })
        }

        async function initialize() {
            directionsDisplay = new google.maps.DirectionsRenderer({
                suppressMarkers: true
            });

            if (jQuery('#map').length > 0) {
                var locations = MapPoints;

                map = new google.maps.Map(document.getElementById('map'), {
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    scrollwheel: true,
                    zoom: 14,
                });
                var infowindow = new google.maps.InfoWindow();
                var bounds = new google.maps.LatLngBounds();

                for (i = 0; i < locations.length; i++) {

                    let markerObj = {
                        position: new google.maps.LatLng(locations[i].address.lat, locations[i].address.lng),
                        map: map,
                        animation: google.maps.Animation.DROP,
                        title: locations[i].title,
                        time: locations[i].time
                    };

                    const status = ['check_in', 'check_out'];
                    if (locations[i].icon != '' && !status.includes(locations[i].icon)) {
                        markerObj.icon = {
                            path: 'm 12,2.4000002 c -2.7802903,0 -5.9650002,1.5099999 -5.9650002,5.8299998 0,1.74375 1.1549213,3.264465 2.3551945,4.025812 1.2002732,0.761348 2.4458987,0.763328 2.6273057,2.474813 L 12,24 12.9825,14.68 c 0.179732,-1.704939 1.425357,-1.665423 2.626049,-2.424188 C 16.809241,11.497047 17.965,9.94 17.965,8.23 17.965,3.9100001 14.78029,2.4000002 12,2.4000002 Z',
                            fillColor: '#5a9aed',
                            fillOpacity: 1.0,
                            strokeColor: '#000000',
                            strokeWeight: 1,
                            scale: 0.5,
                            anchor: new google.maps.Point(12, 24),
                        }
                    }

                    if (locations[i].icon == 'check_in') {
                        markerObj.icon = {
                            path: 'm 12,2.4000002 c -2.7802903,0 -5.9650002,1.5099999 -5.9650002,5.8299998 0,1.74375 1.1549213,3.264465 2.3551945,4.025812 1.2002732,0.761348 2.4458987,0.763328 2.6273057,2.474813 L 12,24 12.9825,14.68 c 0.179732,-1.704939 1.425357,-1.665423 2.626049,-2.424188 C 16.809241,11.497047 17.965,9.94 17.965,8.23 17.965,3.9100001 14.78029,2.4000002 12,2.4000002 Z',
                            fillColor: '#00FF00',
                            fillOpacity: 1.0,
                            strokeColor: '#000000',
                            strokeWeight: 1,
                            scale: 2,
                            anchor: new google.maps.Point(12, 24),
                        }
                        // markerObj.title = "Check In";
                    }
                    if (locations[i].icon == 'check_out') {
                        markerObj.icon = {
                            path: 'm 12,2.4000002 c -2.7802903,0 -5.9650002,1.5099999 -5.9650002,5.8299998 0,1.74375 1.1549213,3.264465 2.3551945,4.025812 1.2002732,0.761348 2.4458987,0.763328 2.6273057,2.474813 L 12,24 12.9825,14.68 c 0.179732,-1.704939 1.425357,-1.665423 2.626049,-2.424188 C 16.809241,11.497047 17.965,9.94 17.965,8.23 17.965,3.9100001 14.78029,2.4000002 12,2.4000002 Z',
                            fillColor: 'blue',
                            fillOpacity: 1.0,
                            strokeColor: '#000000',
                            strokeWeight: 1,
                            scale: 2,
                            anchor: new google.maps.Point(12, 24),
                        };
                        // markerObj.title = "Check Out";
                    }

                    marker = new google.maps.Marker(
                        markerObj
                    );

                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                            const geocoder = new google.maps.Geocoder();
                            const latlng = {
                                lat: parseFloat(locations[i].address.lat),
                                lng: parseFloat(locations[i].address.lng),
                            };
                            console.log("lat "+latlng.lat);
                            console.log("lng "+latlng.lng);
                            geocoder.geocode({ location: latlng })
                                .then((response) => {
                                    const contentString =
                                        '<div id="content">' +
                                        '<div id="siteNotice">' +
                                        "</div>" +
                                        '<h1 id="firstHeading" class="firstHeading">'+ marker.title +'</h1>' +
                                        '<div id="bodyContent">' +
                                        "<p><b>Address: </b>"+ response.results[0].formatted_address+"</p>" +
                                        "<p><b>Lat, Lng: </b>"+ latlng.lat+", "+ latlng.lng+"</p>" +
                                        "<p><b>Time: </b>"+ marker.time+"</p>" +
                                        "</div>" +
                                        "</div>";

                                    if (response.results[0]) {
                                        // infowindow.setContent(response.results[0].formatted_address);
                                        infowindow.setContent(contentString);
                                    }
                                })
                            infowindow.open(map, marker);
                        }
                    })(marker, i));

                    bounds.extend(marker.position);
                }

                map.fitBounds(bounds);
            }
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@endsection
