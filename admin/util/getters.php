<?php 

function get_sidebar() {
	include_once(AHOME . 'templates/sidebar.php');
}

function get_footer() {
	include_once(AHOME . 'templates/footer.php');
}

function get_header() {
	include_once(AHOME. 'templates/header.php');
}

function get_html_head() {
	include_once(AHOME . 'templates/htmlhead.php');
}

function get_styles_path_e() {
	echo(ROOT . 'admin/css/adminstylesheet.css');
}

function get_styles_path() {
	return ROOT . 'admin/css/adminstylesheet.css';
}

function get_script_libraries() {
	$p = AROOT . 'ascripts/lib/color_plugin.js';
	$q  = AROOT . 'ascripts/lib/jqueryui.min.js';
	echo
	"
	<script src=\"$p\"></script>
	<script src=\"$q\"></script>
	";
}

function get_html5shiv() {
	echo('	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->'."\n");
}

function get_jquery() {
	echo('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>'."\n");
}

function get_own_scripts() {
	$l = AROOT . 'ascripts/littlethings.js';
	echo
	"<script src=\"$l\"></script>
	";
}



?>