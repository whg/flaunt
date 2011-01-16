<?php 

get_htmlhead();
get_sidebar();
get_header();


?>


<section id="homepage">
<?php 
$pageNumber = current_page_number();
$maxPage = homepage_max_no();
$hp_array = homepage_array();
get_homepage_product();
get_homepage_info();
get_homepage_nav();

?>

</section>


<?php

get_footer();

?>




