<?php 

include_once('./afunctions.php');
check_login();

$user = $_SESSION['user'];

$pagetitle = 'Backstage';

get_html_head();
start_body();
get_sidebar();
mainsection('s');
get_header();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM pages");
$stmt->execute();
$pn = $stmt->fetch(PDO::FETCH_NUM);
$pagenum = $pn[0];

?>
<section class="top">
<p>Hi <?php echo($user)?>, visit the site <a href="<?php echo(ROOT)?>">here</a></p>
</section>

<section class="info">
<p>You have <span class="stat"><?php echo($pagenum)?></span> section(s)</p>

</section>

<section class="info">
<p>The Sitename is: <span class="stat"><?php echo(SITENAME)?></span></p>
<p>The author is: <span class="stat"><?php echo(AUTHOR)?></span></p>
<p>You are using the <span class="stat"><?php echo(THEMENAME)?></span> theme</p>
</section>



<?php
mainsection('e');

get_footer();

?>