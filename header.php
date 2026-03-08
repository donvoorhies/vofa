<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="preconnect" href="https://cdnjs.cloudflare.com/">
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>	
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header>
<div id="menu">&equiv;</div><div class="cf"></div>
<?php if (has_custom_logo()) : ?>
<?php the_custom_logo(); ?>
<?php else : ?>
<h1><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
<?php endif; ?>
<div class="cf"></div>
</header>
<nav>
<?php wp_nav_menu(array('theme_location' => 'main-menu')); ?>
</nav>

