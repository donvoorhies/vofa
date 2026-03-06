<footer>
<?php if (has_nav_menu('footer-menu')) : ?>
<?php wp_nav_menu(['theme_location' => 'footer-menu']); ?>
<?php endif; ?>
<p><?php bloginfo('name'); ?> | &copy; <?php echo esc_html(date('Y')); ?> <?php esc_html_e('All rights reserved.', 'vofa'); ?></p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
