<?php
/**
 * bangla functions and definitions.
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package bangla
 */

define('BANGLA_VER', wp_get_theme()->get('Version'));
define( 'BANGLA__FILE__', __FILE__ );
define( 'BANGLA_PNAME', basename( dirname(BANGLA__FILE__)) );
define( 'BANGLA_PATH', get_template_directory() );
define( 'BANGLA_MODULES_PATH', BANGLA_PATH . 'modules/' );
define( 'BANGLA_URL', get_template_directory_uri() );
define( 'BANGLA_ASSETS_URL', BANGLA_URL . 'assets/' );
define( 'BANGLA_MODULES_URL', BANGLA_URL . 'modules/' );

// Check if in the admin
/**
 * Some parts of the framework only need to run on admin views.
 * These would be those.
 * Calling these only on admin saves some operation time for the theme, everything in the name of speed.
 */

$bangla_is_admin = is_admin();

require_once(get_template_directory() . '/inc/helper-functions.php');
require_once(get_template_directory() . '/admin/dom-helper.php');
require_once(get_template_directory() . '/inc/nav_walker.php');
require_once(get_template_directory() . '/inc/menu_options.php');


// Custom Widgets declare here
require_once(get_template_directory() . '/inc/sidebars.php');

// Theme customizer integration
require_once(get_template_directory() . '/customizer/theme-customizer.php');

// Revolution slider related tweak
require_once(get_template_directory() . '/admin/revolution-slider.php');

//TGM Plugin Activation all required plugin install by this script
require_once(get_template_directory() . '/inc/plugin-activation.php');

// Sidebar Generator plugin this gives you make custom sidebar for your page
require_once(get_template_directory() . '/inc/sidebar-generator.php');

/* 
Meta Box Plugin + related addon loaded here.
This plugin and addons gives you some extra facelities for your page
*/
define( 'BANGLA_MB_URL', trailingslashit( get_template_directory_uri() . '/inc/meta-box' ) );
define( 'BANGLA_MB_DIR', trailingslashit( get_template_directory() . '/inc/meta-box' ) );
require_once BANGLA_MB_DIR . 'meta-box.php';
require_once BANGLA_MB_DIR . 'meta-box-tabs/meta-box-tabs.php'; // Include Tabs Extension
require_once BANGLA_MB_DIR . 'meta-box-conditional-logic/meta-box-conditional-logic.php'; // Include Conditional Logic Extension
require_once BANGLA_MB_DIR . 'meta-box-yoast-seo/mb-yoast-seo.php'; // Include Conditional Logic Extension
require_once get_template_directory() . '/inc/meta-boxes.php';


// Breadcrumb functionalities you get here
require_once(get_template_directory().'/inc/breadcrumbs.php');

// WooCommerce integration
if (class_exists('Woocommerce')) {
	require_once(get_template_directory() . '/inc/woocommerce.php');
}

// enqueue style and script from this file
require_once(get_template_directory().'/inc/enqueue.php');



if ( ! function_exists( 'bangla_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bangla_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on bangla, use a find and replace
	 * to change 'bangla' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'bangla', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	// Post Formats
	add_theme_support( 'post-formats', array('gallery', 'link', 'quote', 'audio', 'video'));

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	if ( function_exists('add_image_size')) {
		add_image_size( 'bangla_blog', 1200, 800, true); // Standard Blog Image
	}

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary'   => esc_html_x('Primary Menu', 'backend', 'bangla'),
		'offcanvas' => esc_html_x('Offcanvas Menu', 'backend', 'bangla'),
		'toolbar'   => esc_html_x('Toolbar Menu', 'backend', 'bangla'),
		'copyright' => esc_html_x('Copyright Menu', 'backend', 'bangla'),
	));

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background');

	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 *
	 * @global int $content_width
	 */
	$GLOBALS['content_width'] = apply_filters( 'bangla_content_width', 890 );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( 'admin/css/editor-style.css' );

	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;

add_action( 'after_setup_theme', 'bangla_setup' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';



/* 
 * Custom Excerpts control
 */

// Set new Default Excerpt Length
function bangla_new_excerpt_length($length) {
    return 200;
}
add_filter('excerpt_length', 'bangla_new_excerpt_length');

// Custom Excerpt Length
function bangla_custom_excerpt($limit=50) {
    return strip_shortcodes(wp_trim_words(get_the_content(), $limit, '...'));
}

// Word Limiter
function bangla_limit_words($string, $word_limit) {
	$words = explode(' ', $string);
	return implode(' ', array_slice($words, 0, $word_limit));
}

// Remove Shortcodes from Search Results Excerpt
function bangla_remove_shortcode_from_excerpt($excerpt) {
  if ( is_search() ) {
    $excerpt = strip_shortcodes( $excerpt );
  }
  return $excerpt;
}
add_filter('the_excerpt', 'bangla_remove_shortcode_from_excerpt');

/* custom sanitization */
function bangla_stripslashes($string) {
    if(get_magic_quotes_gpc()) {
        return stripslashes($string);
    } else {
        return $string;
    }
}

/**
 * sanitize text for customizer
 */
function bangla_sanitize_text($string) {
	return bangla_stripslashes(htmlspecialchars($string));
}

/**
 * sanitize text for html control
 */
function bangla_sanitize_text_decode($string) {
	return bangla_stripslashes(htmlspecialchars_decode($string));
}



/**
 * Add pre-connect for Google Fonts.
 *
 * @since 1.2.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function bangla_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'bangla-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'bangla_resource_hints', 10, 2 );

/*
 * Helper - expand allowed tags()
 * Source: https://gist.github.com/adamsilverstein/10783774
*/
function bangla_allowed_tags() {
	$allowed_tag = wp_kses_allowed_html( 'post' );
	// iframe
	$allowed_tag['iframe'] = array(
		'src'             => array(),
		'height'          => array(),
		'width'           => array(),
		'frameborder'     => array(),
		'allowfullscreen' => array(),
	); 
	return $allowed_tag;
}


// set custom menu walker for get facility for megamenu style and all others benefit from here.
add_filter('wp_nav_menu_args', function($args) {
    if (empty($args['walker'])) {
        $args['walker'] = new bangla_menu_walker;
    }
    return $args; }
);
