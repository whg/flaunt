<?php 

include_once('./afunctions.php');
check_login();

$type = '';
if(isset($_GET['type'])) {
	$type = mysql_string_fix($_GET['type']);
}

$name = '';
if(isset($_GET['name'])) {
	$name = mysql_string_fix($_GET['name']);
}

$pagetitle = 'Edit ' . $name;

get_html_head();
start_body();
get_sidebar();
mainsection('s');
get_header();

//this variable is used to display results
$message = '';

// - - - - - - - - - - - - - - - - - - - - - - - - - -


/* ------ HOMEPAGE ------ */

if($name == 'homepage') { ?>

<?php 
/* --- add new homepage script --- */

$uploadDir = HOME . 'content/homepages/';

if(isset($_POST['uploadhomepage']) && isset($_POST['name'])) {
	
	$fileName = $_FILES['homepagefile']['name'];
	$tmpName = $_FILES['homepagefile']['tmp_name'];
	$fileSize = $_FILES['homepagefile']['size'];
	$fileType = $_FILES['homepagefile']['type'];
	
	$filePath = $uploadDir . $fileName;
	
	$result = move_uploaded_file($tmpName, $filePath);
	if (!$result && $_POST['homepagetype'] !== 'manual') {
		$message .= "Error uploading, are you sure you chose a something to upload?";
		
	}
	else {
			
		//get names
		$name = $_POST['name'];
		$date = get_mysql_date();
		$info = format_htmlp($_POST['info']);
		$filetype = $_POST['homepagetype'];
		
		if($filetype == 'manual') {
			$fileName = $name . '.php';
			$filetype = 'page';
			file_put_contents($uploadDir.$fileName, $_POST['manualpage']);
		}
		
		$path = 'content/homepages/' . $fileName;
		
		$stmt = $pdo->prepare("SELECT COUNT(*) FROM homepage");
		$stmt->execute();
		$currentno = $stmt->fetch(PDO::FETCH_NUM);
		$no = $currentno[0] + 1;
			
		//enter details into database
		$stmt = $pdo->prepare("INSERT INTO homepage(no, name, date, info, path, type) VALUES(?,?,?,?,?,?)");
		$result = $stmt->execute(array($no, $name, $date, $info, $path, $filetype));	
		
		if(!$result) $message .= "Database Error";
		else $message .= p_wrap("Homepage changed successfully to " . '<b>' . $name . '</b>');
	}
}

/* --- delete homepage --- */

$deleting = 0;
if(isset($_POST['delete']) && isset($_POST['page']) && isset($_POST['sure'])) {
	if($_POST['delete'] == 'Delete' && $_POST['sure'] ==  'true') {
		$deleting = 1;
		$post = $_POST['page'];
		
		//find path to image/page
		$stmt = $pdo->prepare("SELECT path FROM homepage WHERE name=?");
		$stmt->execute(array($post));
		$result = $stmt->fetch(PDO::FETCH_NUM);
		
		//delete file
		$un = unlink(HOME . $result[0]);
		
		//delete row from homepage table
		$stmt = $pdo->prepare("DELETE FROM homepage WHERE name=?");
		$r = $stmt->execute(array($post));
		
		//reorder table
		$stmt = $pdo->prepare("SET @num = 0; UPDATE homepage SET no= (SELECT @num := @num + 1)");
		$rr = $stmt->execute();
		
		if($r) $message .= p_wrap("Homepage <b>$post</b> has been deleted. ");
		else $message .= p_wrap("Homepage was not deleted, an error occured.");
		if($un) $message .= p_wrap("File deleted");
		if($rr && $r && $un) $message .= p_wrap("<b>All OK</b>");
		else $message .= "Re-ordering failed... this is kinda bad...";
	}
}

if(!isset($_POST['sure']) && isset($_POST['delete'])) $message.= 'Tick the "Are you sure?" box';


//display all messages...
if($message !== '') {
	echo("<section class=\"changed\">$message</section>");
}

?>



<section class="add">
<h3 class="expand">Change Homepage</h3>
<div class="hide">
<form action="edit.php?name=homepage" method="post" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
<label>Name: </label><input type="text" name="name" /><br />
<label>Type: </label><select name="homepagetype" id="hpselect">
	<option value="-">-</option>
	<option value="image">Image</option>
	<option value="page">Page</option>
	<option value="manual">Manual</option>
</select><br />
<div id="choosefile" class="hide"><label>File: </label><input type="file" name="homepagefile" /><br /></div>
<div id="domanual" class="hide"><label>Manual: </label><br/>
<textarea cols="20" rows="10" name="manualpage"></textarea><br /></div>
<label>Info: </label><br />
<textarea cols="10" rows="10" name="info"></textarea><br />
<input type="submit" name="uploadhomepage" value="Upload" class="button"/>
</form> 
</div>
</section>

<section class="delete">
<h3 class="expand">Delete a Homepage</h3>
<div class="hide">
<form action="edit.php?name=homepage" method="post">
<label>Select post: </label>
<select name="page">
<?php 

$stmt = $pdo->prepare("SELECT name FROM homepage ORDER BY no DESC");
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_NUM);
foreach($row as $o) {
	$val = $o[0];
	echo "<option value=\"$val\">$val</option>\n";
}
?>

