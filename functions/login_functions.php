<?PHP

require_once('config.php');

function user_login(){
	if(empty($_POST['username']) || empty($_POST['password'])){
	
		$error = 'Email or Password is invalid';

	} else {

		$username = $_POST['username'];
		$password = $_POST['password'];

		//mysql connection
		global $db;
		
		$stmt = $db->prepare("SELECT email,password FROM users WHERE email= :email");
		$stmt->bindParam(':email',$username);
		$stmt->execute();
		
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$pass = $results[0]['password'];
		$user = $results[0]['email'];
		
		$password = hash('sha512',$username.$password);
	
		if($password === $pass){
			$_SESSION['login_user']=$username;
			$_SESSION['unique_key']=$password;
			$_SESSION['user_type']='user';

			//user logged in, redirect to members page
			header('location:index.php');
		} else {
			$error = 'Username or Password is invalid';
		}
	}
}

function truck_login(){
	if(empty($_POST['username']) || empty($_POST['password'])){
	
		$error = 'Email or Password is invalid';

	} else {

		$username = $_POST['username'];
		$password = $_POST['password'];

		//mysql connection
		global $db;
		
		$stmt = $db->prepare("SELECT email,password FROM truck_users WHERE email= :email");
		$stmt->bindParam(':email',$username);
		$stmt->execute();
		
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$pass = $results[0]['password'];
		$user = $results[0]['email'];
		
		$password = hash('sha512',$username.$password);
	
		if($password === $pass){
			$_SESSION['login_user']=$username;
			$_SESSION['unique_key']=$password;
			$_SESSION['user_type']='truck';

			//user logged in, redirect
			header('location:index.php');
		} else {
			$error = 'Username or Password is invalid';
		}
	}
}

function login(){
	//start session
	session_start();
	//error log
	$error='';

	if(isset($_POST['submit'])){
		
		switch($_POST['loginform']){
			case 'user':
				user_login();
				break;
			case 'truck':
				truck_login();
				break;
		}
	
	}

}

function logout(){

	session_start();
	if(session_destroy()){
		header('location:index.php');
	}
}

function checkSession(){

	global $db;
	
	session_start();
	//$check_username = $_SESSION['login_user'];
	//$check_unique_key = $_SESSION['unique_key'];
	//$type = $_SESSION['user_type'];
	
	if(!isset($_SESSION['login_user']) || !isset($_SESSION['unique_key'])){
		return false;
	}else{
		return true;
	}
	
/*	switch($type){
		case 'user':
			$stmt = $db->prepare("SELECT password FROM users WHERE email= :email");
			$stmt->bindParam(':email',$check_username);
			$stmt->execute();
			
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			if($results[0]['password']==$check_unique_key){
				return true;
			}else{
				return false;
			}
			break;
			
		case 'truck':
			$stmt = $db->prepare("SELECT password FROM truck_users WHERE email= :email");
			$stmt->bindParam(':email',$check_username);
			$stmt->execute();
			
			$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			if($results[0]['password']==$check_unique_key){
				return true;
			}else{
				return false;
			}
			break;
			
	}
*/
}

?>