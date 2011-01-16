<?php 

get_htmlhead();
?>
<style>

header {
	background-image: url('<?php get_headerimage_path($showcasename, $pagetitle)?>');
}

</style>
<?php 
get_sidebar();
get_header();

?>


<section>

<?php 

get_showcase_page_contents($pagetitle);
?>

</section>



<?php

get_footer();

?>




