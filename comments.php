<?php
// WordPress convention: do not expose comments until the correct post password is provided.
if (post_password_required()) {
    return;
}
?>

<section id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            printf(
                esc_html(_nx('%1$s comment', '%1$s comments', get_comments_number(), 'comments title', 'vofa')),
                esc_html(number_format_i18n(get_comments_number()))
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments([
                'style'      => 'ol',
                'short_ping' => true,
            ]);
            ?>
        </ol>

        <?php the_comments_pagination([
            'prev_text' => __('Previous', 'vofa'),
            'next_text' => __('Next', 'vofa'),
        ]); ?>
    <?php endif; ?>

    <?php
    comment_form([
        'title_reply' => __('Leave a comment', 'vofa'),
    ]);
    ?>
</section>
