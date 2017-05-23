<?PHP

function pop_header($title, $description){
	echo('
		<html>
		<head>
	');
	echo('<title>'.$title.'</title>');
	echo('
		<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
		<meta name="description" content="'.$description.'">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<link rel="stylesheet" href="css/bootstrap.css">
		 <style>

      #map {
        height: 75%;
		min-width: 100%;
		
      }
    </style>
		</head>
		<body> 
				 
				 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
		<div class="wrapper" id="wrapper">
	');
}

?>