</select><br />
<label>Are you sure? </label>
<input type="checkbox" name="sure" value="true" /><br />
<input type="submit" name="delete" value="Delete" />
</form>
</div>
</section>
<?php } // * * * * * * * * * * end if homepgage * * * * * * * * * * 

?>

<?php 


// - - - - - - - - - - - - - - - - - - - - - - - - - -



// ------- PAGE --------

// * * * * * * * edit page script * * * * * * * 
if($type == 'page') {
$contentpath = HOME . 'content/data/' . $name . '.html';
$rw = 0;
if(isset($_POST['content']) && isset($_POST['change'])) {
/* 	$newcontent = $_POST['content']; */
	$rw = file_put_contents($contentpath, $_POST['content']);
	
	if($rw) $message .= p_wrap("File re-written");
	
	if($message !== '') {
	echo("<section class=\"changed\">$message</section>");
	}
}
$oldcontent = file_get_contents($contentpath);
?>
<section class="edit">
<h3 class="expand">Edit the HTML: </h3>
<div class="hide">
<form action="edit.php?name=<?php echo($name)?>&type=<?php echo($type)?>" method="post">
<textarea cols="10" rows="30" name="content">
<?php echo($oldcontent); ?>
</textarea><br />
<input type="submit" name="change" value="Change" />
</form>
</div>
</section>

<?php 
} // * * * * * * * * * * END PAGE * * * * * * * * * * 


// - - - - - - - - - - - - - - - - - - - - - - - - - -


/* - - - - - - - - GALLERY - - - - - - - - - */

