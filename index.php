<html>

<head>
    <style>
        #map {
            width: 100%;
            height: 300px;
        }

        .container1 {
            background-color: #f8f8f8;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            width: 100%;
            padding: 10px;
            max-width: 100%;
            min-height: 200px;
        }
    </style>
</head>

<body>
    <div class="card contact-card contdetselectbox">
        <div class="card-body adddoctforminputmainset">
            <div class="card_header_title">
                <h4 class="card-title">Google-map-get-tatitude-and-longitude-autocomplete-using-map</h4>
            </div>

            <div class="row form-row">
                <input type="hidden" class="form-control" id="lattitude" name="lattitude" value="" />
                <input type="hidden" class="form-control" id="longitude" name="longitude" value="" />

                <div class="col-md-12 col-sm-12">
                    <div class="container1 regi contmapditcoverset" id="container1">
                        <div class="form-top" style="height: 100%; width: 100%">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <p>Please select your address.</p>
                                    <input type="text" class="form-control" id="property_search" name="property_search" />
                                </div>
                                <div class="col-sm-12 col-md-12 mapcoverhtweset">
                                    <div id="map"></div>
                                </div>
                                <input type="hidden" class="form-control" id="full_property_address" name="full_property_address" value="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        marker = [];

        function initMap() {
            geocoder = new google.maps.Geocoder();
            var def_lat = '<?php echo DEFAULT_LAT; ?>';
            var def_long = '<?php echo DEFAULT_LONG; ?>';
            var map_zoom = '<?php echo MAP_ZOOM; ?>';
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: parseFloat(def_lat),
                    lng: parseFloat(def_long)
                },
                zoom: parseInt(map_zoom)
            });
            var input = document.getElementById('property_search');
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);
            marker = new google.maps.Marker({
                map: map,
                anchorPoint: new google.maps.Point(0, -29)
            });
            autocomplete.addListener('place_changed', function() {
                setMapOnAll(null);
                marker.setVisible(false);

                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    window.alert("No details availabel for input: '" + place.name + "'");
                    return;
                }
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);
                var address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].long_name || ''),
                        (place.address_components[1] && place.address_components[1].long_name || ''),
                        (place.address_components[2] && place.address_components[2].long_name || ''),
                        (place.address_components[3] && place.address_components[3].long_name || ''),
                        (place.address_components[4] && place.address_components[4].long_name || ''),
                        (place.address_components[5] && place.address_components[5].long_name || ''),
                        (place.address_components[6] && place.address_components[6].long_name || '')
                    ].join(' ');
                }
                console.log(place.address_components);
                $.each(place.address_components, function(index, value) {
                    if (value.types[0] == 'postal_code') {
                        $('#contact_pincode').val(value['long_name']);
                    } else if (value.types[0] == 'locality' || value.types[0] == 'administrative_area_level_3') {
                        $('#city').val(value['long_name']);
                        $('#contact_city').val(value['long_name']);
                    } else if (value.types[0] == 'administrative_area_level_1') {
                        $('#contact_state').val(value['long_name']);
                    } else if (value.types[0] == 'country') {
                        $('#country').val(value['long_name']);
                    } else if (value.types[0] == 'street_number') {
                        $('#street_number').val(value['long_name']);
                        $('#property_name').val(value['long_name']);
                    }
                    $('#full_property_address').val($('#property_search').val());

                    $('#contact_address').val($('#property_search').val());
                });
                $('#name_of_property_multi').val(place.address_components[0].long_name);
                $('#street').val(place.address_components[1].long_name);
                $('#lattitude').val(place.geometry.location.lat());
                $('#longitude').val(place.geometry.location.lng());
                var location = $('#state').val();
                if (location == '') {
                    $('#property_search').focus();
                    return false;
                }

            });
            google.maps.event.addListener(map, 'click', function(event) {
                var LatFind = event.latLng.lat();
                var LongFind = event.latLng.lng();
                $('#lattitude').val(LatFind);
                $('#longitude').val(LongFind);
                var myLatLng = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
                marker.setPosition(event.latLng);
                marker.setVisible(true);
                getAddress(event.latLng);
            });

        }

        function setMapOnAll(map) {
            for (var i = 0; i < marker.length; i++) {
                marker[i].setMap(map);
            }
        }

        function getAddress(latLng) {
            geocoder.geocode({
                    'latLng': latLng
                },
                function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var address = '';
                            if (results[0].address_components) {
                                address = [
                                    (results[0].address_components[0] && results[0].address_components[0].long_name || ''),
                                    (results[0].address_components[1] && results[0].address_components[1].long_name || ''),
                                    (results[0].address_components[2] && results[0].address_components[2].long_name || ''),
                                    (results[0].address_components[3] && results[0].address_components[3].long_name || ''),
                                    (results[0].address_components[4] && results[0].address_components[4].long_name || ''),
                                    (results[0].address_components[5] && results[0].address_components[5].long_name || ''),
                                    (results[0].address_components[6] && results[0].address_components[6].long_name || '')
                                ].join(' ');
                            }
                            $.each(results[0].address_components, function(index, value) {
                                if (value.types[0] == 'postal_code') {
                                    $('#contact_pincode').val(value['long_name']);
                                } else if (value.types[0] == 'locality' || value.types[0] == 'administrative_area_level_3') {
                                    $('#city').val(value['long_name']);
                                    $('#contact_city').val(value['long_name']);
                                } else if (value.types[0] == 'administrative_area_level_1') {
                                    $('#contact_state').val(value['long_name']);
                                } else if (value.types[0] == 'country') {
                                    $('#country').val(value['long_name']);
                                } else if (value.types[0] == 'street_number') {
                                    $('#street_number').val(value['long_name']);
                                    $('#property_name').val(value['long_name']);
                                }
                                $('#property_search').val(results[0].formatted_address);
                                $('#contact_address').val($('#property_search').val());
                            });
                            $('#name_of_property_multi').val(results[0].address_components[0].long_name);
                            $('#street').val(results[0].address_components[1].long_name);
                            $('#longitude').val(results[0].geometry.location.lat());
                            $('#longitude').val(results[0].geometry.location.lng());
                            var location = $('#state').val();
                            if (location == '') {
                                $('#property_search').focus();
                                return false;
                            }
                        } else {
                            console.log('No----');
                        }
                    } else {
                        console.log(status);
                    }
                });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_KEY; ?>&libraries=places&callback=initMap" async defer></script>
</body>

</html>