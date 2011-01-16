<?php 

//fill in database details...
define('DB_HOST', 'localhost');
define('DB_USER', 'will');
define('DB_PASS', 'kiss');
define('DB', 'playing');

//fill this in with the right details...
define('SITENAME', 'thisisthesitename');
define('THEMENAME', 'one');
define('AUTHOR', 'WHG');

//--- --- --- --- --- --- --- --- --- --- --- --- --- --- 

//fill in $dr with the URL of the folder that the site will reside in
//or leave it blank if the site will be in the document root
//eg. $dr = ''; OR $dr = 'http://www.hello.com/sitename';
$dr = 'http://localhost/~WHG/flaunt'; 

//--- file definitions ---
if(!defined('HOME')) define('HOME', dirname(__FILE__ ).'/'); 
if(!defined('AHOME')) define('AHOME', dirname(__FILE__ ). '/admin/'); 

if(!$dr) {
	if(!defined('ROOT')) define('ROOT', 'http://' . $_SERVER['SERVER_NAME'] . '/');
}
else {
	$dr .= '/';
	if(!defined('ROOT')) define('ROOT', $dr);
}
if(!defined('AROOT')) define('AROOT', ROOT . 'admin/');

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


/* some random characters to salt the passwords with... */

$salt1 = 'kdje';
$salt2 = 'akdfje';


?>