if($type == 'gallery') {

/* --- add new photo script --- */

$uploadDir = HOME . 'content/images/';

if(isset($_POST['uploadimage']) && isset($_POST['name'])) {
	
	$fileName = $_FILES['newphoto']['name'];
	$tmpName = $_FILES['newphoto']['tmp_name'];
	$fileSize = $_FILES['newphoto']['size'];
	$fileType = $_FILES['newphoto']['type'];
	
	$filePath = $uploadDir . $fileName;
	
	$result = move_uploaded_file($tmpName, $filePath);
	
	//now do the database stuff
	if (!$result) {
		$message .= p_wrap("Error uploading, are you sure you chose a something to upload?");
		
	}
	else {
		//get names
		$photoname = $_POST['name'];
		$caption = $_POST['caption'];
		$path = 'content/photo/' . $fileName;
		
		$stmt = $pdo->prepare("SELECT COUNT(*) FROM $name");
		$stmt->execute();
		$currentno = $stmt->fetch(PDO::FETCH_NUM);
		$no = $currentno[0] + 1;
			
		//enter details into database
		$stmt = $pdo->prepare("INSERT INTO $name(no, name, file, caption) VALUES(?,?,?,?)");
		$result = $stmt->execute(array($no, $photoname, $fileName, $caption));	
		
		if(!$result) $message.= p_wrap("Database Error, photo not added");
		else $message.= p_wrap("<b>$photoname</b> has been added to <b>$name</b>");
	}
}

// * * * * * * * edit gallery intro script * * * * * * * 

$contentpath = HOME . 'content/data/' . $name . '.html';
$rw = 0;
if(isset($_POST['content']) && isset($_POST['change'])) {
	$rw = file_put_contents($contentpath, $_POST['content']);
	if($rw) $message.= p_wrap("File re-written");
}
$oldcontent = file_get_contents($contentpath);

/* --- delete photo script --- */
$delphoto = 0;
if(isset($_POST['deletephoto']) && isset($_POST['photo']) && isset($_POST['sure'])) {
	if($_POST['deletephoto'] == 'Delete' && $_POST['sure'] ==  'true') {
		$delphoto = 1;
		$post = $_POST['photo'];
		
		$stmt = $pdo->prepare("DELETE FROM $name WHERE name=?");
		$r = $stmt->execute(array($post));
	
		$stmt = $pdo->prepare("SET @num = 0; UPDATE homepage SET no= (SELECT @num := @num + 1)");
		$rr = $stmt->execute();
		
		if($r) $message.= p_wrap("Photo <b>$post</b> has been deleted. ");
		else $message.= p_wrap("Photo was not deleted, an error occured.");
		if($rr) $message.= p_wrap("<b>All OK</b>");
		else $message.= p_wrap("Re-ordering failed... this is kinda bad...");
	}
}

if(!isset($_POST['sure']) && isset($_POST['deletephoto'])) $message.= p_wrap('Tick the "Are you sure?" box');

//show all messages
if($message !== '') {
	echo("<section class=\"changed\">$message</section>");
}

?>

<section class="add">
<h3 class="expand">Add New Photo</h3>
<div class="hide">
<form action="edit.php<?php echo("?type=$type&name=$name")?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<label>Name: </label>
<input type="text" name="name" /><br />
<label>File: </label><input type="file" name="newphoto" /><br />
<label>Caption: </label><br />
<textarea cols="10" rows="2" name="caption"></textarea>
<input type="submit" value="Upload" name="uploadimage" />
</form>
</div>
</section>


<section class="edit">
<h3 class="expand">Edit Intro</h3>
<div class="hide">
<form action="edit.php?name=<?php echo($name)?>&type=<?php echo($type)?>" method="post">
<textarea cols="10" rows="10" name="content">
<?php echo($oldcontent); ?>
</textarea><br />
<input type="submit" name="change" value="Change" />
</form>
<?php ?>
</div>
</section>


<section class="delete">
<h3 class="expand">Delete a Photo</h3>
<div class="hide">
<form action="edit.php?type=<?php echo($type)?>&name=<?php echo($name)?>" method="post">
<label>Select a Photo: </label>
<select name="photo">
<?php 
//get all photos in gallery...
$stmt = $pdo->prepare("SELECT name FROM $name ORDER BY no DESC");
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_NUM);
foreach($row as $o) {
	$val = $o[0];
	echo "<option value=\"$val\">$val</option>\n";
}
?>

</select><br />
<label>Are you sure? </label>
<input type="checkbox" name="sure" value="true" /><br />
<input type="submit" name="deletephoto" value="Delete" />
</form>
</div>
</section>

<?php 
} // * * * * * * * * * * END GALLERY * * * * * * * * * * 


// - - - - - - - - - - - - - - - - - - - - - - - - - -



// * * * * * * * * * * * * SHOWCASE * * * * * * * * * * * *

