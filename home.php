<?PHP

/*
** This page only loads the rest of the content. No need to edit here, edit individual sections instead.
*/

/*
** LIST OF ALL POSSIBLE SWITCH CASES FOR REFERENCE. DEFAULT SHOULD ALWAYS BE HOME.PHP
** VAR IS _GET['page'];
** HOME - home.php
** LOGIN/LOGOUT - logfunc.php
** SEARCH - search.php
** LOCATIONS - locations.php
** DEFAULT - home.php
*/

//Contains html header info - also executes the verifyLogin() function.
require_once('header.php');

//Contains the menu, search-bar, and banner as well.
require_once('menu.php');

//Produces the body - Just a giant switch statement.
require_once('body.php');

//contains the footer.
require_once('footer.php');


?>