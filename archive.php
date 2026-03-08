<?php get_header(); ?>
<main class="wrap">
<section class="content-area content-thin">
<?php if (have_posts()) : ?>
<header>
<h2><?php the_archive_title(); ?></h2>
<?php the_archive_description('<div class="archive-description">', '</div>'); ?>
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
<h2><?php esc_html_e('Nothing found', 'vofa'); ?></h2>
</header>
<p><?php esc_html_e('There are no posts to show in this archive yet.', 'vofa'); ?></p>
</article>
<?php endif; ?>
</section>
<?php get_sidebar(); ?>
<div class="push"></div>
</main>
<?php get_footer(); ?>