if($type == 'showcase') {

/* --- add new showcase item script --- */

$uploadDir = HOME . "$name/data/";

if(isset($_POST['addnewshowcase']) && isset($_POST['name'])) {
	
	$fileName1 = $_FILES['smallimage']['name'];
	$tmpName1 = $_FILES['smallimage']['tmp_name'];
	$fileSize1 = $_FILES['smallimage']['size'];
	$fileType1 = $_FILES['smallimage']['type'];
	
	$fileName2 = $_FILES['headerimage']['name'];
	$tmpName2 = $_FILES['headerimage']['tmp_name'];
	$fileSize2 = $_FILES['headerimage']['size'];
	$fileType2 = $_FILES['headerimage']['type'];
	
	$filePath1 = $uploadDir . $fileName1;
	$result1 = move_uploaded_file($tmpName1, $filePath1);
	
	$filePath2 = $uploadDir . $fileName2;
	$result2 = move_uploaded_file($tmpName2, $filePath2);
	
	//now do the database stuff
	if (!$result1 && !$result2) {
		$message .= p_wrap("Error uploading, are you sure you chose a something to upload?");
		
	}
	else {
		
		//get names
		$item = $_POST['name'];
		$summary = $_POST['summary'];
		$page = $_POST['page'];
		$smallimagepath = $fileName1;
		$headerimagepath = $fileName2;
		
		$stmt = $pdo->prepare("SELECT COUNT(*) FROM $name");
		$stmt->execute();
		$currentno = $stmt->fetch(PDO::FETCH_NUM);
		$no = $currentno[0] + 1;
			
		//enter details into database
		$stmt = $pdo->prepare("INSERT INTO $name(no, name, summary, smallimage, headerimage) VALUES(?,?,?,?,?)");
		$result = $stmt->execute(array($no, $item, $summary, $smallimagepath, $headerimagepath));	
		
		if(!$result) $message .= p_wrap("Database Error, item not added");
		else $message .= p_wrap("<b>$item</b> has been added to $name");
	
		//create page
		$dest = HOME . "$name/$item.php";	
		$ffh = fopen($dest, 'w');
		$c2 = fwrite($ffh, add_showcase_page($item, $name));
		fclose($ffh);
		chmod($dest, 0774);
		if($c2) $message .= p_wrap("Page created");
		
		//create data file, this is a php file so it has the ability to do fun stuff
		$dest = $uploadDir . "$item.php";	
		$ffh2 = fopen($dest, 'w');
		$c3 = fwrite($ffh2, $page);
		fclose($ffh2);
		chmod($dest, 0774);
		if($c3) $message .= p_wrap("Data file created");
		
		$message .= p_wrap("Both images added");
		
		if($c2 && $result && $c3) $message .= p_wrap("<b>All OK</b>");

	}
	
}

// * * * * * * * edit showcase intro script * * * * * * * 
$contentpath = HOME . 'content/data/' . $name . '.html';
$rw = 0;
if(isset($_POST['content']) && isset($_POST['changeshowcaseintro'])) {
	$rw = file_put_contents($contentpath, $_POST['content']);
	if($rw) $message .= p_wrap("File re-written");
}
$oldcontent = file_get_contents($contentpath);


/* --- delete showcase item script --- */

if(isset($_POST['deleteshowcaseitem']) && isset($_POST['item']) && isset($_POST['sure'])) {
	if($_POST['deleteshowcaseitem'] == 'Delete' && $_POST['sure'] ==  'true') {
		$delitem = 1;
		$item = $_POST['item'];
		
		$stmt = $pdo->prepare("DELETE FROM $name WHERE name=?");
		$r = $stmt->execute(array($item));
		
		//reorder no column in table
		$stmt = $pdo->prepare("SET @num = 0; UPDATE homepage SET no= (SELECT @num := @num + 1)");
		$rr = $stmt->execute();
		
		if($r && $rr) $message .= p_wrap("<b>$item</b> has been deleted from <b>$name</b> table");
		
		//delete file in showcase folder
		$u = unlink(HOME . "$name/$item.php");
		if($u) $message .= p_wrap("<b>$item</b> page has been deleted");
		
		if($u && $r && $rr) $message .= p_wrap("<b>All OK</b>");
	}
}

if(!isset($_POST['sure']) && isset($_POST['deleteshowcaseitem'])) $message .= p_wrap('Tick the "Are you sure?" box');

/*  - - - - - - edit showcase item - - - - - - - */
if(isset($_POST['commititemedit']) && isset($_POST['item'])) {
	$no = mysql_string_fix($_POST['no']); 
	$item = mysql_string_fix($_POST['item']);
	$summary = mysql_string_fix($_POST['summary']);
	$page = $_POST['page'];
	
	$stmt = $pdo->prepare("UPDATE $name SET name=?, summary=? WHERE no=?");
	$r = $stmt->execute(array($item, $summary, $no));
	
	$dest = HOME . $name . "/data/$item.php";
	$rr = file_put_contents($dest, $page);
	
	if($r) $message .= p_wrap("Database updated");
	if($rr) $message .= p_wrap("Page file updated");
	if($r && $rr) $message .= p_wrap("<b>All OK</b>");
}

//show all messages
if($message !== '') {
	echo("<section class=\"changed\">$message</section>");
}

?>
<section class="add">
<h3 class="expand">Add New Item</h3>
<div class="hide">
<form action="edit.php<?php echo("?type=$type&name=$name")?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<label>Name: </label>
<input type="text" name="name" /><br />
<label>Small Image: </label><input type="file" name="smallimage" /><br />
<label>Header Image: </label><input type="file" name="headerimage" /><br />
<label>Summary: </label><br />
<textarea cols="10" rows="5" name="summary"></textarea><br />
<label>Page: </label><br />
<textarea cols="10" rows="20" name="page"></textarea><br />
<input type="submit" value="Add" name="addnewshowcase" />
</form>
</div>
</section>

<section class="edit">
<h3 class="expand">Edit an Item</h3>
<div class="<?php 
//show this section if chosen to edit, otherwise it collapses and we don't want that
if(isset($_POST['showsection']) && $_POST['showsection'] == 'true') {
	echo('');
}
else {
	echo('hide');
}
?>">
<form action="edit.php?type=<?php echo($type)?>&name=<?php echo($name)?>" method="post">
<input type="hidden" name="showsection" value="true" />
<label>Select an Item: </label>
<select name="item">
<?php 
//get all entries ...
$stmt = $pdo->prepare("SELECT name FROM $name ORDER BY no DESC");
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_NUM);
foreach($row as $o) {
	$val = $o[0];
	echo "<option value=\"$val\">$val</option>\n";
}
?>

