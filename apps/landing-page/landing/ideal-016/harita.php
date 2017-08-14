<?php
	require_once('Config.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple markers</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
		width: 100%;
        height: 100%;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>

function initMap() {
  var myLatLng = {lat: <?php echo $Map['coordinate1']; ?>, lng: <?php echo $Map['coordinate2']; ?>};

  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 8,
    center: myLatLng
  });

  var marker = new google.maps.Marker({
    position: myLatLng,
    map: map,
    title: 'Your Ideal Home is Here!',
	icon: '<?php echo $URL; ?>/assets/imgs/mapicon.png'
  });
}

    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo $Map['api_key']; ?>&signed_in=true&callback=initMap"></script>
  </body>
</html>