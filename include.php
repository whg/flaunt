<?php 

//fill in $dr with the URL of the folder that the site will reside in
//or leave it blank if the site will be in the document root
//eg. $dr = ''; OR $dr = 'http://www.hello.com/sitename';
$dr = 'http://localhost/~WHG/flaunt'; 

//fill in database details...
define('DB_HOST', 'localhost');
define('DB_USER', 'will');
define('DB_PASS', 'kiss');
define('DB', 'playing');

//fill this in with the right details...
define('SITENAME', 'testsite');
define('THEMENAME', 'one');
define('AUTHOR', 'WHG');

/* some random characters to salt the passwords with...
   these can be whatever you want, but once you set them don't change them */

$salt1 = 'kdje';
$salt2 = 'akdfje';


// - - - - - - - - - - - - - - - - - - - - - - - - -


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


/*
load all the useful things in life,
but only if you aren't isn't in the admin area...
*/
if(!strpos($_SERVER['REQUEST_URI'], 'admin')) {
	include_once(HOME . 'includes/load.php');
}

//--- --- --- --- --- --- --- --- --- --- --- --- --- --- 


/* set up PDO instance */

$dsn = "mysql:host=".DB_HOST.";dbname=".DB;

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
}
catch(PDOException $e) {
    die("Could not connect to the database\n");
}

/* --- misc. --- */

date_default_timezone_set('UTC');

?>