</select><br />
<input type="submit" name="editshowcaseitem" value="Edit" />
</form>

<?php 
//now do the form for the editing
if(isset($_POST['showsection']) && $_POST['showsection'] == 'true') {

	$item = $_POST['item'];
	
	//get database info
	$stmt = $pdo->prepare("SELECT * FROM $name WHERE name=?");
	$stmt->execute(array($item));
	$rowo = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$row = $rowo[0];
	
	//get data file contents
	$dest = HOME . $name . "/data/$item.php";
	$filec = file_get_contents($dest);

	//now do the form....
?>
<form action="edit.php?type=<?php echo($type)?>&name=<?php echo($name)?>" method="post">
<input type="hidden" name="no" value="<?php echo($row['no'])?>"
<label>Item Name: </label>
<input type="text" name="item" value="<?php echo($row['name'])?>" /><br />
<label>Summary: </label><br />
<textarea name="summary" cols="20" rows="5"><?php echo(stripcslashes($row['summary']))?></textarea><br />
<label>Page: </label><br />
<textarea name="page" cols="20" rows="20"><?php echo($filec)?></textarea><br />
<input type="submit" value="Commit Change" name="commititemedit"/>
</form>

<?php
//end if editing item...
}
?>
</div>
</section>

<section class="edit">
<h3 class="expand">Edit Intro</h3>
<div class="hide">
<form action="edit.php?type=showcase&name=<?php echo($name)?>" method="post">
<textarea cols="10" rows="10" name="content">
<?php echo($oldcontent); ?>
</textarea><br />
<input type="submit" name="changeshowcaseintro" value="Change" />
</form>
</div>
</section>

