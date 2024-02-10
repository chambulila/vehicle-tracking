@extends('index')
@section('content')
    <div class="container mt-5">
        
        <div id="map"></div>
    </div>

    {{-- <script type="text/javascript">
        function initMap() {
            const myLatLng = {
                lat: -6.776012 , lng: 39.178326
                // plat: parseFloat(data[0].latitude), lng: parseFloat(data[0].longitude) 
            };
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10
                , center: myLatLng
            , });

            const icon = {
              url: '{{asset("car.jpg")}}',
              scaledSize: new google.maps.Size(40, 20)
            };
            new google.maps.Marker({
                position: myLatLng, map,
                 title: 'gariiiii',
                 icon: icon
            , });

              // Call the function to update the map with real-time data
            getRealTimeData();

          // Refresh every 1 second
          setInterval(getRealTimeData, 6000);
        }

        window.initMap = initMap;

    </script> --}}

    <script type="text/javascript">
      function initMap() {
          // Initial map setup
          const map = new google.maps.Map(document.getElementById("map"), {
              zoom: 10,
              center: { lat: -6.776012, lng: 39.178326 }
          });
  
          const icon = {
              url: '{{asset("car.jpg")}}',
              scaledSize: new google.maps.Size(40, 20)
          };
  
          // Create a marker with initial position
          const marker = new google.maps.Marker({
              map,
              title: 'gari',
              icon: icon
          });
  
          // Function to update the marker position with real-time data
          function updateMarkerPosition(latitude, longitude) {
              const newLatLng = new google.maps.LatLng(latitude, longitude);
              marker.setPosition(newLatLng);
          }
  
          // Function to fetch real-time data from the API
          function getRealTimeData() {
              // Make an API request to get updated coordinates
              const apiUrl = 'https://chambulila.000webhostapp.com/getstate.php';
              fetch(apiUrl)
                  .then(response => response.json())
                  .then(data => {
                      // Assuming your API response has latitude and longitude properties
                      const { latitude, longitude } = data;
  
                      // Update the marker position with the new coordinates
                      console.log(data[longitude]);
                      updateMarkerPosition(parseFloat(data[0].latitude), parseFloat(data[0].longitude));
                  })
                  .catch(error => console.error('Error fetching real-time data:', error));
          }
  
          // Call the function to update the map with real-time data
          getRealTimeData();
  
          // Refresh every 1 second (1000 milliseconds)
          // setInterval(getRealTimeData, 9000);
      }
  
      window.initMap = initMap;
  </script>
  
    @endsection
{{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBAIOtZ0eyv2XMb_F0ZgJqufRqfTxzZKtY&libraries=drawing&callback=initMap" async defer></script> --}}
