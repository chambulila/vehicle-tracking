@extends('index')

@section('content')
    <div id="map" style="height: 400px;"></div>
    <form action="{{ route('saveGeofence') }}" method="post">
        @csrf
        <input type="hidden" name="latitude" id="latitude" />
        <input type="hidden" name="longitude" id="longitude" />
        <button type="submit">Save Geofence</button>
    </form>
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBAIOtZ0eyv2XMb_F0ZgJqufRqfTxzZKtY&libraries=drawing&callback=initMap" async defer></script> --}}

    {{-- <script src="{{ asset('js/leaflet.js') }}"></script> --}}
    <script>
        var map = L.map('map').setView([0, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var marker;

        map.on('click', function (e) {
            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker(e.latlng).addTo(map);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });
    </script>
@endsection