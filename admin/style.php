<?php 

include_once('./afunctions.php');
check_login();

$pagetitle = 'Edit Stylesheet';

get_html_head();
start_body();
get_sidebar();
mainsection('s');
get_header();

$stylespath = HOME . 'themes/' . THEMENAME . '/css/stylesheet.css';
$message = '';

if(isset($_POST['sheet']) && isset($_POST['changestylesheet'])) {
	$changed = $_POST['sheet'];
	$c = file_put_contents($stylespath, $changed);
	if($c) $message .= p_wrap("Stylesheet changed");
}

$stylesheet = file_get_contents($stylespath);
if($message !== '') {
	echo("<section class=\"changed\">$message</section>");
}

?>
<section class="edit">
<form action="style.php" method="post">
<textarea cols="20" rows="40" name="sheet">
<?php echo($stylesheet);?>
</textarea>
<input type="submit" name="changestylesheet" value="Change" />
</form>
</section>




<?php
mainsection('e');

get_footer();

?>