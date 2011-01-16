<?php 
include_once('afunctions.php');

$pagetitle = 'heelooo';

get_html_head();

start_body();
/* get_sidebar(); */

mainsection('s');

set_header('Login');

article('s');

?>

<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" title="login" >
	<input type="text" name="user" /> <br />
	<input type="password" name="pass" /> <br />
	<input type="submit" value="Login"/>
</form>		

<?php

session_start();

//do the log out
if(isset($_GET['s']) && $_GET['s'] == 'lo') {
	if(isset($_SESSION['status'])) {
		unset($_SESSION['status']);

		if(isset($_COOKIE[session_name()])) 
			setcookie(session_name(), '', time() - 5);
		
		session_destroy();
		
		echo(p_wrap("You have been logged out"));
	}

}

//do the log in
if(isset($_POST['user']) && isset($_POST['pass'])) {
			
	$u = $_POST['user'];
	$p = $_POST['pass'];
	$sp = $salt1 . $p . $salt2;
	$msp = md5($sp); //salted and encrypted password
		
	$stmt = $pdo->prepare("SELECT pass FROM users WHERE user = ?");
	$stmt->execute(array("$u"));	
	$row = $stmt->fetch(PDO::FETCH_NUM);
	
	if($row[0] == $msp) {
	
		$_SESSION['status'] = 'loggedIn';
		$_SESSION['user'] = $u;
		header("location: index.php");
		
	} else {
		echo(p_wrap("incorrect details, i'm afraid"));
	}
	
}

//if tried to get to restricted area...
if(isset($_GET['a']) && $_GET['a'] == 'n') echo(p_wrap("You must login to access that area."));


article('e');

mainsection('e');

get_footer();


?>

