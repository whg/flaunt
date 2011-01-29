<?php 

include_once('./afunctions.php');
check_login();

$pagetitle = 'Add Section';

$type = '';
$name = '';
$message = '';

//checking variables... this isn't very clever, but works...
$c1 = 0;
$c2 = 0;
$fh = 0;

if(!empty($_POST['name']) && isset($_POST['submit'])) {
	$type = $_POST['type'];
	$name = $_POST['name'];
	//create name with no whitespace or capitals
	$nname = lowercase_nospace($name);

	
	//get current no of pages...	
	$stmt = $pdo->prepare("SELECT COUNT(*) FROM pages");
	$stmt->execute();
	$currentno = $stmt->fetch(PDO::FETCH_NUM);
	$no = $currentno[0] + 1;
	
	//add page to pages table... at the end
	$stmt = $pdo->prepare("INSERT INTO pages(no, name, type) VALUES(?, ?, ?)");
	$c1 = $stmt->execute(array($no, $name, $type));
	if($c1) $message .= p_wrap("Added page <b>$name</b> to pages table");
	
	//create intro/page file - this is used on all types
	$fm = HOME . 'content/data/' . $nname . '.html';
	$fh = fopen($fm, 'w');
	fclose($fh);
	if($fh) $message .= p_wrap("Made file in data folder");
	
	if($type !== 'showcase') {
		//create page in root
		//if there is space in the name remove it, so we don't get a messy %20
		//also make it lowercase
		$dest = HOME . "$nname.php";	
		$ffh = fopen($dest, 'w');
		//write
		$c2 = fwrite($ffh, addpage($name, $type));
		//and close
		fclose($ffh);
		chmod($dest, 0774);
		if($c2) $message .= p_wrap("Page created");
	}
	
	// - - - - for showcase - - - - 
	else {
		//create folder for showcase
		//remove any space and stuff
		$dest = HOME . $nname;
		mkdir($dest);
		chmod($dest, 0774);
		mkdir($dest. '/data');
		chmod($dest . '/data', 0774);
		
		//create index page
		$ffh = fopen($dest . '/index.php', 'w');
		//addpage() puts all the goodness in... basically includes...
		$c2 = fwrite($ffh, addpage($name, $type));	
		fclose($ffh);
		chmod($dest . '/index.php', 0774);
		if($c2) $message .= p_wrap("Page created");
	}
	
/* 	- - - - - NOW DO THE TYPES - - - - - - */
	
	if($type == 'gallery') {
	
	//add create own table for data
	$stmt = $pdo->prepare("CREATE TABLE $nname(
	id INT NOT NULL AUTO_INCREMENT,
	no INT(4),
	name VARCHAR(20),
	file VARCHAR(20),
	caption TEXT,
	PRIMARY KEY (id)) ENGINE MyISAM");
	$r2 = $stmt->execute();
	if($r2) $message .= p_wrap("Created <b>$nname</b> table");

	if($r2 && $fh && $c1 && $c2) $message .= p_wrap("<b>All OK</b>");
	else $message .= p_wrap("<b>Not OK!</b>");

	}
	else if($type == 'showcase') {
		
		//create own table for showcase data
		$stmt = $pdo->prepare("CREATE TABLE $nname(
		id INT NOT NULL AUTO_INCREMENT,
		no INT(4),
		name VARCHAR(20),
		summary TEXT,
		smallimage VARCHAR(20),
		headerimage VARCHAR(20),
		PRIMARY KEY (id)) ENGINE MyISAM");
		$r2 = $stmt->execute();
		if($r2) $message .= p_wrap("Created <b>$nname</b> table");
	
		if($r2 && $fh && $c1 && $c2) $message .= p_wrap("<b>All OK</b>");
		else $message .= p_wrap("<b>Not OK!</b>");
	
	}
	else if($type == 'page') {	
		
		//final check
		if($c1 && $fh && $c2) $message .= p_wrap("<b>All OK</b>");
		else $message .= p_wrap("<b>Not OK!</b>");
	
	}
	else if($type == 'blog') {
	
		//create own table for blog data
		$stmt = $pdo->prepare("CREATE TABLE $nname(
		id INT NOT NULL AUTO_INCREMENT,
		no INT(4),
		title VARCHAR(20),
		date DATETIME,
		entry MEDIUMTEXT,
		PRIMARY KEY (id)) ENGINE MyISAM");
		$r2 = $stmt->execute();
		if($r2) $message .= p_wrap("Created <b>$nname</b> table");
	
		if($r2 && $fh && $c1 && $c2) $message .= p_wrap("<b>All OK</b>");
		else $message .= p_wrap("<b>Not OK!</b>");
	
	}

}
//submitting with no name...
else if(empty($_POST['name']) && isset($_POST['submit'])){
	$message = "A page must have a name.";
}


//do this here so that the new page gets added to the sidebar...

get_html_head();
start_body();
get_sidebar();
mainsection('s');
get_header();

//display all messages...
if($message !== '') {
	echo("<section class=\"changed\">$message</section>");
}

?>


<section class="add">
<form method="post" action="<?php echo($_SERVER['PHP_SELF']) ?>" >
<label>Name: </label><input type="text" name="name" /><br />
<label>Type: </label>
<input type="radio" name="type" value="page" />Page
<input type="radio" name="type" value="blog" />Blog
<input type="radio" name="type" value="showcase" />Showcase
<input type="radio" name="type" value="gallery" />Gallery<br />
<input type="submit" name="submit" value="Add" />
</form>
</section>


<?php
mainsection('e');

get_footer();

?>