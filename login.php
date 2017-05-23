<?PHP

$state = '';
$handle = '';

//user is already logged in - can't login again.
require_once('functions/session.php');
if($verify == true){
	header('location: index.php');
}

$err = array();

if(isset($_POST['submit'])){
	include(__DIR__."/functions/config.php");
	
	$email = $_POST['email'];
	$state = $_POST['state'];
	$password = $_POST['password'];
	
	//make sure no blank vars.
	if(empty($email) || empty($password)){
		$err[] = 'Email and password Required';
	}
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$err[] = 'Invalid email address';
	}
	if(strlen($password) < 6){
		$err[] = 'password too short. Minimum 6 characters';
	}
	
	//make sure user exists
	switch($state){
		case 'user':
			$stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email= :email");
			break;
		case 'truck':
			$stmt = $db->prepare("SELECT COUNT(*) FROM truck_users WHERE email= :email");
			break;
	}
	$stmt->bindParam(':email',$email);
	$stmt->execute();
		
	$result = $stmt->fetchColumn();
		
	if($result == 0){
		$err[] = 'Email address not found - Please register first.';
	}
	$result = null;
	$stmt = null;
	
	switch($state){
		case 'user':
			$stmt = $db->prepare("SELECT pass FROM users WHERE email= :email");
			break;
		case 'truck':
			$stmt = $db->prepare("SELECT pass FROM truck_users WHERE email= :email");
			break;
	}
	$stmt->bindParam(':email',$email);
	$stmt->execute();
	
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$password = hash('sha512',$email.$password);
	
	if($password !== $results[0]['pass']){
		$err[] = 'Email or password incorrect';
	}
	
	$results = null;
	$stmt = null;
	
	//no errors? Login.
	if(empty($err)){
		$_SESSION['login_user'] = $email;
		$_SESSION['unique_key'] = hash('sha256',$email);
		$_SESSION['user_type'] = $state;
		
		header('location: account.php');
		exit;
	}
}	
/*
** Below is the actual page/form.
**
*/
require_once('header.php');
$title = 'Login | Foodie on the Run';
$description = 'Login to access Favorites, or modify your account';
pop_header($title, $description);

//Determines whether or not user is logged in, and generates the appropriate menu response.
require_once('nav-bar.php');

echo('
	<div class="content-body" id="content-body">
	<div class="container-fluid ">
    <div class="row">
		<div class="col-xs-12 col-sm-6 col-md-6" id="user-login-form">
			<h2 id="user-login-form" class="user-login-form">User Login</h2><br>
');
if($state == 'user'){
if(isset($err)){
	foreach($err as $er){
		echo('<h3 id="user-login-error" class="user-login-error">'.$er.'</h3><br>');
	}
}
}
echo('
<form action="login.php" method="POST">
<div class="container-fluid ">
    <div class="row">

<input type="hidden" name="state" value="user">
<p id="user-login-form">Email</p>

<div class="col-xs-12 col-sm-8 col-md-6 reduc">
<input type="email" class="form-control" name="email"  id="user-login-form">
</div><br><br><br>

<p id="user-login-form">Password</p>

<div class="col-xs-12 col-sm-8 col-md-6 reduc">
<input type="password" class="form-control" name="password" id="user-login-form">
</div>

<br><br><br>
<button type="submit" name="submit" class="btn btn-default">Login</button>
<br><br>

</div>
</div>

</form>
</div>


');

echo('
		<div class="col-xs-12 col-sm-6 col-md-6" id="truck-login-form">
			<h2 id="user-login-form" class="user-login-form">Food Truck Login</h2><br>
');
if($state == 'truck'){
if(isset($err)){
	foreach($err as $er){
		echo('<h3 id="user-login-error" class="user-login-error">'.$er.'</h3><br>');
	}
}
}
echo('
<form action="login.php" method="POST">
<div class="container-fluid ">
<div class="row">

<input type="hidden" name="state" value="truck">
<p class="user-login-form" id="user-login-form">Email</p>

<div class="col-xs-12 col-sm-8 col-md-6 reduc">
<input type="email" name="email" class="form-control" id="user-login-form">
</div>

<br><br><br>
<p class="user-login-form" id="user-login-form">password</p>

<div class="col-xs-12 col-sm-8 col-md-6 reduc">
<input type="password" name="password" class="form-control" id="user-login-form"><br>
</div>

<br><br><br>
<button type="submit" name="submit" class="btn btn-default">Login</button>

</div>
</div>
</form>
</div>
</div>
</div>
</div>
');
require_once('footer.php');
?>