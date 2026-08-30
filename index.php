<?php get_header(); ?>
<main class="wrap">
    <section class="content-area content-full-width">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('article-full'); ?>>
                    <header>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php vofa_posted_on(); ?>
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
            <article>
                <p><?php esc_html_e('Sorry, no posts matched your criteria.', 'vofa'); ?></p>
            </article>
        <?php endif; ?>
    </section>
    <div class="push"></div>
</main>
<?php get_footer(); ?>