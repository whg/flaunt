<?php 

// ------ THEME SPECIFIC ------

function get_htmlhead() {
	include_once(HOME . 'themes/'. THEMENAME.'/htmlhead.php');
}

function get_sidebar() {
	include_once(HOME . 'themes/'. THEMENAME.'/sidebar.php');
}

function get_footer() {
	include_once(HOME . 'themes/'. THEMENAME.'/footer.php');
}

function get_header() {
	include_once(HOME . 'themes/'. THEMENAME.'/header.php');

}

function get_styles_path() {
	echo (ROOT."themes/".THEMENAME."/css/stylesheet.css");
}

function get_html5shiv() {
	echo('	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->'."\n");
}

function get_jquery() {
	echo('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>'."\n");
}

//finds all .js files in scripts folder...
// i like this
function get_scripts() {
	foreach(glob(HOME."themes/".THEMENAME."/scripts/*.js") as $file) {
		$p = strpos($file, 'themes');
		$newfile = substr($file, $p);
		echo('<script src='.ROOT.$newfile."></script>\n");
	}
}

function get_title(){
	global $pagetitle; 
	echo($pagetitle);
}

?>