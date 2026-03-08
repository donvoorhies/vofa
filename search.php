<?php get_header(); ?>
<main class="wrap">
<section class="content-area content-thin">
<?php if (have_posts()) : ?>
<header>
<h2>
<?php
printf(
    esc_html__('Search results for: %s', 'vofa'),
    '<span>' . esc_html(get_search_query()) . '</span>'
);
?>
</h2>
</header>

<?php while (have_posts()) : the_post(); ?>
<article <?php post_class('article-full'); ?>>
<header>
<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
</header>
<?php the_excerpt(); ?>
</article>
<?php endwhile; ?>

<?php the_posts_pagination([
    'mid_size'  => 1,
    'prev_text' => __('Previous', 'vofa'),
    'next_text' => __('Next', 'vofa'),
]); ?>

<?php else : ?>
<article class="article-full">
<header>
<h2><?php esc_html_e('No results found', 'vofa'); ?></h2>
</header>
<p><?php esc_html_e('Try searching with different keywords.', 'vofa'); ?></p>
<?php get_search_form(); ?>
</article>
<?php endif; ?>
</section>
<?php get_sidebar(); ?>
<div class="push"></div>
</main>
<?php get_footer(); ?>
