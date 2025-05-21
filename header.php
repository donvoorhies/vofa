<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php bloginfo('name'); ?></title>
	<link rel="preconnect" href="https://cdnjs.cloudflare.com/">
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>	
<noscript>
</noscript>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header>
<div id="menu">&equiv;</div><div class="cf"></div>
<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1><div class="cf">
<!--<p><?php bloginfo('description'); ?></p>-->
</header>
<nav>
<?php wp_nav_menu(array('theme_location' => 'main-menu')); ?>
</nav>

