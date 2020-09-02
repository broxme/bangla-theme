<?php
function bangla_customize_register_titlebar($wp_customize) {
	//header section
	$wp_customize->add_section('header', array(
		'title' => esc_attr__('Titlebar', 'bangla'),
		'priority' => 31
	));

	$wp_customize->add_setting('bangla_global_header', array(
		'default' => 'title',
		'sanitize_callback' => 'bangla_sanitize_choices'
	));
	$wp_customize->add_control('bangla_global_header', array(
		'label'    => esc_attr__('Titlebar Layout', 'bangla'),
		'section'  => 'header',
		'settings' => 'bangla_global_header', 
		'type'     => 'select',
		'priority' => 1,
		'choices'  => array(
			'title'               => esc_attr__('Titlebar (Left Align)', 'bangla'),
			'featuredimagecenter' => esc_attr__('Titlebar (Center Align)', 'bangla'),
			'notitle'             => esc_attr__('No Titlebar', 'bangla')
		)
	));


	$wp_customize->add_setting('bangla_titlebar_style', array(
		'default' => 'titlebar-dark',
		'sanitize_callback' => 'bangla_sanitize_choices'
	));
	$wp_customize->add_control('bangla_titlebar_style', array(
		'label'    => esc_attr__('Titlebar Style', 'bangla'),
		'section'  => 'header',
		'settings' => 'bangla_titlebar_style', 
		'type'     => 'select',
		'priority' => 1,
		'choices'  => array(
			'titlebar-dark' => esc_attr__('Dark (for dark backgrounds)', 'bangla'),
			'titlebar-light' => esc_attr__('Light (for light backgrounds)', 'bangla')
		)
	));

	$wp_customize->add_setting( 'bangla_titlebar_bg_image' , array(
		'sanitize_callback' => 'esc_url'
	));
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bangla_titlebar_bg_image', array(
		'priority' => 1,
	    'label'    => esc_attr__( 'Titlebar Background', 'bangla' ),
	    'section'  => 'header',
	    'settings' => 'bangla_titlebar_bg_image'
	)));

	$wp_customize->add_setting('bangla_blog_title', array(
		'default' => esc_attr__('Blog', 'bangla'),
		'sanitize_callback' => 'esc_attr'
	));
	$wp_customize->add_control('bangla_blog_title', array(
		'priority' => 2,
	    'label'    => esc_attr__('Blog Title: ', 'bangla'),
	    'section'  => 'header',
	    'settings' => 'bangla_blog_title'
	));

	if (class_exists('Woocommerce')){
		$wp_customize->add_setting('bangla_woocommerce_title', array(
			'default' => esc_attr__('Shop', 'bangla'),
			'sanitize_callback' => 'esc_attr'
		));
		$wp_customize->add_control('bangla_woocommerce_title', array(
			'priority' => 3,
		    'label'    => esc_attr__('WooCommerce Title: ', 'bangla'),
		    'section'  => 'header',
		    'settings' => 'bangla_woocommerce_title'
		));
	}
	
	$wp_customize->add_setting('bangla_right_element', array(
		'default' => 'back_button',
		'sanitize_callback' => 'bangla_sanitize_choices'
	));
	$wp_customize->add_control('bangla_right_element', array(
		'label'    => esc_attr__('Right Element', 'bangla'),
		'section'  => 'header',
		'settings' => 'bangla_right_element', 
		'type'     => 'select',
		'priority' => 4,
		'choices'  => array(
			0             => esc_attr__('Nothing', 'bangla'),
			'back_button' => esc_attr__('Back Button', 'bangla'),
			'breadcrumb'  => esc_attr__('Breadcrumb', 'bangla')
		)
	));

}

add_action('customize_register', 'bangla_customize_register_titlebar');