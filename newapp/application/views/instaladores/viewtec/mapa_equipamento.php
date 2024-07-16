<?php 
foreach ($eqp as $key) {
	$y = $key->Y;
	$x = $key->X;
} ?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<br>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4">
				<h4><i class="ion-android-list"></i> Informações do equipamento</h4><br>
				<?php foreach ($info_eq as $key): ?>
				<label><i class="ion-calendar"></i> DATA: <?php echo $key['data']?></label><br>
				<label><i class="ion-speedometer"></i> VELOCIDADE: <?php echo $key['velocidade']?></label><br>
				<label><i class="ion-toggle-filled"></i> IGNIÇÃO: <?php echo $key['ignicao']?></label><br>
				<label><i class="ion-android-warning"></i> PÂNICO: <?php echo $key['panico']?></label><br>
				<label><i class="ion-android-lock"></i> BLOQUEIO: <?php echo $key['bloqueio']?></label><br>
				<label><i class="ion-android-map"></i> GPRS: <?php echo $key['gprs']?></label><br>
				<label><i class="ion-android-pin"></i> GPS: <?php echo $key['gps']?></label><br>
				<label><i class="ion-magnet"></i> VOLTAGEM: <?php echo $key['voltagem']?></label><br>
				<label><i class="ion-thermometer"></i> ODOMETRO: <?php echo $key['odometro']?></label><br>
				<label><i class="ion-android-compass"></i> RPM: <?php echo $key['rpm']?></label><br>
			<?php endforeach ?>
		</div>
		<div class="col-md-8">
			<div class="card">
				<div class="card-body">
					<div id="map"></div>	
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	function initMap() {
		var y = <?php echo $y; ?>;
		var x = <?php echo $x; ?>;
		var loc = {lat: x, lng: y};
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 10,
			center: loc
		});
		var marker = new google.maps.Marker({
			position: loc,
			map: map
		});
	}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRJi-tdf4bN5px7r7MaQPqrTNQWyUiM9g&callback=initMap"
async defer></script>
</body>
<style>
#map {
	height: 400px;
	width: 100%;
}
</style>
</html>