<section class="delete">
<h3 class="expand">Delete an Item</h3>
<div class="hide">
<form action="edit.php?type=<?php echo($type)?>&name=<?php echo($name)?>" method="post">
<label>Select an Item: </label>
<select name="item">
<?php 
//get all photos in gallery...
$stmt = $pdo->prepare("SELECT name FROM $name ORDER BY no DESC");
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_NUM);
foreach($row as $o) {
	$val = $o[0];
	echo "<option value=\"$val\">$val</option>\n";
}
?>

</select><br />
<label>Are you sure? </label>
<input type="checkbox" name="sure" value="true" /><br />
<input type="submit" name="deleteshowcaseitem" value="Delete" />
</form>
</div>
</section>

<?php 
} // ------- END SHOWCASE -----------


// - - - - - - - - - - - - - - - - - - - - - - - - - -


// * * * * * * * * * * * * BLOG * * * * * * * * * * * *
if($type == 'blog') {

/* ----------- add blog entry script ------------- */

if(isset($_POST['addblogentry']) && isset($_POST['title'])) {
	//get names
	$title = $_POST['title'];
	$date = get_mysql_date();
	$entry = $_POST['entry'];
	
	$stmt = $pdo->prepare("SELECT COUNT(*) FROM $name");
	$stmt->execute();
	$currentno = $stmt->fetch(PDO::FETCH_NUM);
	$no = $currentno[0] + 1;
		
	//enter details into database
	$stmt = $pdo->prepare("INSERT INTO $name(no, title, date, entry) VALUES(?,?,?,?)");
	$result = $stmt->execute(array($no, $title, $date, $entry));	

	if($result) $message .= p_wrap("Entry <b>$title</b> added");
}

// * * * * * * * EDIT INTRO * * * * * * * 
$contentpath = HOME . 'content/data/' . $name . '.html';
if(isset($_POST['content']) && isset($_POST['changeblogintro'])) {
	$rw = file_put_contents($contentpath, $_POST['content']);
	if($rw) $message .= p_wrap("File re-written");

}
$oldcontent = file_get_contents($contentpath);

/* --- delete blog entry script --- */
if(isset($_POST['deleteblogentry']) && isset($_POST['entry']) && isset($_POST['sure'])) {
	if($_POST['deleteblogentry'] == 'Delete' && $_POST['sure'] ==  'true') {
		$delitem = 1;
		$entry = $_POST['entry'];
		
		$stmt = $pdo->prepare("DELETE FROM $name WHERE title=?");
		$r = $stmt->execute(array($entry));
		
		//reorder no column in table
		$stmt = $pdo->prepare("SET @num = 0; UPDATE homepage SET no= (SELECT @num := @num + 1)");
		$rr = $stmt->execute();
		
		if($r) $message .= p_wrap("<b>$entry</b> has been deleted from <b>$name</b> table");
		if($rr) $message .= p_wrap("Table has been reordered");
		
		if($r && $rr) $message .= p_wrap("<b>All OK</b>");

	}
}

/*  - - - - - - edit blog entry - - - - - - - */
if(isset($_POST['commitblogedit']) && isset($_POST['entry'])) {
	$no = mysql_string_fix($_POST['no']); 
	$title = mysql_string_fix($_POST['title']);
	$date = mysql_string_fix($_POST['date']);
	$entry = mysql_string_fix($_POST['entry']);
	
	$stmt = $pdo->prepare("UPDATE $name SET title=?, date=?, entry=? WHERE no=?");
	$r = $stmt->execute(array($title, $date, $entry, $no));
	
	if($r) $message .= p_wrap("Entry <b>$title</b> edited");
}

if(!isset($_POST['sure']) && isset($_POST['deleteblogentry'])) $message .= p_wrap('Tick the "Are you sure?" box');

//show all messages
if($message !== '') {
	echo("<section class=\"changed\">$message</section>");
}

?>
<section class="add">
<h3 class="expand">Add New Entry</h3>
<div class="hide">
<form action="edit.php?type=<?php echo($type)?>&name=<?php echo($name)?>" method="post">
<label>Title: </label>
<input type="text" name="title" /><br />
<label>Entry: </label>
<textarea cols="20" rows="20" name="entry"></textarea><br />
<input type="submit" name="addblogentry" value="Add" />
</form>
</div>
</section>

<section class="edit">
<h3 class="expand">Edit an Entry</h3>
<div class="<?php 
//show this section if chosen to edit, otherwise it collapses and we don't want that
if(isset($_POST['showsection']) && $_POST['showsection'] == 'true') {
	echo('');
}
else {
	echo('hide');
}
?>">
<form action="edit.php?type=<?php echo($type)?>&name=<?php echo($name)?>" method="post">
<input type="hidden" name="showsection" value="true" />
<label>Select an Entry: </label>
<select name="entry">
<?php 
//get all entries ...
$stmt = $pdo->prepare("SELECT title FROM $name ORDER BY no DESC");
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_NUM);
foreach($row as $o) {
	$val = $o[0];
	echo "<option value=\"$val\">$val</option>\n";
}
?>

