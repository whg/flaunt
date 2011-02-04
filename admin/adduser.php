<?php 
include_once('./afunctions.php');

get_html_head();

start_body();

mainsection('s');

set_header('Add User');

article('s');

?>

	<form method="post" action="adduser.php">

		<input type="text" name="user" /> <br />
		<input type="password" name="pass" /> <br />
		<input type="submit" value="Add" />

	</form>



<?php

//get post variables and insert into mysql table using thedetails.php
//pasword is encrypted using MD5 with salts from thedetails.php

if(isset($_POST['user']) && isset($_POST['pass'])) {


	$u = $_POST['user'];
	$p = $_POST['pass'];
	//salt passord
	$p = $salt1 . $p . $salt2;
	$p = md5($p);
		
	$db_server = mysql_connect(DB_HOST, DB_USER, DB_PASS);
	
	if(!$db_server) die("Unable to connect" . mysql_error());
	
	mysql_select_db(DB) or die("Unable to select database: " . mysql_error());
	
	$u = mysql_string_fix($u);
	
	$a = "INSERT INTO users(user, pass) values('$u', '$p')";
			
	mysql_query($a) or die('Unable to add: ' . mysql_error());
	echo(p_wrap("user $u  added"));

}


article('e');

mainsection('e');

get_footer();


?>