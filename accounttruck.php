<?PHP

echo('
<div class="container-fluid ">
    <div class="row">
<div class="col-xs-12 col-sm-12 col-md-12" id="user-login-form">
	<h1>Welcome to Your Account Profile</h1>');
echo('<h2>Subscription Status:</h2>');

$stmt = $db->prepare("SELECT active FROM truck_users WHERE email= :email");
$stmt->bindParam(':email',$_SESSION['login_user']);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$active = $results[0]['active'];
if($active == 0){
	echo('<p>In-Active</p>');
	echo('
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="WPL2HYM5HCMMG">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>

	');
}else if($active == 1){
	echo('<p>Active</p>');
}
$stmt = null;
$results = null;
if(isset($_POST['submit']) && isset($_POST['description'])){
	$description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
	
	$stmt = $db->prepare("UPDATE truck_users SET description= :description WHERE email= :email");
	$stmt->bindParam(':description', $description);
	$stmt->bindParam(':email',$_SESSION['login_user']);
	$stmt->execute();

	echo('<form action="account.php" method="POST" name="descript" id="descript">

<div class="container-fluid ">
    <div class="row">
		');

	echo('
		<div class="col-xs-12 col-sm-12 col-md-12 reduc">
		<div class="container-fluid ">
    <div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6 reduc">
		<textarea name="description" class="form-control"  rows="10" cols="75" form="descript">');
	echo $description;
	echo('</textarea>
		</textarea></div></div><br>
</div></div><br><br><br>');
	echo('
		<button type="submit" name="submit" class="btn btn-default">submit</button><br><br>');
	echo('
</div>
</div>
		</form>');
}else{
	$stmt = $db->prepare("SELECT description FROM truck_users WHERE email= :email");
	$stmt->bindParam(':email', $_SESSION['login_user']);
	$stmt->execute();

	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	echo('<form action="account.php" method="POST" id="description-form">
		<div class="container-fluid ">
    <div class="row">');
	echo('<div class="col-xs-12 col-sm-12 col-md-12 reduc">
		<div class="container-fluid ">
    <div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6 reduc">
		<textarea name="description" class="form-control"  rows="10" cols="75" form="description-form">');
	echo $results[0]['description'];
	echo('</textarea></div></div><br>
</div></div><br><br><br>');
	echo('<button type="submit" name="submit" class="btn btn-default">submit</button><br><br>');
	echo('
		</div>
</div>
		</form>');
}

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
	$stmt = $db->prepare("SELECT pass FROM truck_users WHERE pass= :pass");
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
		
		$stmt = $db->prepare("UPDATE truck_users SET pass= :new WHERE pass= :old");
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

<button type="submit" name="submit" id="password-change-button" class="btn btn-default">Change Password</button>

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