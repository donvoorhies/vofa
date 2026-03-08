<?php
/**
 * Theme Optimization and Hardening
 */

/*----------------------------------
 # SECURITY / PERFORMANCE OPTIMIZATIONS
-----------------------------------*/

/* Remove SSL capability test */
remove_filter('atom_service_url','atom_service_url_filter');

/* Remove WordPress version from head and feeds */
add_filter('the_generator', '__return_empty_string');

/* Clean up wp_head */
add_action('init', function () {
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'start_post_rel_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
});

/* Disable internal pingbacks & trackbacks */
add_action('pre_ping', function (&$links) {
    foreach ($links as $l => $link) {
        if (0 === strpos($link, home_url('/'))) {
            unset($links[$l]);
        }
    }
});

/* Disable pingback headers & methods */
add_filter('wp_headers', function ($headers) {
    unset($headers['X-Pingback']);
    return $headers;
});
add_filter('bloginfo_url', 'disable_pingback_url', 10, 2);
add_filter('bloginfo', 'disable_pingback_url', 10, 2);
add_filter('xmlrpc_enabled', '__return_false');
add_filter('xmlrpc_methods', function ($methods) {
    unset($methods['pingback.ping']);
    return $methods;
});
// Hooking both filters prevents pingback URL output in different bloginfo() code paths.
function disable_pingback_url($output, $show = '') {
    return $show === 'pingback_url' ? '' : $output;
}

/* Remove version numbers from scripts and styles */
add_filter('style_loader_src', 'remove_css_js_ver', 9999);
add_filter('script_loader_src', 'remove_css_js_ver', 9999);
function remove_css_js_ver($src) {
    return strpos($src, '?ver=') ? remove_query_arg('ver', $src) : $src;
}

/*----------------------------------
 # THEME SUPPORT & MENUS
-----------------------------------*/
function flash_theme_setup() {
    load_theme_textdomain('vofa', get_template_directory() . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height'      => 120,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // Gutenberg block editor support
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'editor-style.css' );

    register_nav_menus([
        'main-menu'   => __('Main Menu', 'vofa'),
        'footer-menu' => __('Footer Menu', 'vofa'),
    ]);
}
add_action('after_setup_theme', 'flash_theme_setup');

if (! isset($content_width)) {
    $content_width = 1200;
}

/*----------------------------------
 # ENQUEUE STYLES & SCRIPTS
-----------------------------------*/
function flash_theme_enqueue_assets() {
    wp_enqueue_style('flash-theme-style', get_stylesheet_uri());

    wp_enqueue_script('fade-transition', get_template_directory_uri() . '/fade-transition.js', [], null, true);

    wp_register_script('vofa-navigation', false, ['jquery'], null, true);
    wp_enqueue_script('vofa-navigation');
    wp_add_inline_script('vofa-navigation', '
        jQuery(document).ready(function($) {
            $("#menu").click(function() {
                $("nav").slideToggle("slow");
            });
        });
    ');

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'flash_theme_enqueue_assets', 20);

/* Load Google Fonts using WebFontLoader */
function open_sans_font_loader() {
    wp_enqueue_script('webfontloader', 'https://cdnjs.cloudflare.com/ajax/libs/webfont/1.6.28/webfontloader.js', [], '1.6.28', true);

    wp_add_inline_script('webfontloader', '
        WebFont.load({
            google: { families: ["Open+Sans:400,700&display=swap"] },
            inactive: function() { console.warn("Font loading failed."); }
        });
    ');
}
add_action('wp_enqueue_scripts', 'open_sans_font_loader');

/*----------------------------------
 # WIDGETS
-----------------------------------*/
function flash_theme_widgets_init() {
    register_sidebar([
        'name'          => __('Homepage Widget Area', 'vofa'),
        'id'            => 'homepage-widget-area',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);
    register_sidebar([
        'name'          => __('Sidebar', 'vofa'),
        'id'            => 'sidebar',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>',
    ]);
}
add_action('widgets_init', 'flash_theme_widgets_init');

/**
 * Conditionally add async or defer attributes to enqueued scripts
 * Usage: Use script handle names in the arrays to control behavior.
 */
function flash_async_defer_scripts($tag, $handle, $src) {
    // Keep original tags for logged-in users to avoid customizer/admin script regressions.
    if (is_user_logged_in()) {
        return $tag;
    }

    // Add handles you want to defer
    $defer_scripts = array(
        'fade-transition',
        'webfontloader',
    );

    // Add handles you want to async
    $async_scripts = array();

    // Skip jQuery or any critical scripts
    if (in_array($handle, array('jquery'))) {
        return $tag;
    }

    // Add 'defer' attribute
    if (in_array($handle, $defer_scripts)) {
        return '<script src="' . esc_url($src) . '" defer></script>' . "\n";
    }

    // Add 'async' attribute
    if (in_array($handle, $async_scripts)) {
        return '<script src="' . esc_url($src) . '" async></script>' . "\n";
    }

    // Return unchanged for all others
    return $tag;
}
add_filter('script_loader_tag', 'flash_async_defer_scripts', 10, 3);
