
<?php /* functions for the admin area */ 

include_once('../config.php');

include_once(AHOME . 'util/text.php');
include_once(AHOME. 'util/mysqlutil.php');
include_once(AHOME. 'util/getters.php');
include_once(AHOME. 'util/adding.php');


date_default_timezone_set('UTC');

function close_head() {
	echo("</head>\n");
}

function start_body() {
	echo("<body>\n<div id=\"container\">\n");
}

function close_page() {
	echo("</div><!-- close container -->\n</body>\n</hmtl>");
}

function get_meta() {
	echo("<meta charset=\"utf-8\">\n");
}

function mainsection($t) {
	switch($t) {
		case 's': echo("<div id=\"mainsection\">\n"); break;
		case 'e': echo("</div>\n"); break;
	}
}

function set_header($h){
	echo("<header><h1>$h</h1></header>\n");
}

function article($t) {
	switch($t) {
		case 's': echo("<article>\n"); break;
		case 'e': echo("</article>\n"); break;
	}
}


/* --- setters --- */

function set_title($t) {
	echo("<title>$t</title>\n");
}

/* --- combos --- */

function generic_head_nometa($t) {
	get_head();
	get_meta();
	set_title($t);
	get_styles();
	get_modern();
	close_head();
}

function check_login() {
	session_start();
	if($_SESSION['status'] != 'loggedIn') header('location: login.php?a=n');
}

?>