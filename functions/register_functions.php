<?PHP

/*
****************NOTE****************
** $_POST VARS ARE MODIFIED IN REGISTER.PHP FOR VALDIATION PURPOSES; CHECK BEFORE
** MODIFYING VARIABLES FURTHER.
*/

require_once('config.php');

function register($state){
	global $db;
	
	switch($state){
		case 'user':
			$table = 'users';
			break;
		case 'truck':
			$table = 'truck_users';
			break;
	}
	if(isset($_POST['username']) && isset($_POST['password'])){
		$stmt = $db->prepare("SELECT COUNT(*) FROM :table WHERE email= :email");
		$stmt->bindParam(':table',$table);
		$stmt->bindParam(':email',$_POST['username']);
		$stmt->execute();
		
		$result = $stmt->fetchColumn();
		$stmt = null;
		$result = null;
		
		if($result >0){
			$err = 'An account already exists with that username';
			return $err;
		}
		
		if($_POST['password'] !== $_POST['password2']){
			$err = 'Your passwords must match.';
			return $err;
		}
		$password = hash('sha512',$_POST['username'].$_POST['password']);
		
		$stmt = $db->prepare("INSERT INTO :table (fname, lname, email, password, city, country) VALUES(:first, :last, :email, :password, :city, :country)");
		$stmt->bindParam(':table',$table);
		$stmt->bindParam(':first',$_POST['fname']);
		$stmt->bindParam(':last',$_POST['lname']);
		$stmt->bindParam(':email',$_POST['username']);
		$stmt->bindParam(':password',$password);
		$stmt->bindParam(':city',$_POST['city']);
		$stmt->bindParam(':country',$_POST['country']);
		
		$stmt->execute();
		
		return true;
	}else{
		$err = 'Username and Password must be input';
		return $err;
	}
}

?>