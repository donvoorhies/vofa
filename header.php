<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Free Web tutorials">
    <title><?php bloginfo('name'); ?></title>
	<link rel="preconnect" href="https://cdnjs.cloudflare.com/">
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>	
<noscript>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap">
<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">    
</noscript>
<style>
html{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body,html{height:100%;margin:0}body{font:16px 'Open Sans',Arial,sans-serif;margin:0 auto;padding:0;line-height:1.6;color:#333;background-color:#fff;width:95%;max-width:100%}body.page-id-26 main header{display:none}body.fade-out{opacity:0;transition:opacity .5s ease-in-out}body.fade-in{opacity:1;transition:opacity .5s ease-in-out}article,div.push,footer,header,main,nav,nav div.menu-menu-container,section.featured-content,section.recent-posts,ul#menu-menu{margin:0 auto;width:95%;max-width:100%}a,a:active,a:visited{color:#000;text-decoration:none}a:hover{color:#005177}header{background-color:#fff;color:#333;padding:1rem 0;text-align:left;position:relative;border-bottom:1px solid #ccc}header h1 a{margin:0;font-size:2.5rem;color:#333}header p{margin:0;font-size:1.2rem}nav{background-color:#fff;color:#333;padding:.5rem;text-align:center;list-style-type:none;width:calc(95% - 1rem)}nav a{color:#333;text-decoration:none;margin:0 .5rem}nav a:hover{text-decoration:underline}nav ul li{display:inline;text-decoration:none}#menu{display:none}main{padding:1rem;background-color:#fff;min-height:100%;margin-bottom:-24px;width:calc(95% - 2rem)}main h2{font-size:2rem;margin-bottom:1rem;color:#333;border-bottom:2px solid #000;padding-bottom:.5rem}main article{margin-bottom:2rem}main article h3{font-size:1.5rem;margin-bottom:.5rem}main article p{margin-bottom:1rem}main section article header{border-bottom:0 none}article.article-full header{width:100%;max-width:100%}input{width:95%;max-width:100%;margin:0 auto}p input.wpcf7-submit{width:95px}img{border:0;height:auto;max-width:100%}iframe{width:100%,max-width:100%}video{aspect-ratio:16/9;height:100%;width:100%;max-width:100%;margin:0 auto}div.push{height:24px}footer{background-color:#fff;color:#333;text-align:center;padding:1rem 0;position:relative;width:95%;max-width:100%;margin:0 auto;height:24px;line-height:24px}footer p{margin:0;font-size:.9rem}footer nav{margin-top:1rem}footer nav a{margin:0 .5rem}.featured-content,.recent-posts{margin-bottom:2rem}.featured-content h2,.recent-posts h2{border-bottom:2px solid #000;padding-bottom:.5rem}.featured-content article,.recent-posts article{margin-bottom:1.5rem}.cf:after,.cf:before{content:" ";display:table}.cf:after{clear:both}@media (max-width:768px){header{background-color:#fff;color:#333;padding:1rem 0;text-align:center;position:relative}div#menu{border:1px solid #333}#menu,#menu a,nav ul li a{display:block;width:45px;height:45px;line-height:45px;float:right;font-size:2em;z-index:1000;text-align:left}#menu a:hover,nav ul li a:hover{color:#fff;background:#000}#menu,#menu a{text-align:center}nav{display:none;text-align:left}ul{padding:0}nav ul li,nav ul li:hover{display:block;height:45px;line-height:45px;max-width:100%;width:100%;margin-left:0}nav ul li a{display:block;height:45px;line-height:45px;max-width:100%;width:100%;margin-left:0;font-size:16px;text-decoration:none}nav ul li a:hover{color:#fff;background:#333;text-decoration:none}nav ul li:first-child{border-bottom:0 none}}
</style>
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

