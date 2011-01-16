<?php 

function addpage($n, $type) {
	$addpage = '';
	if($type !== 'showcase') {
		$addpage = '<?php $pagetitle = ' . "'$n';";
		$addpage .= " 
		include_once('./include.php');
		include_once(\"./themes/".THEMENAME."/$type.php\");
		";
		$addpage.= '?>';
	}
	//for showcase do ../ as there is a new directory
	else {
		$addpage = '<?php $pagetitle = ' . "'$n';";
		$addpage .= " 
		include_once('../include.php');
		include_once(\"../themes/".THEMENAME."/$type.php\");
		";
		$addpage.= '?>';
	}
	return $addpage;
}

function add_showcase_page($n, $folder) {
	$addpage = '<?php $pagetitle = ' . "'$n';\n";
	$addpage .= '$showcasename = ' . "'$folder';";
	$addpage .= " 
	include_once('../include.php');
	include_once(\"../themes/".THEMENAME."/showcasepage.php\");
	";
	$addpage.= '?>';
	return $addpage;
}

?>
