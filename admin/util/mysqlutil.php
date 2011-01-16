<?php 

// --- useful functions for mysql ---


function get_mysql_date() {
	return date( 'Y-m-d H:i:s');
}

function mysql_string_fix($s) {
	if(get_magic_quotes_gpc()) $s = stripcslashes($s);
	return mysql_real_escape_string($s);
}

?>