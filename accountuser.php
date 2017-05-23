<?PHP

echo('
<div class="user-favorites" id="user-favorites">
<div class="container-fluid ">
    <div class="row">
<div class="col-xs-12 col-sm-12 col-md-12" id="user-login-form">

<h2>Favorites</h2><br>
<ul class="user-favorites" id="user-favorites">
');
//select the users favorited trucks from DB.
$stmt = $db->prepare("SELECT favorites FROM users WHERE email= :email");
$stmt->bindParam(':email', $_SESSION['login_user']);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$favorites = $result[0]['favorites'];

explode(',',$favorites);

$stmt = null;
$result = null;

if($favorites !== null){
	foreach($favorites as $fav){
		//select their ON/OFF status
		$stmt = $db->prepare("SELECT truckname,onoff FROM truck_users WHERE ID= :id");
		$stmt->bindParam(':id',$fav);
		$stmt->execute();
	
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if($results[0]['onoff'] == 1){
			$online = 'Online';
		}else{
			$online = 'Offline';
		}

		echo '<li class="user-fav-cell" id="user-fav-cell">'.$results[0]['truckname'].' is '.$online.'</li>';
	}
	
	$stmt = null;
	$results = null;
}
echo('</ul></div');

echo('<div class="password-change" id="password-change">');

if(isset($_POST['submit']) && isset($_POST['reset'])){
	$error = array();
	$old = $_POST['old'];
	$new = $_POST['new'];
	$new2 = $_POST['confirm'];
	
	if(empty($new) || empty($old) || empty($new2)){
		$error[] = 'All fields must be completed';
	}
	
	if(strlen($new) < 6){
		$error[] = 'Password must be minimum of 6 characters.';
	}
	
	if($new !== $new2){
		$error[] = 'New Passwords must match';
	}
	$pass = hash('sha512',$_SESSION['login_user'].$old);
	$stmt = $db->prepare("SELECT pass FROM users WHERE pass= :pass");
	$stmt->bindParam(':pass',$pass);
	$stmt->execute();
	
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if($results[0] === null){
		$error[] = 'Invalid password';
	}
	$stmt = null;
	$results = null;
	
	if(empty($error)){
		$hash = hash('sha512',$_SESSION['login_user'].$new);
		
		$stmt = $db->prepare("UPDATE users SET pass= :new WHERE pass= :old");
		$stmt->bindParam(':new',$hash);
		$stmt->bindParam(':old',$pass);
		$stmt->execute();
		
		echo '<h3 id="pass-change" class="pass-change">Password Changed Successfully</h3>';
	}else{
		foreach($error as $erro){
			echo('<h3 id="pass-change-error" class="pass-change-error">'.$erro.'</h3><br>');
		}
	}
}
echo('
<form action="account.php" method="POST">

<div class="container-fluid ">
    <div class="row">

<input type="hidden" name="reset" value="reset">
<p class="password-change" id="password-change">Old Password</p>
<div class="col-xs-12 col-sm-6 col-md-3 reduc">
<input type="password" name="old" class="form-control" id="password-change">
</div><br><br><br>

<p class="password-change" id="password-change">New Password</p>
<div class="col-xs-12 col-sm-6 col-md-3 reduc">
<input type="password" name="new" class="form-control" id="password-change">
</div><br><br><br>

<p class="password-change" id="password-change">Confirm Password</p>
<div class="col-xs-12 col-sm-6 col-md-3 reduc">
<input type="password" name="confirm" class="form-control" id="password-change">
</div><br><br><br>

<button type="submit" name="submit" class="btn btn-default">Change Password</button>
<br><br>
</div>
</div>

</form>

</div>
</div>
</div>
');
echo('</div>');
?>