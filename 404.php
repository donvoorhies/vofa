<?php get_header(); ?>
<main class="wrap">
<section class="content-area content-full-width">
<article class="article-full">
<header>
<h2><?php esc_html_e('Page not found', 'vofa'); ?></h2>
</header>
<p><?php esc_html_e('Sorry, the page you are looking for could not be found.', 'vofa'); ?></p>
<p><?php esc_html_e('Try a search instead:', 'vofa'); ?></p>
<?php get_search_form(); ?>
</article>
</section>
<div class="push"></div>
</main>
<?php get_footer(); ?>
