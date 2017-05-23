<?PHP

require_once('functions/session.php');
if($verify !== true){
	header('location:login.php');
	exit;
}

require_once('functions/config.php');

/*
** Build the page - two different states, user & truck.
*/
require_once('header.php');

$title = 'Welcome To Your Account';
$description = 'Modify your favorites.';
pop_header($title, $description);

//Determines whether or not user is logged in, and generates the appropriate menu response.
require_once('nav-bar.php');
echo('
	<div class="content-body" id="content-body">
');

switch($_SESSION['user_type']){
	case 'user':
		require_once('accountuser.php');
		break;
	case 'truck':
		require_once('accounttruck.php');
		break;
}
echo('
	</div>
');

require_once('footer.php');


?>