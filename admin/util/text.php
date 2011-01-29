<?php 

function format_htmlp($source) {
	$paragraphs = explode("\n", $source);
	$n = count($paragraphs);
	$whtml = array(); $j = 0;
	for($i = 0; $i < $n; $i++) {
		$temp = '<p>' . $paragraphs[$i] . '</p>';
		//strip out empty paragraphs
		if(preg_match("/[^<p>\/]/", $temp)) {
			$whtml[$j] = $temp;
			$j++;
		}
	}
	$html = implode('', $whtml); 
	return $html;
}

function p_wrap($t) {
	return '<p>'.$t.'</p>';
}

function lowercase_nospace($a) {
	//make lowercase
	$b = strtolower($a);
	//remove single space
	$c = str_replace(" ", "", $b);
	return $c;
}

?>
