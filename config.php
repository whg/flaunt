<?php 

//fill in database details...
define('DB_HOST', 'localhost');
define('DB_USER', 'will');
define('DB_PASS', 'kiss');
define('DB', 'playing');

//fill this in with the right details...
define('SITENAME', 'testsite');
define('THEMENAME', 'one');
define('AUTHOR', 'WHG');

/* some random characters to salt the passwords with... */

$salt1 = 'kdje';
$salt2 = 'akdfje';	

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