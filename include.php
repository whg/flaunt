<?php 

//fill in $dr with the URL of the folder that the site will reside in
//or leave it blank if the site will be in the document root
//eg. $dr = ''; OR $dr = 'http://www.hello.com/sitename';
$dr = 'http://localhost/~WHG/flaunt'; 

//--- file definitions ---
define('HOME', dirname(__FILE__ ).'/'); 
define('AHOME', dirname(__FILE__ ). '/admin/'); 

if(!$dr) {
	define('ROOT', 'http://' . $_SERVER['SERVER_NAME'] . '/');
}
else {
	$dr .= '/';
	define('ROOT', $dr);
}
define('AROOT', ROOT . 'admin/');


// - - - - - - - - - - - - - - - - - - - - - - - - -

//load configuration file
include_once(HOME . 'config.php');

/*
load all the useful things in life,
but only if it isn't in the admin area...
*/
if(!strpos($_SERVER['REQUEST_URI'], 'admin')) {
	include_once(HOME . 'includes/load.php');
}


?>