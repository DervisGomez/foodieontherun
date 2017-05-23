<?PHP
require_once('../functions/config.php');

//get the input
$body = file_get_contents('php://input');
$body = json_decode($body, TRUE);

$session = $body['sessionid'];

try {
	global $db;
	
	$stmt = $db->prepare("UPDATE truck_users SET onoff='0', sessionid=null, longitude=null, latitude=null WHERE sessionid= :session");
	$stmt->bindParam(':session', $session);
	$stmt->execute();
	
	echo json_encode(array(
	'code' => 200,
	'msg' => 'Logged out'
	));
}catch(PDOException $e){
}

?>