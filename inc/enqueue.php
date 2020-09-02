<?php

/**
 * Admin related stylesheets
 * @return [type] [description]
 */
function bangla_admin_style() {
	wp_register_style( 'admin-setting', get_template_directory_uri() . '/admin/css/admin-settings.css' );
	wp_enqueue_style( 'admin-setting' );
}
add_action( 'admin_enqueue_scripts', 'bangla_admin_style' );


/**
 * Admin related scripts
 * @return [type] [description]
 */
function bangla_admin_script() {
	wp_register_script('admin-setting', get_template_directory_uri() . '/admin/js/admin-settings.js', array( 'jquery' ), bangla_VER, true);

	wp_enqueue_script('admin-setting');
}
add_action( 'admin_enqueue_scripts', 'bangla_admin_script' );


/**
 * Site Stylesheets
 * @return [type] [description]
 */
function bangla_styles() {

	$rtl_enabled = is_rtl();

	// Load Primary Stylesheet
	if (!class_exists('ElementPack\Element_Pack_Loader')) {
		if ($rtl_enabled) {
			wp_enqueue_style( 'theme-style', BANGLA_URL .'/css/theme.rtl.css', array(), BANGLA_VER, 'all' );
		} else {
			wp_enqueue_style( 'theme-style', BANGLA_URL .'/css/theme.css', array(), BANGLA_VER, 'all' );
		}
	}
	if (class_exists('Woocommerce')) {
		wp_enqueue_style( 'theme-style', BANGLA_URL .'/css/woocommerce.css', array(), BANGLA_VER, 'all' );
	}

	if (get_theme_mod( 'bangla_header_txt_style', false )) {
		wp_enqueue_style( 'theme-style', BANGLA_URL .'/css/inverse.css', array(), BANGLA_VER, 'all' );
	}

	wp_enqueue_style( 'theme-fonts', bangla_fonts_url(), array(), null );
	wp_enqueue_style( 'bangla-style', get_stylesheet_uri(), array(), BANGLA_VER, 'all' );

}  
add_action( 'wp_enqueue_scripts', 'bangla_styles' );


function bangla_fonts_url() {
	$fonts_url = '';

	
	$theme_font = _x( 'on', 'Libre Franklin font: on or off', 'bangla' );

	if ( 'off' !== $theme_font ) {
		$font_families = array();

		$font_families[] = 'Roboto:300,300i,400,400i,700';
		$font_families[] = 'Open Sans:300,300i,400,400i,700';

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}


/**
 * Site Scripts
 * @return [type] [description]
 */
function bangla_scripts() {

	$suffix    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	$preloader = get_theme_mod('bangla_preloader');

	if ($preloader) {
		wp_enqueue_script('please-wait', BANGLA_URL . '/js/please-wait.min.js', array(), BANGLA_VER, false);
	}
	// wp_dequeue_script('broxme_wp-uikit');
	if (!class_exists('ElementPack\Element_Pack_Loader')) {
		wp_register_script( 'broxme_wp-uikit', BANGLA_URL . '/js/broxme_wp-uikit' . $suffix . '.js', ['jquery'], '3.0.0.42', true );
		wp_register_script( 'broxme_wp-uikit-icons', BANGLA_URL . '/js/broxme_wp-uikit-icons' . $suffix . '.js', ['jquery', 'broxme_wp-uikit'], '3.0.0.42', true );

	} else {
		wp_register_script( 'broxme_wp-uikit', WP_PLUGIN_URL . '/broxme_wp-element-pack/assets/js/broxme_wp-uikit' . $suffix . '.js', ['jquery'], '3.0.0.42', true );
		wp_register_script( 'broxme_wp-uikit-icons', WP_PLUGIN_URL . '/broxme_wp-element-pack/assets/js/broxme_wp-uikit-icons' . $suffix . '.js', ['jquery', 'broxme_wp-uikit'], '3.0.0.42', true );
		
	}

	wp_enqueue_script('broxme_wp-uikit');
	wp_enqueue_script('broxme_wp-uikit-icons');

	wp_register_script('cookie-bar', BANGLA_URL . '/js/jquery.cookiebar.js', array( 'jquery' ), BANGLA_VER, true);
	wp_register_script('ease-scroll', BANGLA_URL . '/js/jquery.easeScroll.js', array( 'jquery' ), BANGLA_VER, true);
	wp_register_script('bangla-script', BANGLA_URL . '/js/theme.js', array( 'jquery' ), BANGLA_VER, true);
	// Enqueue
	wp_enqueue_script('cookie-bar');
	wp_enqueue_script('ease-scroll');
	wp_enqueue_script('bangla-script');

  	// Load WP Comment Reply JS
  	if(is_singular()) { wp_enqueue_script( 'comment-reply' ); }
}

add_action( 'wp_enqueue_scripts', 'bangla_scripts' );  