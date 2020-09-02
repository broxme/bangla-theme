<?php
/**
 * Set up the WordPress core custom header feature.
 * @uses bangla_header_style() fuzzy function for validation
 */
function bangla_custom_header_setup() {
	add_theme_support( 'custom-header');
}
add_action( 'after_setup_theme', 'bangla_custom_header_setup' );
