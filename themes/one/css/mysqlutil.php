<?php 

// --- useful functions for mysql ---

function mysql_string_fix($s) {
	if(get_magic_quotes_gpc()) $s = stripcslashes($s);
	return mysql_real_escape_string($s);
}

?>