<?PHP

require_once('../functions/config.php');
//get the input
$body = file_get_contents('php://input');
$body = json_decode($body, TRUE);

$username = $body['username'];
$password = $body['password'];

$hash = hash('sha512', $username.$password);

if(!isset($username) || !isset($password)){
	echo json_encode(array(
	'code' => 404,
	'msg' => 'Username & Password must be input'
	));
	exit;
}

try {
	
	global $db;
	
	//$db = new PDO("mysql:host=localhost;dbname=ft_main;charset=utf8mb4", "ft_auth", "TheAuthToDoAllThingsGreat");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	
	$stmt = $db->prepare("SELECT * FROM truck_users WHERE email= :email");
	$stmt->bindParam(':email', $username);
	$stmt->execute();
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	if($results[0]['pass'] === $hash ){
		
		if($results[0]['active'] === false)
		{
			echo json_encode(array(
			'code' => 404,
			'msg' => 'Subscription is inactive. Log in to http://foodieontherun.com to renew.'
			));
			exit;
		}
		$sessionID = hash('sha256',$username.date('i'));
		
		$stmt = $db->prepare("UPDATE truck_users SET sessionid= :sessionid, onoff= '1' WHERE email= :email");
		$stmt->bindParam(':sessionid', $sessionID);
		$stmt->bindParam(':email', $username);
		$stmt->execute();
		
		echo json_encode(array(
		'code' => 200,
		'msg' => $sessionID
		));
		exit;
	} else {
		echo json_encode(array(
		'code' => 404,
		'msg' => 'Email & Password mismatch'
		));
	}
	
}catch(PDOException $e){

}

?>