<?php 

include_once('./afunctions.php');
check_login();

$pagetitle = 'Edit Menu';
$message = '';

get_html_head();
start_body();
get_sidebar();
mainsection('s');
get_header();

//how many pages are there?
$stmt = $pdo->prepare("SELECT COUNT(*) FROM pages");
$stmt->execute();
$nopages = $stmt->fetch();

//deal with the request... re-order the menu
if(isset($_POST['reorder'])) {
	for($i = 1; $i <= $nopages[0]; $i++) {
		$stmt = $pdo->prepare("UPDATE pages SET no=? WHERE name=?");
		$r = $stmt->execute(array($i, $_POST[$i]));
	}
	if($r) $message .= p_wrap("Menu Re-Ordered");
	else $message .= p_wrap("Didn't Work... ");
}

//display all messages...
if($message !== '') {
	echo("<section class=\"changed\">$message</section>");
}

$stmt = $pdo->prepare("SELECT name, no FROM pages ORDER BY no ASC");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>
<section class="edit">
<p>Drag the boxes to the order in which you want them to appear.</p>
<ul id="menusorter">
<?php 
foreach($result as $item) {
	echo("<li class=\"sortmenu\">$item[name]</li>\n");
}
?>
</ul>
<form action="menu.php" method="post">
<input type="submit" value="Re-Order" name="reorder" id="reorderitems"/>
</form>
</section>

<?php
mainsection('e');

get_footer();

?>