<?PHP

require_once('header.php');

$title = 'Foodie On The Run!';
$description = 'Find local food trucks, and feed your inner foodie while on the run!';
pop_header($title, $description);

//Determines whether or not user is logged in, and generates the appropriate menu response.
require_once('nav-bar.php');
echo('
	<div class="content-body" id="content-body">
	<div class="container-fluid ">
<div class="row">

<div class="col-xs-12 col-sm-12 col-md-12">
		<p><h1>Welcome</h1>
		This is the main body content</p>
		</div>

</div>
</div>
	</div>
');

require_once('footer.php');

?>