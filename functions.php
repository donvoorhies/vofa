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
        if (0 === strpos($link, get_option('home'))) {
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
function disable_pingback_url($output, $show = '') {
    return $show === 'pingback_url' ? '' : $output;
}

/* Remove version numbers from scripts and styles */
add_filter('style_loader_src', 'remove_css_js_ver', 9999);
add_filter('script_loader_src', 'remove_css_js_ver', 9999);
function remove_css_js_ver($src) {
    return strpos($src, '?ver=') ? remove_query_arg('ver', $src) : $src;
}

/* Move enqueued scripts to footer */
add_action('wp_enqueue_scripts', function () {
    remove_action('wp_head', 'wp_print_scripts');
    remove_action('wp_head', 'wp_print_head_scripts', 9);
    remove_action('wp_head', 'wp_enqueue_scripts', 1);

    add_action('wp_footer', 'wp_print_scripts', 5);
    add_action('wp_footer', 'wp_enqueue_scripts', 5);
    add_action('wp_footer', 'wp_print_head_scripts', 5);
});

/*----------------------------------
 # THEME SUPPORT & MENUS
-----------------------------------*/
function flash_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus([
        'main-menu'   => __('Main Menu', 'Vofa'),
        'footer-menu' => __('Footer Menu', 'Vofa'),
    ]);
}
add_action('after_setup_theme', 'flash_theme_setup');

/*----------------------------------
 # ENQUEUE STYLES & SCRIPTS
-----------------------------------*/
function flash_theme_enqueue_assets() {
    wp_enqueue_style('flash-theme-style', get_stylesheet_uri());

    wp_enqueue_script('fade-transition', get_template_directory_uri() . '/fade-transition.js', [], null, true);
}
add_action('wp_enqueue_scripts', 'flash_theme_enqueue_assets', 20);

/* Load jQuery from CDN with custom toggle menu */
function load_latest_jquery() {
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js', [], null, true);

    wp_add_inline_script('jquery', '
        jQuery(document).ready(function($) {
            $("#menu").click(function() {
                $("nav").slideToggle("slow");
            });
        });
    ');
}
add_action('wp_enqueue_scripts', 'load_latest_jquery', 30);

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
        'name'          => __('Homepage Widget Area', 'Vofa'),
        'id'            => 'homepage-widget-area',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ]);
    register_sidebar([
        'name'          => 'Sidebar',
        'id'            => 'sidebar',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>',
    ]);
}
add_action('widgets_init', 'flash_theme_widgets_init');

/*----------------------------------
 # OPTIONAL: Lazy Load Images (Uncomment to Use)
-----------------------------------*/
/*
function add_lazy_loading($content)

/**
 * Conditionally add async or defer attributes to enqueued scripts
 * Usage: Use script handle names in the arrays to control behavior.
 */
function flash_async_defer_scripts($tag, $handle, $src) {
    // Do not modify for logged-in users (admin or customizer)
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