</select><br />
<input type="submit" name="editblogentry" value="Edit" />
</form>

<?php 
//now do the form for the editing
if(isset($_POST['showsection']) && $_POST['showsection'] == 'true') {
	$entry = $_POST['entry'];
	
	//get all info
	$stmt = $pdo->prepare("SELECT * FROM $name WHERE title=?");
	$stmt->execute(array($entry));
	$rowo = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$row = $rowo[0];

//now do the form....
?>
<form action="edit.php?type=<?php echo($type)?>&name=<?php echo($name)?>" method="post">
<input type="hidden" name="no" value="<?php echo($row['no'])?>"
<label>Title: </label>
<input type="text" name="title" value="<?php echo($row['title'])?>" /><br />
<label>Date: </label>
<input type="text" name="date" value="<?php echo($row['date'])?>" /><br />
<label>Entry: </label><br />
<textarea name="entry" cols="20" rows="10"><?php echo(stripcslashes($row['entry']))?></textarea><br />
<input type="submit" value="Commit Change" name="commitblogedit"/>
</form>

<?php
}
?>
</div>
</section>

<section class="edit">
<h3 class="expand">Edit Intro</h3>
<div class="hide">
<form action="edit.php?type=blog&name=<?php echo($name)?>" method="post">
<textarea cols="10" rows="10" name="content">
<?php echo($oldcontent); ?>
</textarea><br />
<input type="submit" name="changeblogintro" value="Change" />
</form>
</div>
</section>

<section class="delete">
<h3 class="expand">Delete an Entry</h3>
<div class="hide">
<form action="edit.php?type=<?php echo($type)?>&name=<?php echo($name)?>" method="post">
<label>Select an Entry: </label>
<select name="entry">
<?php 
//get all entries ...
$stmt = $pdo->prepare("SELECT title FROM $name ORDER BY no DESC");
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_NUM);
foreach($row as $o) {
	$val = $o[0];
	echo "<option value=\"$val\">$val</option>\n";
}
?>

</select><br />
<label>Are you sure? </label>
<input type="checkbox" name="sure" value="true" /><br />
<input type="submit" name="deleteblogentry" value="Delete" />
</form>
</div>
</section>

<?php
// * * * * * * * * * * * *  END BLOG * * * * * * * * * * * *

}

mainsection('e');

get_footer();

?>