<?php
// Unique IDs keep label/input pairing valid when multiple search forms are rendered on one page.
$unique_id = wp_unique_id('search-form-');
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="<?php echo esc_attr($unique_id); ?>">
        <span class="screen-reader-text"><?php esc_html_e('Search for:', 'vofa'); ?></span>
    </label>
    <input type="search" id="<?php echo esc_attr($unique_id); ?>" class="search-field" placeholder="<?php echo esc_attr_x('Search …', 'placeholder', 'vofa'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s" />
    <button type="submit" class="search-submit"><?php esc_html_e('Search', 'vofa'); ?></button>
</form>
