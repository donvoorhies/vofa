<?php
/*! 
Prevents WordPress from testing ssl capability on domain.com/xmlrpc.php?rsd 
#Speed-optimization 
*/
remove_filter('atom_service_url','atom_service_url_filter');

/*! 
Remove version info from head and feeds 
#Security/Hardening 
*/
add_filter('the_generator', 'complete_version_removal');
function complete_version_removal() {
    return '';
}
/*! 
Remove unnecessary wp_head actions 
#Optimization
*/
add_action('init', 'optimize_head');
    function optimize_head() {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'start_post_rel_link');
        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    }

/*!
Disabling pingback and trackback notifications
#Optimization
*/
add_action( 'pre_ping', 'wp_internal_pingbacks' );
add_filter( 'wp_headers', 'wp_x_pingback');
add_filter( 'bloginfo_url', 'wp_pingback_url') ;
add_filter( 'bloginfo', 'wp_pingback_url') ;
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'xmlrpc_methods', 'wp_xmlrpc_methods' );

/*! 
Disable internal pingbacks
*/
function wp_internal_pingbacks( &$links ) { 
    foreach ( $links as $l => $link ) {
        if ( 0 === strpos( $link, get_option( 'home' ) ) ) {
            unset( $links[$l] );
        }
    }
}

/*! 
Disable x-pingback
*/
function wp_x_pingback( $headers ) { 
    unset( $headers['X-Pingback'] );
    return $headers;
}

/*! 
Remove pingback URLs
*/
function wp_pingback_url( $output, $show='') { 
    if ( $show == 'pingback_url' ) $output = '';
    return $output;
}

