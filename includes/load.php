
<?php 
/* a load of functions*/ 

/* the organisation of this file is pretty rubbish... */

define('INC', HOME.'includes/');

include_once(INC . './gets.php');

function reinstantiate_pdo() {
	//not quite sure why i have to do this again...
	$dsn = "mysql:host=".DB_HOST.";dbname=".DB;
	
	try {
	    $pdo = new PDO($dsn, DB_USER, DB_PASS);
	}
	catch(PDOException $e) {
	    die("Could not connect to the database\n");
	}
	
	return $pdo;
}

//use this for filenames and stuff...
function lowercase_nospace($a) {
	//make lowercase
	$b = strtolower($a);
	//remove single space
	$c = str_replace(" ", "", $b);
	return $c;
}

//these are really stupid, i don't think they are used
//good if you don't want to drop out of php
function section($t) {
	switch($t) {
		case 's': echo("<section>\n"); break;
		case 'e': echo("</section>\n"); break;
	}
}

function article($t) {
	switch($t) {
		case 's': echo("<article>\n"); break;
		case 'e': echo("</article>\n"); break;
	}
}

function get_random_header_background($n) {
	$i = rand(1, $n);
	$path = ROOT . "content/images/tile$i.jpg";
	echo("
	<style>
	header { background-image: url('$path'); }
	</style>
	");
}

/* * * * * * * * HOMEPAGE * * * * * * *  */

function current_page_number() {
	$pageNumber = 0;
	if(isset($_GET['n'])) {
		$pageNumber = $_GET['n'];
	}
	return $pageNumber;
}

function homepage_array() {
	//query MySQL...
	$pdo = reinstantiate_pdo();	
	global $maxPage, $pageNumber;
	$wantedPage = $maxPage-$pageNumber;	
	$stmt = $pdo->prepare("SELECT name,date,info,path,type FROM homepage WHERE no=?");
	$stmt->execute(array($wantedPage));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	return $row;
	
}

function homepage_max_no() {
	$pdo = reinstantiate_pdo();	
	$stmt = $pdo->prepare("SELECT MAX(no) FROM homepage");
	$stmt->execute();	
	$row = $stmt->fetch(PDO::FETCH_NUM);
	$maxPage = $row[0];
	return $maxPage;

}

function get_homepage_product() {
	
	global $hp_array;
	//start div
	echo('<div class="homepageproduct">');
	
	if($hp_array['type'] == 'image') {
		echo("<a href=\"$hp_array[path]\">
		<img src=\"$hp_array[path]\" alt=\"$hp_array[name]\" class=\"sixforty\"/>
		</a>\n");
	}
	else if($hp_array['type'] == 'page') include(HOME . $hp_array['path']);
	
	//end div
	echo('</div> <!-- end center -->');
}

function get_homepage_info() {
	global $hp_array;
	if($hp_array) {
		$time = strtotime($hp_array['date']);
		$htime = date('d/m/Y', $time); //human time
		$mtime = date('Y-m-d', $time); //machine time
		
		echo("
		<p class=\"showinfo\">
		<time datetime=\"$mtime\">$htime</time> - 
		<span class=\"link\">info</span>
		</p>
		
		<article class=\"info\">
		<header> 
		<h2>$hp_array[name]</h2>
		</header>
		$hp_array[info]
		</article>
		");
	}

}

function get_homepage_nav() {
	global $maxPage, $pageNumber;
	echo('<div id="homepagenav">');
	$pp = $pageNumber+1;
	$np =  $pageNumber-1;
	if($pp < $maxPage) echo "<a href=\"?n=$pp\">previous</a>\n";
	if($np >= 0) echo "<a href=\"?n=$np\">next</a>\n";
	echo('</div> <!-- end homepagenav -->');
}

/* * * * * * * * SIDEBAR * * * * * * *  */


function get_sidebar_list_items() {
	
	$pdo = reinstantiate_pdo();				
	$stmt = $pdo->prepare("SELECT name,type FROM pages ORDER BY no ASC");
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	
	//always have home as link
	echo('<li><a href="'.ROOT."\">Home</a></li>\n");
	
	foreach($row as $name) {
		//get nospace and capital files...
		$nname = lowercase_nospace($name['name']);
	
		//do the marker to separate the other items
		if($name['type'] == 'marker') {
			echo("<li class=\"marker\">$name[name]</li>\n");
		}
		else if($name['type'] !== 'showcase') {
			echo('<li><a href="'.ROOT.$nname.'.php">'.$name['name']."</a></li>\n");
		}
		//for showcase direct to directory
		else if($name['type'] == 'showcase') {
			echo('<li><a href="'.ROOT."$nname\">".$name['name']."</a></li>\n");
		}
	}

}

function get_sidebar_image() {
	//for jpeg
	$jfile = 'content/images/logo.jpg';
	//for png 
	$pfile = 'content/images/logo.png';
	$files = array($jfile, $pfile);
	
	foreach($files as $file) {
		if(file_exists(HOME . $file)) {
			echo('<a href="' . ROOT . '"><img src="' . ROOT . $file . '" alt="logo" class="logo" /></a>');
		}
	}
}

/* * * * * * * * PAGE * * * * * * *  */

function get_page_contents($pagetitle){
	//remove whitespace and stuff
	$npagetitle = lowercase_nospace($pagetitle);
	echo("<section id=\"page\">\n");
	include(HOME.'content/data/'.$npagetitle.'.html');
	echo("</section>\n\n");
}


function get_intro($p) {
	//remove whitespace and stuff
	$np = lowercase_nospace($p);
	echo("<section class=\"intro\">\n");
	include(HOME.'content/data/'.$np.'.html');
	echo("</section>\n\n");
}

/* * * * * * * * GALLERY * * * * * * *  */

function get_gallery_list($p) {
	$np = lowercase_nospace($p);
	$pdo = reinstantiate_pdo();				
	$stmt = $pdo->prepare("SELECT name FROM $np ORDER BY no DESC");
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_COLUMN);
	
	echo("<ul class=\"gallery_list\">\n");
	foreach($row as $name) {
		echo("<li><a href=\"$p.php#$name\">$name</a></li>\n");
	}
	echo("</ul>\n\n");
}

function get_gallery_photos($p) {
	$np = lowercase_nospace($p);
	$pdo = reinstantiate_pdo();
	$stmt = $pdo->prepare("SELECT * FROM $np ORDER BY no DESC");
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	$photopath = ROOT . 'content/images/';
			
	foreach($row as $post) {
		echo ("
		<article class=\"gallery_item\">
		<h3 id=\"$post[name]\">$post[name]</h3>
		<figure>
		<img src=\"$photopath$post[file]\" class=\"sixforty\" alt=\"$post[name]\" />
		<p class=\"showinfo\"><span class=\"link\">info</span></p>
		<figcaption class=\"info\">
		$post[caption]\n
		</figcaption>
		</figure>
		</article>
		");
	}		
}

/* * * * * * * * SHOWCASE * * * * * * *  */

function get_headerimage_path($p, $item) {
	$np = lowercase_nospace($p);
	$pdo = reinstantiate_pdo();				
	$stmt = $pdo->prepare("SELECT headerimage FROM $np WHERE name=?");
	$stmt->execute(array($item));
	$result = $stmt->fetch();
	echo("data/" . $result[0]);
}

function get_showcase_list($p) {
	$np = lowercase_nospace($p);
	$pdo = reinstantiate_pdo();				
	$stmt = $pdo->prepare("SELECT name FROM $np ORDER BY no DESC");
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_COLUMN);
	
	echo("<ul class=\"showcase_list\">\n");
	foreach($row as $name) {
		echo("<li><a href=\"$name.php\">$name</a></li>\n");
	}
	echo("</ul>\n\n");
}

function get_showcase_items($p) {
	$np = lowercase_nospace($p);
	$pdo = reinstantiate_pdo();
	$stmt = $pdo->prepare("SELECT * FROM $np ORDER BY no DESC");
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
	foreach($row as $item) {
		$summary = stripcslashes($item['summary']);
		//remove space
		$nitem = lowercase_nospace($item['name']);
		echo("
		<article class=\"showcase_item\">
		<h3><a class=\"showcase_title\" href=\"$nitem.php\">$item[name]</a></h3>
		<a href=\"$nitem.php\">
		<img src=\"data/$item[smallimage]\" class=\"showcase_smallimage\" alt=\"$item[name]\" />
		</a>
		<p>$summary</p>
		</article>
		");
	}
	
}

function get_showcase_page_contents($item) {
	//remove space
	$nitem = lowercase_nospace($item);
	echo("<section id=\"showcase_page\">\n");
	include('./data/'.$nitem.'.php');
	echo("</section>\n\n");
}

/* * * * * * * * BLOG * * * * * * *  */

function get_blog_entries($p) {
	$np = lowercase_nospace($p);
	$pdo = reinstantiate_pdo();
	$stmt = $pdo->prepare("SELECT * FROM $np ORDER BY no DESC");
	$stmt->execute();
	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($row as $entry) {
		//sort out time...
		$time = $entry['date'];
		$time = strtotime($time);
		$htime = date('d/m/Y', $time); //human time
		$mtime = date('Y-m-d', $time); //machine time
		
		$ent = stripcslashes($entry['entry']);
		
		echo("
		<article class=\"blog_item\">
			<header>
				<h3>$entry[title]</h3>
				<time datetime=\"$mtime\">$htime</time>
			</header>
			$ent
			<footer>
			</footer>
		</article>
		");
	}
	
}


?>