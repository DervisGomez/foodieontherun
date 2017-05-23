<?PHP
session_start();
if(isset($_SESSION['login_user']) && $_SESSION['unique_key']==hash('sha256',$_SESSION['login_user'])){
	$verify = true;
}else{
	$verify = false;
}

?>