/*! 
Disable XML-RPC methods 
*/
function wp_xmlrpc_methods( $methods ) { 
    unset( $methods['pingback.ping'] );
    return $methods;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*! 
Disbable Self-Pingbacks 
*/
add_action( 'pre_ping', 'wpsites_disable_self_pingbacks' );
function wpsites_disable_self_pingbacks( &$links ) {
 foreach ( $links as $l => $link )
 if ( 0 === strpos( $link, get_option( 'home' ) ) )
 unset($links[$l]);
}

/*! 
Removes wp-version number params (scopes) from scripts and styles
This code-block has replaced a previously used code, as it's deemed more efficient/-plays well with the rest of the code.
*/
	
add_filter( 'style_loader_src', 'remove_css_js_version', 9999 );
add_filter( 'script_loader_src', 'remove_css_js_version', 9999 );	
function remove_css_js_version( $src ) {
    if( strpos( $src, '?ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}

/*!
Move all enqueued scripts to the footer to improve page load speed
*/
add_action('wp_enqueue_scripts', 'doa_move_scripts_to_footer');
function doa_move_scripts_to_footer() {
    remove_action('wp_head', 'wp_print_scripts');
    remove_action('wp_head', 'wp_print_head_scripts', 9);
    remove_action('wp_head', 'wp_enqueue_scripts', 1);

    add_action('wp_footer', 'wp_print_scripts', 5);
    add_action('wp_footer', 'wp_enqueue_scripts', 5);
    add_action('wp_footer', 'wp_print_head_scripts', 5);
}



/*! 
Register a new sidebar
*/
function add_widget_support() {
    register_sidebar(array(
        'name'          => 'Sidebar',
        'id'            => 'sidebar',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'add_widget_support');

/*
Register a new navigation menu
*/
function add_main_nav() {
    register_nav_menu('header-menu', __('Header Menu'));
}
add_action('init', 'add_main_nav');



/*!
Enqueue Web Font Loader and load Open Sans font
(https://github.com/typekit/webfontloader)
*/
function open_sans_font_loader_enqueue_scripts() {
    wp_enqueue_script('webfontloader', 'https://cdnjs.cloudflare.com/ajax/libs/webfont/1.6.28/webfontloader.js', array(), null, true);

    wp_add_inline_script('webfontloader', '
        WebFont.load({
            google: {
                families: ["Open+Sans:400,700&display=swap"]
            },
 active: () => {
                    sessionStorage.fontsLoaded = true
                },
        });
    ');
}
add_action('wp_enqueue_scripts', 'open_sans_font_loader_enqueue_scripts');

/*!
Enqueue loadCSS and add inline script to load stylesheets asynchronously
(https://github.com/filamentgroup/loadCSS)
*/
function enqueue_loadcss() {
    wp_register_script('loadcss', 'https://cdnjs.cloudflare.com/ajax/libs/loadCSS/3.1.0/loadCSS.min.js', array(), '3.1.0', true);
    wp_enqueue_script('loadcss');
    $normalize_url = 'https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css';
    /*$cf7_url = 'https://wordpress19.voorhies.dk/wp-content/plugins/contact-form-7/includes/css/styles.css';*/
/**If you do NOT use Contact Form 7, then either leave the string above commented or remove - otherwise remove the comments if the oppostie is the case*/
    $main_stylesheet_url = get_stylesheet_uri();
    $block_stylesheet_url = 'https://wordpress19.voorhies.dk/wp-includes/css/dist/block-library/style.min.css';

    $inline_script = "
        function onloadCSS(ss, callback) {
            ss.onload = function() {
                ss.onload = null;
                if (callback) {
                    callback.call(ss);
                }
            };
            if ('isApplicationInstalled' in ss) {
                if (callback) {
                    callback.call(ss);
                }
            }
        }
        /**var normalizeStylesheet = loadCSS('$normalize_url');
	var cf7Stylesheet = loadCSS('$cf7_url')*/
 /**As earlier, if you do NOT use Contact Form 7, then either leave the strings above commented or remove - otherwise remove the comments if the oppostie is the case*/

        var mainStylesheet = loadCSS('$main_stylesheet_url');
		var blockStylesheet = loadCSS('$block_stylesheet_url');
        
		onloadCSS(normalizeStylesheet, function() {
        console.log('Normalize.css has loaded.');
        });
		
		/** onloadCSS(cf7Stylesheet, function() {
		console.log('cf7.css has loaded.');
        });*/
 /**As earlier, if you do NOT use Contact Form 7, then either leave the strings above commented or remove - otherwise remove the comments if the oppostie is the case*/

		onloadCSS(mainStylesheet, function() {
		console.log('Main stylesheet has loaded.');
        });
		
		onloadCSS(blockStylesheet, function() {
		console.log('Block stylesheet has loaded.');
        });
    ";
    wp_add_inline_script('loadcss', $inline_script);
}
add_action('wp_enqueue_scripts', 'enqueue_loadcss');

/*!
Deregister default jQuery and load the latest version from Google CDN with custom script
*/
function load_latest_jquery() {
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js', array(), null, true);

    wp_add_inline_script('jquery', '
        $(document).ready(function() {
            $("#menu").click(function() {
                $("nav").slideToggle("slow");
            });
        });
    ');
}
add_action('wp_enqueue_scripts', 'load_latest_jquery');


/*
 * function speed_optimizer_add_lazyload_to_images($content) {
    // Check if we're on the index page
    if (is_home()) {
        // Do not modify the content on the index page
        return $content;
    }

    // Add loading="lazy" to all other images
    $content = preg_replace('/<img(.*?)src=/', '<img$1loading="lazy" src=', $content);
    return $content;
}

add_filter('the_content', 'speed_optimizer_add_lazyload_to_images');
add_filter('post_thumbnail_html', 'speed_optimizer_add_lazyload_to_images');
add_filter('widget_text', 'speed_optimizer_add_lazyload_to_images');
*/


function flash_theme_setup() {
/*! 
Add support for title tag
*/
 add_theme_support('title-tag');

    
/*Register nav menus
*/    
register_nav_menus(array(
        'main-menu' => __('Main Menu', 'flash-theme'),
        'footer-menu' => __('Footer Menu', 'flash-theme')
    ));

    // Add support for post thumbnails
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'flash_theme_setup');

// Enqueue styles and scripts
function flash_theme_enqueue_styles() {
    wp_enqueue_style('flash-theme-style', get_stylesheet_uri());
    wp_enqueue_script('fade-transition', get_template_directory_uri() . '/fade-transition.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'flash_theme_enqueue_styles');

// Register widget area
function flash_theme_widgets_init() {
    register_sidebar(array(
        'name' => __('Homepage Widget Area', 'flash-theme'),
        'id' => 'homepage-widget-area',
        'before_widget' => '<div class="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'flash_theme_widgets_init');
