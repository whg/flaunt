<?php 

// --- useful functions for mysql ---

//returns the current date in mysql format
function get_mysql_date() {
	return date( 'Y-m-d H:i:s');
}

//returns string a nice string for mysql... 
//... its used on the GET array... most of the time
function mysql_string_fix($s) {
	if(get_magic_quotes_gpc()) $s = stripcslashes($s);
	return mysql_real_escape_string($s);
}

//return the number + 1 of a selected table
function get_next_table_no($tablename) {	
	$n = mysql_query("SELECT * FROM $tablename;");
	return mysql_num_rows($n) + 1;
}

?>