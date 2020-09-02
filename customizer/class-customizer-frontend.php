<?php
/**
 * Class which handles the output of the WP customizer on the frontend.
 * Meaning that this stuff loads always, no matter if the global $wp_cutomize
 * variable is present or not.
 */
class bangla_Customize_Frontent {

	/**
	 * Add actions to load the right staff at the right places (header, footer).
	 */
	function __construct() {
		add_action( 'wp_enqueue_scripts' , array( $this, 'customizer_css' ), 20 );
		add_action( 'wp_head' , array( $this, 'head_output' ) );
	}

	/**
	* This will output the custom WordPress settings to the live theme's WP head.
	* Used by hook: 'wp_head'
	* @see add_action( 'wp_head' , array( $this, 'head_output' ) );
	*/
	public static function customizer_css() {
		// customizer settings
		$cached_css = get_theme_mod( 'cached_css', '' );

		ob_start();

		echo '/* WP Customizer start */' . PHP_EOL;
		echo apply_filters( 'bangla/cached_css', $cached_css );
		echo '/* WP Customizer end */';

		wp_add_inline_style( 'bangla-style', ob_get_clean() );
	}
        

	/**
	* Outputs the code in head of the every page
	* Used by hook: add_action( 'wp_head' , array( $this, 'head_output' ) );
	*/
	public static function head_output() {

		// Theme favicon output, which will be phased out, because of WP core favicon integration
		$favicon = get_theme_mod( 'site_icon', get_template_directory_uri() . '/images/favicon.png' );
		if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {
			if( ! empty( $favicon ) ) : ?>
				<link rel="shortcut icon" href="<?php echo esc_attr($favicon); ?>">
				<link href="<?php echo esc_attr($favicon); ?>" sizes="152x152" rel="apple-touch-icon-precomposed">
			<?php endif;
		} ?>

		<meta name="theme-color" content="<?php echo get_theme_mod( 'browser_header_color', '#f14b51' ); ?>">
		<meta name="msapplication-navbutton-color" content="<?php echo get_theme_mod( 'browser_header_color', '#f14b51' ); ?>">


		<?php
	}

}