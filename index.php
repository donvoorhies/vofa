<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php get_header(); ?>
    <main>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php the_content(); ?>
            </article>
        <?php endwhile; else : ?>
            <p><?php esc_html_e('Sorry, no posts matched your criteria.', 'flash-theme'); ?></p>
        <?php endif; ?>
	<div class="push">
		</div>
    </main>
    <?php get_footer(); ?>
    <?php wp_footer(); ?>

</body>
</html>