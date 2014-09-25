<!DOCTYPE html>
<html>
<head>
	<title>Simple Map</title>
	<meta charset="utf-8">
	<script src="https://maps.googleapis.com/maps/api/js"></script>
	<script src="js/googlemap-styles.js"></script>
	<script src="js/jquery-2.1.1.js"></script>
	<script src="js/citygame.js"></script>
	<script>
		var map;
		
		function initialize() {
			map = new google.maps.Map(document.getElementById('map-canvas'), {
				zoom: 5,
				center: new google.maps.LatLng(45, 2),
				styles: googlemapstyles,
				draggable: false,
				disableDefaultUI: true,
				scrollwheel: false
			});
			
			new CityGame(map);
		}
		
		google.maps.event.addDomListener(window, 'load', initialize);
	</script>
	<style>
		#map-canvas {
			width: 400px;
			height: 500px;
		}
		.float {
			
		}
	</style>
</head>
<body>
	<div class="float">
		<div id="city-name"></div>
		<div id="map-canvas"></div>
	</div>
	<div class="float">
		<div id="score"></div>
	</div>
</body>
</html>