<?PHP

require_once('../functions/config.php');

$body = file_get_contents('php://input');
$body = json_decode($body, TRUE);

$username = $body['username'];
$session = $body['sessionid'];
$long = $body['longitude'];
$lat = $body['latitude'];

if(!isset($username)){
	echo json_encode(array(
	'code' => 404,
	'msg' =>  'Email not included.'
	));
	exit;
}
if(!isset($session)){
	echo json_encode(array(
	'code' => 404,
	'msg' =>  'SessionID is blank'
	));
	exit;
}

if(!isset($long)){
	echo json_encode(array(
	'code' => 404,
	'msg' =>  'Longitude is blank.'
	));
	exit;
}

if(!isset($lat)){
	echo json_encode(array(
	'code' => 404,
	'msg' =>  'Latitude is blank.'
	));
	exit;
}

try {
	global $db;
    
	$hnd = $db->prepare("SELECT COUNT(*) FROM truck_users WHERE sessionid= :session");
	$hnd->bindParam(':session',$session);
	$hnd->execute();
	$rows = $hnd->fetchColumn(0);
	
	if($rows == 0){
		echo json_encode(array(
		'code' => 404,
		'msg' => 'Invalid sessionid'
		));
		exit;
	}
	$hnd = null;
	
	$stmt = $db->prepare("UPDATE truck_users SET longitude= :longitude, latitude= :latitude WHERE email= :email AND sessionid= :sessionid");
	
	$stmt->bindParam(':longitude', $long);
	$stmt->bindParam(':latitude', $lat);
	$stmt->bindParam(':email', $username);
	$stmt->bindParam(':sessionid', $session);
	$stmt->execute();
	
	echo json_encode(array(
	'code' => 200,
	'msg' => 'Complete'
	));
	
}catch(PDOException $e){
}

?>