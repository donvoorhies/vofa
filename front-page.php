<?php get_header(); ?>
<main class="wrap">
<section class="content-area content-thin">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article class="article-full">
<header>
<h2><?php the_title(); ?></h2>
</header>
<?php the_content(); ?>
<?php wp_link_pages(); ?>

<?php if (comments_open() || get_comments_number()) : ?>
<?php comments_template(); ?>
<?php endif; ?>
</article>
<?php endwhile; else : ?>
<article>
<p><?php esc_html_e('Sorry, no page content was found!', 'vofa'); ?></p>
</article>
<?php endif; ?>
</section>
<?php get_sidebar(); ?>
<div class="push"></div>
</main>
<?php get_footer(); ?>