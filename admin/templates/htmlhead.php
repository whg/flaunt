<!doctype html>  
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ --> 
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<title> <?php global $pagetitle; echo($pagetitle); ?> </title>
<link href="<?php get_styles_path_e() ?>" rel="stylesheet" type="text/css" />
<?php get_jquery(); get_html5shiv(); get_script_libraries(); get_own_scripts(); ?>
</head>
