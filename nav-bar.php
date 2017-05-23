<?PHP
require_once('functions/session.php');
echo('


<nav class="navbar navbar-default ">
  <div class="container-fluid ">
    <!-- Brand and toggle get grouped for better mobile display -->



    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <form class="navbar-brand navbar-form " action="findtruck.php" method="GET">
      	<div class="container-fluid ">
      	<div class="row">
        <div class="form-group col-xs-8 col-sm-9 col-md-9">
          <input type="text" class="form-control" name="query" placeholder="Search">
          
        </div>
        <button type="submit" class="btn btn-default col-xs-4 col-sm-3 col-md-3">Search</button>
        </div>
        </div>
      </form>

    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-left">
        <li><a href="index.php">Home</a></li>
        <li><a href="findtruck.php">Find Trucks</a></li>


');


if($verify == false){
	echo '<li ><a href="register.php">Register</a></li>';
	echo '<li><a href="login.php">Login</a></li>';
}else{
	echo '<li><a href="account.php">My Account</a></li>';
	echo '<li><a href="logout.php">Logout</a></li>';
}
echo('

	<li><a href="about.php">About</a></li>
	</ul>
    </div><!-- /.navbar-collapse -->

  </div><!-- /.container-fluid -->
</nav>


');


?>
