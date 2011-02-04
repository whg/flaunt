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
//this is needed for all things...

$nopages = get_next_table_no("pages");

/*  - - - - - - - - ADD NEW MARKER - - - - - - - -  */

if(isset($_POST['addmarker']) && isset($_POST['markername'])) {
	$mname = $_POST['markername'];
	$pno = $nopages;
	$stmt = $pdo->prepare("INSERT INTO pages(no, name, type) VALUES(?,?,?)");
	$rr = $stmt->execute(array($pno, $mname, "marker"));
	//check if it worked
	if($rr) $message .= p_wrap("Marker Added");
	else $message .= p_wrap("Didn't Work... ");
}

/*  - - - - - - - -  DELETE MARKER - - - - - - - -  */
if(isset($_POST['deletemarker']) && isset($_POST['marker'])) {
	if($_POST['sure'] == 'true') {
		$stmt = $pdo->prepare("DELETE FROM pages WHERE name = ?");
		$rrr = $stmt->execute(array($_POST['marker']));
		//check if it worked	
		if($rrr) $message .= p_wrap("Marker Deleted");
		else $message .= p_wrap("Didn't Work... ");
	}
	else $message .= p_wrap("Tick the 'Are you sure' box...");
}

/*  - - - - - - - - REORDERING OF PAGES - - - - - - - -  */


//deal with the request... re-order the menu
if(isset($_POST['reorder'])) {
	for($i = 1; $i <= $nopages[0]; $i++) {
		$stmt = $pdo->prepare("UPDATE pages SET no=? WHERE name=?");
		$r = $stmt->execute(array($i, $_POST[$i]));
	}
	if($r) $message .= p_wrap("Menu Re-Ordered");
	else $message .= p_wrap("Didn't Work... ");
}

//get all things from pages tables
$stmt = $pdo->prepare("SELECT name, no FROM pages ORDER BY no ASC");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

//display all messages...
if($message !== '') {
	echo("<section class=\"changed\">$message</section>");
}

?>
<section class="edit">
<h3 class="expand">Order Menu</h3>
<div class="hide">
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
</div>
</section>

<section class="add">
<h3 class="expand">Add Marker</h3>
<div class="hide">
<form action="menu.php" method="post">
<label>Name: </label><input type="text" name="markername" /><br />
<input type="submit" value="Add" name="addmarker" />
</form>
</div>
</section>

<section class="delete">
<h3 class="expand">Delete Marker</h3>
<div class="hide">
<form action="menu.php" method="post">
<label>Select Marker: </label>
<select name="marker">
<?php 

$stmt = $pdo->prepare("SELECT name FROM pages WHERE type=? ORDER BY no DESC");
$stmt->execute(array("marker"));
$row = $stmt->fetchAll(PDO::FETCH_NUM);
foreach($row as $o) {
	$val = $o[0];
	echo "<option value=\"$val\">$val</option>\n";
}
?>
</select><br />
<label>Are you sure? </label>
<input type="checkbox" name="sure" value="true" /><br />
<input type="submit" value="Delete" name="deletemarker" />
</form>
</div>
</section>

<?php
mainsection('e');

get_footer();

?>