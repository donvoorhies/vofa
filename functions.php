<?php
/**
 * Theme functions and definitions
 *
 * @package Vofa
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Setup
 * - Registers theme supports, navigation menus.
 */
function Vofa_setup() {
	// Add support for title tag, letting WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Add support for post thumbnails (featured images).
	add_theme_support( 'post-thumbnails' );

	// Register navigation menus.
	register_nav_menus(
		array(
			'header-menu' => __( 'Header Menu', 'Vofa' ),
			'main-menu'   => __( 'Main Menu', 'Vofa' ), // Assuming 'main-menu' is distinct from 'header-menu'
			'footer-menu' => __( 'Footer Menu', 'Vofa' ),
		)
	);
}
add_action( 'after_setup_theme', 'Vofa_setup' );

/**
 * Enqueue styles and scripts for the theme.
 */
function Vofa_enqueue_assets() {
	// Enqueue main stylesheet.
	wp_enqueue_style( 'flash-theme-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );

	// Enqueue fade transition script in the footer.
	wp_enqueue_script( 'fade-transition', get_template_directory_uri() . '/fade-transition.js', array(), wp_get_theme()->get( 'Version' ), true );
}
add_action( 'wp_enqueue_scripts', 'Vofa_enqueue_assets' );


/**
 * Register widget areas.
 */
function Vofa_widgets_init() {
	// Primary Sidebar
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'flash-theme' ),
			'id'            => 'sidebar',
			'description'   => __( 'Main sidebar appearing on posts and pages.', 'flash-theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// Homepage Widget Area
	register_sidebar(
		array(
			'name'          => __( 'Homepage Widget Area', 'flash-theme' ),
			'id'            => 'homepage-widget-area',
			'description'   => __( 'Widget area specifically for the homepage.', 'flash-theme' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'Vofa_widgets_init' );

// --- WordPress Optimizations & Hardening ---

/**
 * Remove WordPress version information from head and feeds for security.
 */
if ( ! function_exists( 'Vofa_remove_wp_version_info' ) ) {
	function Vofa_remove_wp_version_info() {
		return '';
	}
}
add_filter( 'the_generator', 'Vofa_remove_wp_version_info' );

/**
 * Remove unnecessary wp_head actions for optimization and minor security.
 * These actions are typically only output on the front-end.
 */
if ( ! function_exists( 'Vofa_optimize_wp_head' ) ) {
	function Vofa_optimize_wp_head() {
		remove_action( 'wp_head', 'rsd_link' ); // Really Simple Discovery link.
		remove_action( 'wp_head', 'wlwmanifest_link' ); // Windows Live Writer manifest link.
		remove_action( 'wp_head', 'wp_generator' ); // WordPress version.
		remove_action( 'wp_head', 'start_post_rel_link' ); // Link to start post. Deprecated.
		remove_action( 'wp_head', 'index_rel_link' ); // Link to site index.
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' ); // Links for next/previous posts. Deprecated.
		// Note: The 'atom_service_url_filter' line from original code was removed as 'atom_service_url_filter' is not a standard WP callback.
		// Disabling XML-RPC (see below) is more effective for RSD if XML-RPC is not needed.
	}
}
add_action( 'init', 'Vofa_optimize_wp_head' );


/**
 * Disable XML-RPC interface.
 * XML-RPC can be a target for attacks if not used. Consider if you need it for remote publishing or specific plugins.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Unset specific XML-RPC methods (less critical if xmlrpc_enabled is false, but good for defense in depth).
 */
if ( ! function_exists( 'Vofa_disable_xmlrpc_methods' ) ) {
	function Vofa_disable_xmlrpc_methods( $methods ) {
		unset( $methods['pingback.ping'] ); // Disable pingbacks via XML-RPC.
		// unset( $methods['another.method'] ); // Example: To disable other specific methods if XML-RPC were partially enabled.
		return $methods;
	}
}
// This filter might not run if xmlrpc_enabled is already false, but it's here for completeness.
add_filter( 'xmlrpc_methods', 'Vofa_disable_xmlrpc_methods' );


/**
 * Disable self-pingbacks (pinging your own site).
 */
if ( ! function_exists( 'Vofa_disable_self_pingbacks' ) ) {
	function Vofa_disable_self_pingbacks( &$links ) {
		$home_url = get_option( 'home' );
		foreach ( $links as $l => $link ) {
			if ( 0 === strpos( $link, $home_url ) ) {
				unset( $links[ $l ] );
			}
		}
	}
}
add_action( 'pre_ping', 'Vofa_disable_self_pingbacks' );

/**
 * Remove X-Pingback header from HTTP headers.
 */
if ( ! function_exists( 'Vofa_remove_x_pingback_header' ) ) {
	function Vofa_remove_x_pingback_header( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}
}
add_filter( 'wp_headers', 'Vofa_remove_x_pingback_header' );

/**
 * Remove pingback URL from bloginfo.
 */
if ( ! function_exists( 'Vofa_remove_pingback_url_bloginfo' ) ) {
	function Vofa_remove_pingback_url_bloginfo( $output, $show ) {
		if ( 'pingback_url' === $show ) {
			$output = '';
		}
		return $output;
	}
}
// This primarily targets get_bloginfo('pingback_url').
add_filter( 'bloginfo_url', 'Vofa_remove_pingback_url_bloginfo', 10, 2 );
// The filter on 'bloginfo' might be redundant if the above covers all cases, but kept from original for now.
add_filter( 'bloginfo', 'Vofa_remove_pingback_url_bloginfo', 10, 2 );


/**
 * Remove version parameters from CSS and JS files.
 * Caution: This can affect browser caching if file contents change but filenames don't.
 * WordPress uses version parameters for cache-busting.
 */
if ( ! function_exists( 'Vofa_remove_asset_version_params' ) ) {
	function Vofa_remove_asset_version_params( $src ) {
		if ( strpos( $src, '?ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		return $src;
	}
}
add_filter( 'style_loader_src', 'Vofa_remove_asset_version_params', 9999 );
add_filter( 'script_loader_src', 'Vofa_remove_asset_version_params', 9999 );


/**
 * Note on Moving Scripts to Footer:
 * The original code had a function 'doa_move_scripts_to_footer' that attempted to force all scripts
 * to the footer by re-hooking 'wp_enqueue_scripts'. This is highly problematic and can break
 * plugin functionality and WordPress core script loading.
 *
 * The correct way to load scripts in the footer is to use the $in_footer parameter (set to true)
 * when calling wp_enqueue_script():
 * e.g., wp_enqueue_script( 'my-script', 'path/script.js', array('jquery'), '1.0', true );
 *
 * Ensure all scripts enqueued by your theme use this method if they are safe to load in the footer.
 */


/**
 * Load latest jQuery from CDN and deregister WordPress's version on the front-end.
 * Includes a custom inline script.
 * Caution: Replacing core jQuery can lead to plugin compatibility issues. Test thoroughly.
 */
if ( ! function_exists( 'Vofa_load_cdn_jquery' ) ) {
	function Vofa_load_cdn_jquery() {
		if ( ! is_admin() ) { // Only modify jQuery on the front-end.
			wp_deregister_script( 'jquery-core' ); // Deregister WordPress's core jQuery.
			wp_register_script( 'jquery-core', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js', array(), '3.7.1', true );

			// WordPress uses 'jquery' as an alias for 'jquery-core'. Re-registering 'jquery' handle to point to the CDN version.
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js', array(), '3.7.1', true );
			
			// Optional: Deregister jQuery Migrate if you are sure no plugins/theme parts need it.
			// wp_deregister_script( 'jquery-migrate' );

			// Custom inline script dependent on jQuery.
			$custom_jquery_script = '
				jQuery(document).ready(function($) {
					// Safely use $ as an alias for jQuery within this function.
					$("#menu").click(function() {
						$("nav").slideToggle("slow");
					});
				});
			';
			// Add inline script after 'jquery' (our CDN jQuery) is enqueued.
			wp_add_inline_script( 'jquery', $custom_jquery_script );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'Vofa_load_cdn_jquery', 5 ); // Load early to replace before others enqueue.


/**
 * Enqueue WebFontLoader and load Open Sans font asynchronously.
 * Uses sessionStorage to avoid reloading fonts within the same session.
 */
if ( ! function_exists( 'Vofa_enqueue_open_sans_webfontloader' ) ) {
	function Vofa_enqueue_open_sans_webfontloader() {
		// Enqueue the WebFontLoader library with version number, load in footer.
		wp_enqueue_script( 'webfontloader', 'https://cdnjs.cloudflare.com/ajax/libs/webfont/1.6.28/webfontloader.js', array(), '1.6.28', true );

		// Add inline script to load fonts and check sessionStorage.
		$webfont_loader_script = "
            if (!sessionStorage.fontsLoadedFlashTheme) { // Use a theme-specific sessionStorage key
                WebFont.load({
                    google: {
                        families: ['Open+Sans:400,700&display=swap']
                    },
                    active: function() {
                        sessionStorage.fontsLoadedFlashTheme = true;
                        // console.log('Open Sans font loaded successfully via WebFontLoader.'); // Optional: for debugging
                    },
                    inactive: function() {
                        // console.log('Could not load Open Sans font via WebFontLoader.'); // Optional: for debugging
                    }
                });
            } else {
                // console.log('Open Sans font already loaded in this session (WebFontLoader).'); // Optional: for debugging
            }
        ";
		wp_add_inline_script( 'webfontloader', $webfont_loader_script );
	}
}
add_action( 'wp_enqueue_scripts', 'Vofa_enqueue_open_sans_webfontloader' );


/**
 * Note on Asynchronous CSS Loading (loadCSS):
 * The original code had a commented-out section for 'enqueue_loadcss'.
 * If you intend to load CSS asynchronously:
 * 1. Avoid hardcoding URLs; use WordPress functions like plugins_url(), get_stylesheet_directory_uri().
 * 2. For core stylesheets (like block-library), enqueue them via their handles: wp_enqueue_style('wp-block-library');
 * Then, you might filter 'style_loader_tag' to add rel="preload" and an onload handler for those specific handles.
 * 3. Ensure critical CSS (for above-the-fold content) is inlined or loaded synchronously to prevent FOUC.
 * Example of using Filament Group's loadCSS pattern:
 * <link rel="stylesheet" href="/path/to/mystyles.css" media="print" onload="this.media='all'">
 * <noscript><link rel="stylesheet" href="/path/to/mystyles.css"></noscript>
 */


/**
 * Note on Image Lazy Loading:
 * The original code contained commented-out custom functions for adding 'loading="lazy"' to images.
 * WordPress 5.5+ automatically adds 'loading="lazy"' to images by default (controlled by 'wp_lazy_loading_enabled' filter).
 * Custom functions are generally not needed for this anymore unless you require more specific logic
 * or support for browsers that don't understand loading="lazy" (though this is rare now).
 */


/**
 * Note on Deferring JavaScript:
 * The original code had a commented-out 'defer_js' function using 'script_loader_tag'.
 * For scripts you enqueue yourself via wp_enqueue_script(), the modern WordPress way (since 6.3)
 * to add 'defer' or 'async' attributes is using wp_script_add_data():
 *
 * wp_enqueue_script( 'my-handle', 'path/to/script.js', [], '1.0', true ); // true for footer
 * wp_script_add_data( 'my-handle', 'strategy', 'defer' );
 *
 * For modifying scripts enqueued by third-party plugins or themes, the 'script_loader_tag' filter
 * is still valid, but ensure the logic for adding the attribute is correct (modifying the tag, not the URL).
 */

// --- End of WordPress Optimizations & Hardening ---

// You can add other theme-specific functions below.

?>
