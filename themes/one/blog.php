<?php 


get_htmlhead();
get_sidebar();
get_header();


?>

<?php 

get_intro($pagetitle);

get_blog_entries($pagetitle);
/*
get_gallery_list($pagetitle);
get_gallery_photos($pagetitle);
*/


?>

<?php

get_footer();

?>


