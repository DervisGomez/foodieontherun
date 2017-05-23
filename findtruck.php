<?PHP
require_once('header.php');
require_once('functions/config.php');
require_once('functions/session.php');
require_once('functions/search.php');

$title = 'Find Food Trucks!';
$description = 'Find local food trucks, and feed your inner foodie while on the run!';
pop_header($title, $description);

require_once('nav-bar.php');

echo('<div class="truck-content-body" id="truck-content-body">');
echo('<div class="truck-body" id="truck-body">');
//default location = city/country user is from.
if($verify == true && !isset($_GET['query'])){
	$stmt = $db->prepare("SELECT city,country FROM users WHERE email= :email");
	$stmt->bindParam(':email',$_SESSION['login_user']);
	$stmt->execute();
	
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$city = $results[0]['city'];
	$country = $results[0]['country'];
	
	$center = geocode_city($results[0]['city'],$results[0]['country']);
	$citylat = $center[0];
	$citylong = $center[1];
	$stmt = null;
	$results = null;
	
	$stmt = $db->prepare("SELECT truckname,longitude,latitude,description FROM truck_users WHERE city= :city AND country= :country AND onoff='1' AND active='1'");
	$stmt->bindParam(':city',$city);
	$stmt->bindParam(':country',$country);
	$stmt->execute();
	
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$i = 0;
	//Loaded all relevant info from the database Create and populate the map.
	echo('<div class="map" id="map">');
	
	echo('
	<script>
    var map;
    function initMap() {
		map = new google.maps.Map(document.getElementById(\'map\'), {
			center: {lat: '.$citylat.', lng: '.$citylong.'},
			zoom: 10
        });
	');
	foreach($results as $result){
		echo('
		var marker'.$i.' = new google.maps.Marker({
			position: {lat: '.$result['latitude'].', lng: '.$result['longitude'].'},
			map: map,
			title: \''.$result['truckname'].'\',
			label: \''.$result['truckname'].'\'
		});
		');
		
		$i++;
	}
	$i = 0;
	foreach($results as $result){
		echo('
		var contentString'.$i.' = \'<div id="content">\'+
            \'<div id="siteNotice">\'+
            \'</div>\'+
            \'<h1 id="firstHeading" class="firstHeading">'.$result['truckname'].'</h1>\'+
            \'<div id="bodyContent">\'+
            \'<p>'.$result['description'].'</p>\'+
            \'</div>\'+
            \'</div>\';
			
		var infowindow'.$i.' = new google.maps.InfoWindow({
          content: contentString'.$i.'
        });
		marker'.$i.'.addListener(\'click\', function() {
          infowindow'.$i.'.open(map, marker'.$i.');
        });
		');
	}
	
	echo('    }
	  
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key='.$maps_api.'&callback=initMap"
    async defer></script>
	');
	
	echo('</div>');
	
}else if($verify == false && !isset($_GET['query'])){
	echo('
<div class="container-fluid ">
      	<div class="row">
      	<div class="col-xs-0 col-sm-2 col-md-3"></div>
      	<div class="col-xs-12 col-sm-8 col-md-6">
	<h1>Find local food trucks!</h1>
	<h2>Search by City, Postal or Zip code!</h2>
	<br>
	<div class="container-fluid ">
      	<div class="row">
      	<div class="col-xs-0 col-sm-2 col-md-3"></div>

	<form class="col-xs-12 col-sm-8 col-md-6"  action="findtruck.php" method="GET">
          <input type="text" class="form-control" name="query">
          <br>
        <button type="submit" name="Find Now!" class="btn btn-default ">Enviar</button>
        
      </form>
      <div class="col-xs-0 col-sm-2 col-md-3"></div>
      </div>
        </div>
</div>
<div class="col-xs-0 col-sm-2 col-md-3"></div>
</div>
</div>
	');
	
}else if(isset($_GET['query'])){
	//get the city first.
	$results = new_search($_GET['query']);
	
	$city = array_shift($results);
	$country = array_shift($results);
	$results = array_shift($results);	
	
	$city = geocode_city($city, $country);
	$citylat = $city[0];
	$citylong = $city[1];
	
	$i = 0;
	//Loaded all relevant info from the database Create and populate the map.
	echo('<div class="container-fluid ">
      	<div class="row">
      	<div class="col-xs-0 col-sm-2 col-md-3"></div>
      	<div class="col-xs-12 col-sm-8 col-md-6">
		<div class="map" id="map">');
	
	echo('
	<script>
    var map;
    function initMap() {
		map = new google.maps.Map(document.getElementById(\'map\'), {
			center: {lat: '.$citylat.', lng: '.$citylong.'},
			zoom: 10
        });
	');
	foreach($results as $result){
		echo('
		var marker'.$i.' = new google.maps.Marker({
			position: {lat: '.$result['latitude'].', lng: '.$result['longitude'].'},
			map: map,
			title: \''.$result['truckname'].'\',
			label: \''.$result['truckname'].'\'
		});
		');
		
		$i++;
	}
	$i = 0;
	foreach($results as $result){
		echo('
		var contentString'.$i.' = \'<div id="content">\'+
            \'<div id="siteNotice">\'+
            \'</div>\'+
            \'<h1 id="firstHeading" class="firstHeading">'.$result['truckname'].'</h1>\'+
            \'<div id="bodyContent">\'+
            \'<p>'.$result['description'].'</p>\'+
            \'</div>\'+
            \'</div>\';
			
		var infowindow'.$i.' = new google.maps.InfoWindow({
          content: contentString'.$i.'
        });
		marker'.$i.'.addListener(\'click\', function() {
          infowindow'.$i.'.open(map, marker'.$i.');
        });
		');
	}
	
	echo('    }
	  
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key='.$maps_api.'&callback=initMap"
    async defer></script>
	');
	
	echo('</div></div>
		<div class="col-xs-0 col-sm-2 col-md-3"></div>
		</div></div>');
}

echo('</div>');
echo('</div>');

require_once('footer.php');

?>