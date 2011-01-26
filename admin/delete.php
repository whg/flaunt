<?php 

include_once('./afunctions.php');
check_login();

$pagetitle = 'Delete Page';


/* --- delete page --- */

$message = '';

if(isset($_POST['delete']) && isset($_POST['page']) && isset($_POST['sure'])) {
	if($_POST['delete'] == 'Delete' && $_POST['sure'] ==  'true') {
		$name = $_POST['page'];
		
		//find type of page...
		$stmt = $pdo->prepare("SELECT type from pages where name=?");
		$stmt->execute(array($name));
		$row = $stmt->fetch();
		$type = $row[0];
		
		//for showcase, gallery and blog
		if($type !== 'page') {
			$stmt = $pdo->prepare("DROP TABLE $name");
			$del = $stmt->execute();
			if($del) $message .= p_wrap("Table deleted");
		}
		
		//remove row from pages table
		$stmt = $pdo->prepare("DELETE FROM pages WHERE name=?");
		$trow = $stmt->execute(array($name));
		if($trow) $message .= p_wrap("Deleted page from table");
		
		//reorder table
		$stmt = $pdo->prepare("SET @num = 0; UPDATE pages SET no= (SELECT @num := @num + 1)");
		$rr = $stmt->execute();

		
		//delete data file
		$fn = HOME . 'content/data/' . $name . '.html';
		$ddf = unlink($fn);
		if($ddf) $message .= p_wrap("Deleted data file");
		
		$dpf = 0;
		if($type !== 'showcase') {
			//delete page file
			$fdn = HOME . $name . '.php';
			$dpf = unlink($fdn);
			if($dpf) $message .= p_wrap("Deleted page file");
		}
		//if showcase
		else {
			
			foreach(glob(HOME.$name."/data/*") as $imagefile) {
				unlink($imagefile);
			}
			//this silly file can ruin things somewhat...
			if(file_exists(HOME . $name . '/data/.DS_Store')) {
				unlink(HOME . $name . '/data/.DS_Store');
			}
			//hopefully now we can remove the folder
			rmdir(HOME.$name.'/data');
			
			foreach(glob(HOME.$name."/*") as $folderfile) {
				unlink($folderfile);
			}
			//and this one
			if(file_exists(HOME . $name . '/.DS_Store')) {
				unlink(HOME . $name . '/.DS_Store');
			}
			//final check
			$dpf = rmdir(HOME . $name);
			if($dpf) $message .= p_wrap("Deleted page folder");
		}
				
		if($trow && $ddf && $dpf) {
			$message .= p_wrap("<b>All OK</b>");
		}
		else $message .= p_wrap("<b>Not OK!</b>");
	}
}

//do these here
get_html_head();
start_body();
get_sidebar();
mainsection('s');
get_header();

if($message !== '') {
	echo("<section class=\"changed\">$message</section>");
}



?>

<section class="delete">
<form action="delete.php" method="post">
<label>Select Page:</label>
<select name="page">
<?php 
$stmt = $pdo->prepare("SELECT name, type FROM pages ORDER BY id DESC");
$stmt->execute();
$row = $stmt->fetchAll(PDO::FETCH_NUM);
foreach($row as $o) {
	if($o[1] !== 'marker') {
		$val = $o[0];
		echo "<option value=\"$val\">$val</option>\n";
	}
}
?>
</select><br />
<label>Are you sure? </label>
<input type="checkbox" name="sure" value="true" /><br />
<input type="submit" name="delete" value="Delete" />
</form>
</section>

<?php 

mainsection('e');

get_footer();

?>