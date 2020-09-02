<?php
/**
 * Contains methods for customizing the theme customization screen.
 *
 * @package bangla
 * @link http://codex.wordpress.org/Theme_Customization_API
 */

class bangla_Customizer_Base {
	/**
	 * The singleton manager instance
	 *
	 * @see wp-includes/class-wp-customize-manager.php
	 * @var WP_Customize_Manager
	 */
	protected $wp_customize;

	public function __construct( WP_Customize_Manager $wp_manager ) {
		// set the private propery to instance of wp_manager
		$this->wp_customize = $wp_manager;

		// register the settings/panels/sections/controls, main method
		$this->register();

		/**
		 * Action and filters
		 */

		// render the CSS and cache it to the theme_mod when the setting is saved
		add_action( 'customize_save_after' , array( $this, 'cache_rendered_css' ) );

		// save logo width/height dimensions
		add_action( 'customize_save_logo_img' , array( $this, 'save_logo_dimensions' ), 10, 1 );

		// flush the rewrite rules after the customizer settings are saved
		add_action( 'customize_save_after', 'flush_rewrite_rules' );

		// handle the postMessage transfer method with some dynamically generated JS in the footer of the theme
		add_action( 'wp_footer', array( $this, 'customize_footer_js' ), 30 );
		add_action('wp_head',array( $this, 'hook_custom_css' ));


	}

	/**
	* This hooks into 'customize_register' (available as of WP 3.4) and allows
	* you to add new sections and controls to the Theme Customize screen.
	*
	* Note: To enable instant preview, we have to actually write a bit of custom
	* javascript. See live_preview() for more.
	*
	* @see add_action('customize_register',$func)
	*/
	public function register () {
		/**
		 * Settings
		 */

		//$this->wp_customize->remove_section( 'colors');
		$this->wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$this->wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';



		$this->wp_customize->add_setting( 'bangla_logo_default' , array(
			'sanitize_callback' => 'esc_url'
		));
		$this->wp_customize->add_control( new WP_Customize_Image_Control( $this->wp_customize, 'bangla_logo_default', array(
			'priority' => 101,
		    'label'    => esc_html_x( 'Default Logo', 'backend', 'bangla' ),
		    'section'  => 'title_tagline',
		    'settings' => 'bangla_logo_default'
		)));

		$this->wp_customize->add_setting('bangla_logo_width_default', array(
			'sanitize_callback' => 'bangla_sanitize_text'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_logo_width_default', array(
			'label'       => esc_html_x('Default Logo Width', 'backend', 'bangla'),
			'description' => esc_html_x('This is an optional width (example: 150px) settings. maybe this not need if you use 150px x 32px logo.' , 'backend', 'bangla'),
			'priority' => 102,
			'section'     => 'title_tagline',
			'settings'    => 'bangla_logo_width_default', 
			'type'        => 'text',
		)));

		$this->wp_customize->add_setting( 'bangla_logo_mobile' , array(
			'sanitize_callback' => 'esc_url'
		));
		$this->wp_customize->add_control( new WP_Customize_Image_Control( $this->wp_customize, 'bangla_logo_mobile', array(
			'priority' => 103,
		    'label'    => esc_html_x( 'Mobile Logo', 'backend', 'bangla' ),
		    'section'  => 'title_tagline',
		    'settings' => 'bangla_logo_mobile'
		)));


		$this->wp_customize->add_setting('bangla_logo_width_mobile', array(
			'sanitize_callback' => 'bangla_sanitize_text'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_logo_width_mobile', array(
			'label'       => esc_html_x('Mobile Logo Width', 'backend', 'bangla'),
			'description' => esc_html_x('This is an optional width (example: 150px) settings. maybe this not need if you use 150px x 32px logo.' , 'backend', 'bangla'),
			'priority' => 104,
			'section'     => 'title_tagline',
			'settings'    => 'bangla_logo_width_mobile', 
			'type'        => 'text',
		)));

		$this->wp_customize->add_setting('bangla_mobile_logo_align', array(
			'default' => 'center',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_mobile_logo_align', array(
			'label'    => esc_html_x('Mobile Logo Align', 'backend', 'bangla'),
			'section'  => 'title_tagline',
			'settings' => 'bangla_mobile_logo_align', 
			'type'     => 'select',
			'choices'  => array(
				'' => esc_html_x('Hide', 'backend', 'bangla'),
				'left' => esc_html_x('Left', 'backend', 'bangla'),
				'right' => esc_html_x('Right', 'backend', 'bangla'),
				'center' => esc_html_x('Center', 'backend', 'bangla'),
			),
			'priority' => 106,
		)));


		$this->wp_customize->add_section('toolbar', array(
			'title' => esc_html_x('Toolbar', 'backend', 'bangla'),
			'priority' => 28
		));

		$this->wp_customize->add_setting('bangla_toolbar', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_toolbar', array(
			'label'    => esc_html_x('Show Toolbar', 'backend', 'bangla'),
			'section'  => 'toolbar',
			'settings' => 'bangla_toolbar', 
			'type'     => 'select',
			'choices'  => array(
				1 => esc_html_x('Yes', 'backend', 'bangla'),
				0 => esc_html_x('No', 'backend', 'bangla'),
			)
		)));

		$this->wp_customize->add_setting('bangla_toolbar_fullwidth', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_toolbar_fullwidth', array(
			'label'       => esc_html_x('Toolbar Fullwidth', 'backend', 'bangla'),
			'description' => esc_html_x('(Make your tolbar full width like fluid width.)', 'backend', 'bangla'),
			'section'     => 'toolbar',
			'settings'    => 'bangla_toolbar_fullwidth', 
			'type'        => 'checkbox',
			'active_callback' => 'bangla_toolbar_check',
		)));


		// Add footer text color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'toolbar_text_color', array(
			'default'           => '#999999',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.broxme_wp-toolbar',
					'.broxme_wp-toolbar a',
					'.broxme_wp-toolbar .broxme_wp-subnav>*>:first-child',
				),
				'color|lighten(30)' => array(
					'.broxme_wp-toolbar a:hover',
					'.broxme_wp-toolbar .broxme_wp-subnav>*>a:hover', 
					'.broxme_wp-toolbar .broxme_wp-subnav>*>a:focus',
					'.broxme_wp-toolbar .broxme_wp-subnav>.broxme_wp-active>a',
				),
			)
		)));

		// Add toolbar text color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'toolbar_text_color', array(
			'label'       => esc_html_x( 'Text Color', 'backend', 'bangla' ),
			'section'     => 'toolbar',
			'active_callback' => 'bangla_toolbar_check',
		)));

		// Add toolbar background color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'toolbar_background_color', array(
			'default'           => '#222222',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'background-color' => array(
					'.broxme_wp-toolbar',
				),
				'border-color|lighten(15)' => array(
					'.broxme_wp-toolbar .broxme_wp-subnav-divider>:nth-child(n+2):not(.broxme_wp-first-column)::before',
				),
			)
		)));

		// Add toolbar background color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'toolbar_background_color', array(
			'label'       => esc_html_x( 'Background Color', 'backend', 'bangla' ),
			'section'     => 'toolbar',
			'active_callback' => 'bangla_toolbar_check',
		)));


		$this->wp_customize->add_setting('bangla_toolbar_left', array(
			'default' => 'tagline',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_toolbar_left', array(
			'label'           => esc_html_x('Toolbar Left Area', 'backend', 'bangla'),
			'section'         => 'toolbar',
			'settings'        => 'bangla_toolbar_left', 
			'active_callback' => 'bangla_toolbar_check',
			'type'            => 'select',
			'choices'         => $this->bangla_toolbar_left_elements()
		)));

		$this->wp_customize->add_setting('bangla_toolbar_left_hide_mobile', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_toolbar_left_hide_mobile', array(
			'label'           => esc_html_x('Hide in mobile mode', 'backend', 'bangla'),
			'section'         => 'toolbar',
			'settings'        => 'bangla_toolbar_left_hide_mobile', 
			'type'            => 'checkbox',
			'active_callback' => 'bangla_toolbar_check',
		)));


		$this->wp_customize->add_setting('bangla_toolbar_left_custom', array(
			'sanitize_callback' => 'bangla_sanitize_textarea'
		));
		$this->wp_customize->add_control( new bangla_Customize_Textarea_Control( $this->wp_customize, 'bangla_toolbar_left_custom', array(
			'label'           => esc_html_x('Toolbar Left Custom Text', 'backend', 'bangla'),
			'section'         => 'toolbar',
			'settings'        => 'bangla_toolbar_left_custom',
			'active_callback' => 'bangla_toolbar_left_custom_check',
			'type'            => 'textarea',
		)));

		$this->wp_customize->add_setting('bangla_toolbar_right', array(
			'default' => 'social',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_toolbar_right', array(
			'label'           => esc_html_x('Toolbar Right Area', 'backend', 'bangla'),
			'description' 	  => (get_theme_mod( 'bangla_woocommerce_cart' ) == 'toolbar') ? esc_html_x('This element automatically hide on mobile mode, for better preview shopping cart.', 'backend', 'bangla') : '',
			'section'         => 'toolbar',
			'settings'        => 'bangla_toolbar_right', 
			'active_callback' => 'bangla_toolbar_check',
			'type'            => 'select',
			'choices'         => $this->bangla_toolbar_right_elements()
		)));

		$this->wp_customize->add_setting('bangla_toolbar_right_hide_mobile', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_toolbar_right_hide_mobile', array(
			'label'           => esc_html_x('Hide in mobile mode', 'backend', 'bangla'),
			'section'         => 'toolbar',
			'settings'        => 'bangla_toolbar_right_hide_mobile', 
			'type'            => 'checkbox',
			'active_callback' => 'bangla_toolbar_check',
		)));


		$this->wp_customize->add_setting('bangla_toolbar_right_custom', array(
			'sanitize_callback' => 'bangla_sanitize_textarea'
		));
		$this->wp_customize->add_control( new bangla_Customize_Textarea_Control( $this->wp_customize, 'bangla_toolbar_right_custom', array(
			'label'           => esc_html_x('Toolbar Right Custom Text', 'backend', 'bangla'),
			'section'         => 'toolbar',
			'settings'        => 'bangla_toolbar_right_custom',
			'active_callback' => 'bangla_toolbar_right_custom_check',
			'type'            => 'textarea',
		)));


		$this->wp_customize->add_setting('bangla_toolbar_social', array(
			'sanitize_callback' => 'esc_html'
		));
		$this->wp_customize->add_control( new bangla_Customize_Social_Control( $this->wp_customize, 'bangla_toolbar_social', array(
			'label'             => esc_html_x('Social Link', 'backend', 'bangla'),
			'description'       => esc_html_x('Enter up to 5 links to your social profiles.', 'backend', 'bangla'),
			'section'           => 'toolbar',
			'settings'          => 'bangla_toolbar_social',
			'type'              => 'social',
			'active_callback' => 'bangla_toolbar_social_check',
		)));

		$this->wp_customize->add_setting('bangla_toolbar_social_style', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_toolbar_social_style', array(
			'label'           => esc_html_x('Social link as button', 'backend', 'bangla'),
			'section'         => 'toolbar',
			'settings'        => 'bangla_toolbar_social_style', 
			'type'            => 'checkbox',
			'active_callback' => 'bangla_toolbar_social_check',
		)));
		
		

		/**
		 * General Customizer Settings
		 */

		//general section
		$this->wp_customize->add_section('general', array(
			'title' => esc_html_x('General', 'backend', 'bangla'),
			'priority' => 21
		));

		$this->wp_customize->add_setting('bangla_global_layout', array(
			'default' => 'full',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_global_layout', array(
			'label'    => esc_html_x('Global Layout', 'backend', 'bangla'),
			'section'  => 'general',
			'settings' => 'bangla_global_layout', 
			'type'     => 'select',
			'choices'  => array(
				'full'  => esc_html_x('Fullwidth', 'backend', 'bangla'),
				'boxed' => esc_html_x('Boxed', 'backend', 'bangla'),
			)
		)));

		$this->wp_customize->add_setting('bangla_comment_show', array(
			'default' => 1,
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_comment_show',
	        array(
				'label'       => esc_html_x('Show Global Page Comment', 'backend', 'bangla'),
				'description' => esc_html_x('Enable / Disable global page comments (not post comment).', 'backend', 'bangla'),
				'section'     => 'general',
				'settings'    => 'bangla_comment_show',
				'type'        => 'select',
				'choices'     => array(
					1 => esc_html_x('Yes', 'backend', 'bangla'),
					0 => esc_html_x('No', 'backend', 'bangla')
				)
	        )
		));

		$this->wp_customize->add_setting('bangla_offcanvas_search', array(
			'default' => 1,
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_offcanvas_search',
	        array(
				'label'       => esc_html_x('Offcanvas Search', 'backend', 'bangla'),
				'description' => esc_html_x('Enable / Disable Offcanvas search display', 'backend', 'bangla'),
				'section'     => 'general',
				'settings'    => 'bangla_offcanvas_search',
				'type'        => 'select',
				'choices'     => array(
					1 => esc_html_x('Yes', 'backend', 'bangla'),
					0 => esc_html_x('No', 'backend', 'bangla')
				)
	        )
		));


		//titlebar section
		$this->wp_customize->add_section('titlebar', array(
			'title' => esc_html_x('Titlebar', 'backend', 'bangla'),
			'priority' => 32,
			'active_callback' => 'bangla_titlebar_check'
		));

		$this->wp_customize->add_setting('bangla_titlebar_layout', array(
			'default' => 'left',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control('bangla_titlebar_layout', array(
			'label'    => esc_html_x('Titlebar Layout', 'backend', 'bangla'),
			'section'  => 'titlebar',
			'settings' => 'bangla_titlebar_layout', 
			'type'     => 'select',
			'priority' => 1,
			'choices'  => array(
				'left'   => esc_html_x('Left Align', 'backend', 'bangla'),
				'center'  => esc_html_x('Center Align', 'backend', 'bangla'),
				'right'  => esc_html_x('Right Align', 'backend', 'bangla'),
				'notitle' => esc_html_x('No Titlebar', 'backend', 'bangla')
			)
		));


		$this->wp_customize->add_setting('bangla_titlebar_bg_style', array(
			'default' => 'muted',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_titlebar_bg_style', array(
			'label'    => esc_html_x('Background Style', 'backend', 'bangla'),
			'section'  => 'titlebar',
			'settings' => 'bangla_titlebar_bg_style', 
			'type'     => 'select',
			'choices'  => array(
				'default'   => esc_html_x('Default', 'backend', 'bangla'),
				'muted'     => esc_html_x('Muted', 'backend', 'bangla'),
				'primary'   => esc_html_x('Primary', 'backend', 'bangla'),
				'secondary' => esc_html_x('Secondary', 'backend', 'bangla'),
				'media'     => esc_html_x('Image', 'backend', 'bangla'),
				//'video'     => esc_html_x('Video', 'backend', 'bangla'),
			)
		));
		

		$this->wp_customize->add_setting( 'bangla_titlebar_bg_img' , array(
			'sanitize_callback' => 'esc_url'
		));
		$this->wp_customize->add_control( new WP_Customize_Image_Control( $this->wp_customize, 'bangla_titlebar_bg_img', array(
			'label'           => esc_html_x( 'Background Image', 'backend', 'bangla' ),
			'section'         => 'titlebar',
			'settings'        => 'bangla_titlebar_bg_img',
			'active_callback' => 'bangla_titlebar_bg_check',
		)));


		$this->wp_customize->add_setting('bangla_titlebar_txt_style', array(
			'default'           => 0,
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_titlebar_txt_style', array(
			'label'    => esc_html_x('Text Color', 'backend', 'bangla'),
			'section'  => 'titlebar',
			'settings' => 'bangla_titlebar_txt_style', 
			'type'     => 'select',
			'choices'  => array(
				0       => esc_html_x('Default', 'backend', 'bangla'),
				'light' => esc_html_x('Light', 'backend', 'bangla'),
				'dark'  => esc_html_x('Dark', 'backend', 'bangla'),
			)
		));


		$this->wp_customize->add_setting('bangla_titlebar_padding', array(
			'default' => 'medium',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_titlebar_padding', array(
			'label'    => esc_html_x('Padding', 'backend', 'bangla'),
			'section'  => 'titlebar',
			'settings' => 'bangla_titlebar_padding', 
			'type'     => 'select',
			'choices'  => array(
				'medium' => esc_html_x('Default', 'backend', 'bangla'),
				'small'  => esc_html_x('Small', 'backend', 'bangla'),
				'large'  => esc_html_x('Large', 'backend', 'bangla'),
				'none'   => esc_html_x('None', 'backend', 'bangla'),
			)
		));


		$this->wp_customize->add_setting('bangla_blog_title', array(
			'default' => esc_html_x('Blog', 'backend', 'bangla'),
			'sanitize_callback' => 'esc_attr'
		));
		$this->wp_customize->add_control('bangla_blog_title', array(
		    'label'    => esc_html_x('Blog Title: ', 'backend', 'bangla'),
		    'section'  => 'titlebar',
		    'settings' => 'bangla_blog_title'
		));

		if (class_exists('Woocommerce')){
			$this->wp_customize->add_setting('bangla_woocommerce_title', array(
				'default' => esc_html_x('Shop', 'backend', 'bangla'),
				'sanitize_callback' => 'esc_attr'
			));
			$this->wp_customize->add_control('bangla_woocommerce_title', array(
			    'label'    => esc_html_x('WooCommerce Title: ', 'backend', 'bangla'),
			    'section'  => 'titlebar',
			    'settings' => 'bangla_woocommerce_title'
			));
		}



		//blog section
		$this->wp_customize->add_section('blog', array(
			'title' => esc_html_x('Blog', 'backend', 'bangla'),
			'priority' => 35
		));


		$this->wp_customize->add_setting('bangla_blog_layout', array(
			'default' => 'sidebar-right',
			'sanitize_callback' => 'bangla_sanitize_choices',
		));
		$this->wp_customize->add_control(new bangla_Customize_Layout_Control( $this->wp_customize, 'bangla_blog_layout', 
			array(
				'label'       => esc_html_x('Blog Page Layout', 'backend', 'bangla'),
				'description' => esc_html_x('If you select static blog page so you need to select your blog page layout from here.', 'backend', 'bangla'),
				'section'     => 'blog',
				'settings'    => 'bangla_blog_layout', 
				'type'        => 'layout',
				'choices' => array(
					"sidebar-left"  => esc_html_x('Sidebar Left', 'backend', 'bangla'), 
					"full"          => esc_html_x('Fullwidth', 'backend', 'bangla'),
					"sidebar-right" => esc_html_x('Sidebar Right', 'backend', 'bangla'),
				),
				//'active_callback' => 'is_front_page',
			)
		));



		$this->wp_customize->add_setting('bangla_blog_readmore', array(
			'default' => 1,
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_blog_readmore',
	        array(
				'label'       => esc_html_x('Read More Button in Blog Posts', 'backend', 'bangla'),
				'description' => esc_html_x('Enable / Disable read more button on blog posts.', 'backend', 'bangla'),
				'section'     => 'blog',
				'settings'    => 'bangla_blog_readmore',
				'type'        => 'select',
				'choices'     => array(
					1 => esc_html_x('Yes', 'backend', 'bangla'),
					0  => esc_html_x('No', 'backend', 'bangla')
				)
	        )
		));

		$this->wp_customize->add_setting('bangla_blog_meta', array(
			'default' => 1,
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_blog_meta',
	        array(
				'label'       => esc_html_x('Metadata on Blog Posts', 'backend', 'bangla'),
				'description' => esc_html_x('Enable / Disable metadata on blog post.', 'backend', 'bangla'),
				'section'     => 'blog',
				'settings'    => 'bangla_blog_meta',
				'type'        => 'select',
				'choices'     => array(
					1 => esc_html_x('Yes', 'backend', 'bangla'),
					0  => esc_html_x('No', 'backend', 'bangla')
				)
	        )
		));

		$this->wp_customize->add_setting('bangla_blog_next_prev', array(
			'default' => 1,
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_blog_next_prev',
	        array(
				'label'       => esc_html_x('Previous / Next Pagination', 'backend', 'bangla'),
				'description' => esc_html_x('Enable / Disable next previous button on blog posts.', 'backend', 'bangla'),
				'section'     => 'blog',
				'settings'    => 'bangla_blog_next_prev',
				'type'        => 'select',
				'choices'     => array(
					1 => esc_html_x('Yes', 'backend', 'bangla'),
					0  => esc_html_x('No', 'backend', 'bangla')
				)
	        )
		));

		$this->wp_customize->add_setting('bangla_author_info', array(
			'default' => 1,
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_author_info',
	        array(
				'label'       => esc_html_x('Author Info in Blog Details', 'backend', 'bangla'),
				'description' => esc_html_x('Enable / Disable author info from underneath of blog posts.', 'backend', 'bangla'),
				'section'     => 'blog',
				'settings'    => 'bangla_author_info',
				'type'        => 'select',
				'choices'     => array(
					1 => esc_html_x('Yes', 'backend', 'bangla'),
					0  => esc_html_x('No', 'backend', 'bangla')
				)
	        )
		));

		$this->wp_customize->add_setting('bangla_blog_align', array(
			'default' => 'left',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control('bangla_blog_align', array(
			'label'    => esc_html_x('Titlebar Layout', 'backend', 'bangla'),
			'section'  => 'blog',
			'settings' => 'bangla_blog_align', 
			'type'     => 'select',
			'priority' => 1,
			'choices'  => array(
				'left'   => esc_html_x('Left Align', 'backend', 'bangla'),
				'center'  => esc_html_x('Center Align', 'backend', 'bangla'),
				'right'  => esc_html_x('Right Align', 'backend', 'bangla'),
			)
		));

		$this->wp_customize->add_setting('bangla_related_post', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_related_post',
	        array(
				'label'       => esc_html_x('Related Posts in Blog Details', 'backend', 'bangla'),
				'description' => esc_html_x('Enable / Disable related post underneath of blog posts.', 'backend', 'bangla'),
				'section'     => 'blog',
				'settings'    => 'bangla_related_post',
				'type'        => 'select',
				'choices'     => array(
					1 => esc_html_x('Yes', 'backend', 'bangla'),
					0  => esc_html_x('No', 'backend', 'bangla')
				)
	        )
		));



		
		/**
		 * Layout Customizer Settings
		 */

		//Header section
		$this->wp_customize->add_section('header', array(
			'title' => esc_html_x('Header', 'backend', 'bangla'),
			'priority' => 31
		));


		$this->wp_customize->add_setting('bangla_header_layout', array(
			'default'           => 'horizontal-right',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new bangla_Customize_Header_Layout_Control( $this->wp_customize, 'bangla_header_layout', 
			array(
			'label'    => esc_html_x('Header Layout', 'backend', 'bangla'),
			'description' => esc_html_x('Select header layout from here. This header layout for global usage but you can change it from your page setting for specific page.', 'backend', 'bangla'),
			'section'  => 'header',
			'settings' => 'bangla_header_layout', 
			'type'     => 'layout_header',
			'choices'  => array(
				'horizontal-left'      => esc_html_x('Horizontal Left', 'backend', 'bangla'),
				'horizontal-center'    => esc_html_x('Horizontal Center', 'backend', 'bangla'),
				'horizontal-right'     => esc_html_x('Horizontal Right', 'backend', 'bangla'),
				'stacked-center-a'     => esc_html_x('Stacked Center A', 'backend', 'bangla'),
				'stacked-center-b'     => esc_html_x('Stacked Center B', 'backend', 'bangla'),
				'stacked-center-split' => esc_html_x('Stacked Center Split', 'backend', 'bangla'),
				'stacked-left-a'       => esc_html_x('Stacked Left A', 'backend', 'bangla'),
				'stacked-left-b'       => esc_html_x('Stacked Left B', 'backend', 'bangla'),
				'toggle-offcanvas'     => esc_html_x('Toggle Offcanvas', 'backend', 'bangla'),
				'toggle-modal'         => esc_html_x('Toggle Modal', 'backend', 'bangla'),
				'side-left'            => esc_html_x('Side Left', 'backend', 'bangla'),
				'side-right'           => esc_html_x('Side Right', 'backend', 'bangla'),
			)
		)));
		
		$this->wp_customize->add_setting('bangla_header_fullwidth', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_header_fullwidth', array(
			'label'       => esc_html_x('Header Fullwidth', 'backend', 'bangla'),
			'description' => esc_html_x('(Make your header full width like fluid width.)', 'backend', 'bangla'),
			'section'     => 'header',
			'settings'    => 'bangla_header_fullwidth', 
			'type'        => 'checkbox',
			'active_callback' => 'bangla_header_layout_check',
		)));

		$this->wp_customize->add_setting('bangla_header_bg_style', array(
			'default'           => 'default',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_header_bg_style', array(
			'label'    => esc_html_x('Background Style', 'backend', 'bangla'),
			'section'  => 'header',
			'settings' => 'bangla_header_bg_style',
			'type'     => 'select',
			'choices'  => array(
				'default'   => esc_html_x('Default', 'backend', 'bangla'),
				'muted'     => esc_html_x('Muted', 'backend', 'bangla'),
				'primary'   => esc_html_x('Primary', 'backend', 'bangla'),
				'secondary' => esc_html_x('Secondary', 'backend', 'bangla'),
				'media'     => esc_html_x('Image', 'backend', 'bangla'),
				//'video'     => esc_html_x('Video', 'backend', 'bangla'),
			),
			'active_callback' => 'bangla_header_transparent_check',
		));

		$this->wp_customize->add_setting( 'bangla_header_bg_img' , array(
			'sanitize_callback' => 'esc_url'
		));
		$this->wp_customize->add_control( new WP_Customize_Image_Control( $this->wp_customize, 'bangla_header_bg_img', array(
			'label'           => esc_html_x( 'Background Image', 'backend', 'bangla' ),
			'section'         => 'header',
			'settings'        => 'bangla_header_bg_img',
			'active_callback' => 'bangla_header_bg_style_check',
		)));

		$this->wp_customize->add_setting('bangla_header_bg_img_position', array(
			'default' => '',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_header_bg_img_position', array(
			'label'    => esc_html_x('Background Position', 'backend', 'bangla'),
			'section'  => 'header',
			'settings' => 'bangla_header_bg_img_position', 
			'type'     => 'select',
			'choices'  => array(
				'top-left'      => esc_html_x('Top Left', 'backend', 'bangla'),
				'top-center'    => esc_html_x('Top Center', 'backend', 'bangla'),
				'top-right'     => esc_html_x('Top Right', 'backend', 'bangla'),
				'center-left'   => esc_html_x('Center Left', 'backend', 'bangla'),
				''              => esc_html_x('Center Center', 'backend', 'bangla'),
				'center-right'  => esc_html_x('Center Right', 'backend', 'bangla'),
				'bottom-left'   => esc_html_x('Bottom Left', 'backend', 'bangla'),
				'bottom-center' => esc_html_x('Bottom Center', 'backend', 'bangla'),
				'bottom-right'  => esc_html_x('Bottom Right', 'backend', 'bangla'),
			),
			'active_callback' => 'bangla_header_bg_img_check',
		)));

		$this->wp_customize->add_setting('bangla_header_txt_style', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_header_txt_style', array(
			'label'    => esc_html_x('Header Text Color', 'backend', 'bangla'),
			'section'  => 'header',
			'settings' => 'bangla_header_txt_style', 
			'type'     => 'select',
			'choices'  => array(
				0       => esc_html_x('Default', 'backend', 'bangla'),
				'light' => esc_html_x('Light', 'backend', 'bangla'),
				'dark'  => esc_html_x('Dark', 'backend', 'bangla'),
			),
			//'active_callback' => 'bangla_header_fixed_check',
		)));


		$this->wp_customize->add_setting('bangla_header_shadow', array(
			'default' => 'small',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_header_shadow', array(
			'label'    => esc_html_x('Shadow', 'backend', 'bangla'),
			'section'  => 'header',
			'settings' => 'bangla_header_shadow', 
			'type'     => 'select',
			'choices'  => array(
				0          => esc_html_x('No Shadow', 'backend', 'bangla'),
				'small'    => esc_html_x('Small', 'backend', 'bangla'),
				'medium'   => esc_html_x('Medium', 'backend', 'bangla'),
				'large'    => esc_html_x('Large', 'backend', 'bangla'),
				'xlarge' => esc_html_x('Extra Large', 'backend', 'bangla'),
			)
		));


		$this->wp_customize->add_setting('bangla_header_transparent', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_header_transparent', array(
			'label'    => esc_html_x('Header Transparent', 'backend', 'bangla'),
			'section'  => 'header',
			'settings' => 'bangla_header_transparent', 
			'type'     => 'select',
			'choices'  => array(
				0       => esc_html_x('No', 'backend', 'bangla'),
				'light' => esc_html_x('Overlay (Light)', 'backend', 'bangla'),
				'dark'  => esc_html_x('Overlay (Dark)', 'backend', 'bangla'),
			),
			'active_callback' => 'bangla_header_layout_check',
		)));


		$this->wp_customize->add_setting('bangla_header_sticky', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_header_sticky', array(
			'label'    => esc_html_x('Header Sticky', 'backend', 'bangla'),
			'section'  => 'header',
			'settings' => 'bangla_header_sticky', 
			'type'     => 'select',
			'choices'  => array(
				0        => esc_html_x('No', 'backend', 'bangla'),
				'sticky' => esc_html_x('Sticky', 'backend', 'bangla'),
				'smart'  => esc_html_x('Smart Sticky', 'backend', 'bangla'),
			),
			'active_callback' => 'bangla_header_layout_check',
		)));

        // Add global color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'bangla_header_sticky_color', array(
        	'default'           => '#fff',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
        		'background-color' => array(
        			'.broxme_wp-navbar-container.broxme_wp-sticky.broxme_wp-active',
        		),
        	)
        )));

        
		$this->wp_customize->add_control(new WP_Customize_Color_Control( $this->wp_customize, 'bangla_header_sticky_color', array(
			'label'       => esc_html_x('Sticky Active Color', 'backend', 'bangla'),
			'section'     => 'header',
        )));
        


		$this->wp_customize->add_setting('bangla_navbar_style', array(
			'default' => 'style1',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_navbar_style', array(
			'label'    => esc_html_x('Main Menu Style', 'backend', 'bangla'),
			'section'  => 'header',
			'settings' => 'bangla_navbar_style', 
			'type'     => 'select',
			'choices'  => array(
				'style1' => esc_html_x('Top Line', 'backend', 'bangla'),
				'style2' => esc_html_x('Bottom Line', 'backend', 'bangla'),
				'style3'  => esc_html_x('Top Edge Line', 'backend', 'bangla'),
				'style4'  => esc_html_x('Bottom Edge Line', 'backend', 'bangla'),
				'style5'  => esc_html_x('Markar Mark', 'backend', 'bangla'),
			),
			'active_callback' => 'bangla_header_layout_check',
		)));


		$this->wp_customize->add_setting('bangla_search_position', array(
			'default' => 'header',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_search_position', array(
			'label'    => esc_html_x('Search', 'backend', 'bangla'),
			'description'    => esc_html_x('Select the position that will display the search.', 'backend', 'bangla'),
			'section'  => 'header',
			'settings' => 'bangla_search_position', 
			'type'     => 'select',
			'choices'  => array(
				0        => esc_html_x('Hide', 'backend', 'bangla'),
				'header' => esc_html_x('Header', 'backend', 'bangla'),
				'menu'   => esc_html_x('With Menu', 'backend', 'bangla'),
			)
		)));

		$this->wp_customize->add_setting('bangla_search_style', array(
			'default' => 'default',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_search_style', array(
			'label'       => esc_html_x('Search Style', 'backend', 'bangla'),
			'description' => esc_html_x('Select search style from here.', 'backend', 'bangla'),
			'section'     => 'header',
			'settings'    => 'bangla_search_style', 
			'type'        => 'select',
			'choices'     => array(
				'default'  => esc_html_x('Default', 'backend', 'bangla'),
				'modal'    => esc_html_x('Modal', 'backend', 'bangla'),
				'dropdown' => esc_html_x('Dropdown', 'backend', 'bangla'),
			),
			'active_callback' => 'bangla_header_layout_check',
		)));

		$this->wp_customize->add_setting('bangla_mobile_offcanvas_style', array(
			'default' => 'offcanvas',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_mobile_offcanvas_style', array(
			'label'       => esc_html_x('Mobile Menu Style', 'backend', 'bangla'),
			'description' => 'Select the menu style displayed in the mobile position.',
			'section'     => 'header',
			'settings'    => 'bangla_mobile_offcanvas_style', 
			'type'        => 'select',
			'choices'     => array(
				'offcanvas' => esc_html_x('Offcanvas', 'backend', 'bangla'),
				'modal'     => esc_html_x('Modal', 'backend', 'bangla'),
				'dropdown'  => esc_html_x('Dropdown', 'backend', 'bangla'),
			),
		)));


		$this->wp_customize->add_setting('bangla_mobile_offcanvas_mode', array(
			'default' => 'slide',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_mobile_offcanvas_mode', array(
			'label'       => esc_html_x('Offcanvas Mode', 'backend', 'bangla'),
			'section'     => 'header',
			'settings'    => 'bangla_mobile_offcanvas_mode', 
			'type'        => 'select',
			'choices'     => array(
				'slide'  => esc_html_x('Slide', 'backend', 'bangla'),
				'reveal' => esc_html_x('Reveal', 'backend', 'bangla'),
				'push'   => esc_html_x('Push', 'backend', 'bangla'),
			),
			'active_callback' => 'bangla_offcanvas_mode_check',
		)));

		
		$this->wp_customize->add_setting('bangla_mobile_break_point', array(
			'default' => 'm',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_mobile_break_point', array(
			'label'    => esc_html_x('Mobile Break Point', 'backend', 'bangla'),
			'section'  => 'header',
			'settings' => 'bangla_mobile_break_point', 
			'type'     => 'select',
			'choices'  => array(
				's' => esc_html_x('Small', 'backend', 'bangla'),
				'm' => esc_html_x('Medium', 'backend', 'bangla'),
				'l' => esc_html_x('Large', 'backend', 'bangla'),
			)
		)));


		$this->wp_customize->add_setting('bangla_mobile_menu_align', array(
			'default' => 'left',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_mobile_menu_align', array(
			'label'    => esc_html_x('Mobile Menu Align', 'backend', 'bangla'),
			'section'  => 'header',
			'settings' => 'bangla_mobile_menu_align', 
			'type'     => 'select',
			'choices'  => array(
				''      => esc_html_x('Hide', 'backend', 'bangla'),
				'left'  => esc_html_x('Left', 'backend', 'bangla'),
				'right' => esc_html_x('Right', 'backend', 'bangla'),
			)
		)));

		
		$this->wp_customize->add_setting('bangla_mobile_menu_text', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control('bangla_mobile_menu_text', array(
			'label'       => esc_html_x('Display the menu text next to the icon.', 'backend', 'bangla'),
			'section'     => 'header',
			'settings'    => 'bangla_mobile_menu_text',
			'type'        => 'checkbox'
		));
		



		// Main Body Settings
		$this->wp_customize->add_section('mainbody', array(
			'title'       => esc_html_x('Main Body', 'backend', 'bangla'),
			'description' => esc_html_x( 'Default body settings here.', 'backend', 'bangla' ),
			'priority'    => 33
		));

		$this->wp_customize->add_setting('bangla_sidebar_position', array(
			'default' => 'sidebar-right',
			'sanitize_callback' => 'bangla_sanitize_choices',
		));
		$this->wp_customize->add_control(new bangla_Customize_Layout_Control( $this->wp_customize, 'bangla_sidebar_position', 
			array(
				'label'       => esc_html_x('Sidebar Layout', 'backend', 'bangla'),
				'description' => esc_html_x('Select global page sidebar position from here. If you already set any sidebar setting from specific page so it will not applicable for that page.', 'backend', 'bangla'),
				'section'     => 'mainbody',
				'settings'    => 'bangla_sidebar_position', 
				'type'        => 'layout',
				'choices' => array(
					"sidebar-left"  => esc_html_x('Sidebar Left', 'backend', 'bangla'), 
					"full"          => esc_html_x('No Sidebar', 'backend', 'bangla'),
					"sidebar-right" => esc_html_x('Sidebar Right', 'backend', 'bangla'),
				),
				'active_callback' => 'bangla_homepage_check',
			)
		));


		$this->wp_customize->add_setting('bangla_main_bg_style', array(
			'default' => 'default',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_main_bg_style', array(
			'label'    => esc_html_x('Background Style', 'backend', 'bangla'),
			'section'  => 'mainbody',
			'settings' => 'bangla_main_bg_style', 
			'type'     => 'select',
			'choices'  => array(
				'default'   => esc_html_x('Default', 'backend', 'bangla'),
				'muted'     => esc_html_x('Muted', 'backend', 'bangla'),
				'primary'   => esc_html_x('Primary', 'backend', 'bangla'),
				'secondary' => esc_html_x('Secondary', 'backend', 'bangla'),
				'media'     => esc_html_x('Image', 'backend', 'bangla'),
				//'video'     => esc_html_x('Video', 'backend', 'bangla'),
			)
		));
		

		$this->wp_customize->add_setting( 'bangla_main_bg_img' , array(
			'sanitize_callback' => 'esc_url'
		));
		$this->wp_customize->add_control( new WP_Customize_Image_Control( $this->wp_customize, 'bangla_main_bg_img', array(
			'label'           => esc_html_x( 'Background Image', 'backend', 'bangla' ),
			'section'         => 'mainbody',
			'settings'        => 'bangla_main_bg_img',
			'active_callback' => 'bangla_main_bg_check',
		)));

		$this->wp_customize->add_setting('bangla_main_txt_style', array(
			'default'           => 0,
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_main_txt_style', array(
			'label'    => esc_html_x('Text Color', 'backend', 'bangla'),
			'section'  => 'mainbody',
			'settings' => 'bangla_main_txt_style', 
			'type'     => 'select',
			'choices'  => array(
				0       => esc_html_x('Default', 'backend', 'bangla'),
				'light' => esc_html_x('Light', 'backend', 'bangla'),
				'dark'  => esc_html_x('Dark', 'backend', 'bangla'),
			)
		));

		$this->wp_customize->add_setting('bangla_sidebar_width', array(
			'default' => '1-4',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_sidebar_width', array(
			'label'    => esc_html_x('Sidebar Width', 'backend', 'bangla'),
			'description' => esc_html_x('Set a sidebar width in percent and the content column will adjust accordingly. The width will not go below the Sidebar\'s min-width, which you can set in the Style section.', 'backend', 'bangla'),
			'section'  => 'mainbody',
			'settings' => 'bangla_sidebar_width', 
			'type'     => 'select',
			'choices'  => array(
				'1-5' => esc_html_x('20%', 'backend', 'bangla'),
				'1-4' => esc_html_x('25%', 'backend', 'bangla'),
				'1-3' => esc_html_x('33%', 'backend', 'bangla'),
				'2-5' => esc_html_x('40%', 'backend', 'bangla'),
				'1-2' => esc_html_x('50%', 'backend', 'bangla'),
			)
		));


		$this->wp_customize->add_setting('bangla_sidebar_gutter', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_sidebar_gutter', array(
			'label'    => esc_html_x('Gutter', 'backend', 'bangla'),
			'section'  => 'mainbody',
			'settings' => 'bangla_sidebar_gutter', 
			'type'     => 'select',
			'choices'  => array(
				'small'    => esc_html_x('Small', 'backend', 'bangla'),
				'medium'   => esc_html_x('Medium', 'backend', 'bangla'),
				0          => esc_html_x('Default', 'backend', 'bangla'),
				'large'    => esc_html_x('Large', 'backend', 'bangla'),
				'collapse' => esc_html_x('Collapse', 'backend', 'bangla'),
			)
		));

		$this->wp_customize->add_setting('bangla_sidebar_divider', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control('bangla_sidebar_divider', array(
			'label'           => esc_html_x('Display dividers between body and sidebar', 'backend', 'bangla'),
			'description'     => esc_html_x('(Set the grid gutter width and display dividers between grid cells.)', 'backend', 'bangla'),
			'section'         => 'mainbody',
			'settings'        => 'bangla_sidebar_divider',
			//'active_callback' => 'bangla_bottom_gutter_collapse_check',
			'type'            => 'checkbox'
		));


		$this->wp_customize->add_setting('bangla_sidebar_breakpoint', array(
			'default' => 'm',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_sidebar_breakpoint', array(
			'label'       => esc_html_x('Breakpoint', 'backend', 'bangla'),
			'description' => esc_html_x('Set the breakpoint from which the sidebar and content will stack.', 'backend', 'bangla'),
			'section'     => 'mainbody',
			'settings'    => 'bangla_sidebar_breakpoint', 
			'type'        => 'select',
			'choices'     => array(
				's'  => esc_html_x('Small (Phone Landscape)', 'backend', 'bangla'),
				'm'  => esc_html_x('Medium (Tablet Landscape)', 'backend', 'bangla'),
				'l'  => esc_html_x('Large (Desktop)', 'backend', 'bangla'),
				'xl' => esc_html_x('X-Large (Large Screens)', 'backend', 'bangla'),
			)
		));






		$this->wp_customize->add_setting('bangla_main_padding', array(
			'default' => 'medium',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_main_padding', array(
			'label'    => esc_html_x('Padding', 'backend', 'bangla'),
			'section'  => 'mainbody',
			'settings' => 'bangla_main_padding', 
			'type'     => 'select',
			'choices'  => array(
				'medium' => esc_html_x('Default', 'backend', 'bangla'),
				'small'  => esc_html_x('Small', 'backend', 'bangla'),
				'large'  => esc_html_x('Large', 'backend', 'bangla'),
				'none'   => esc_html_x('None', 'backend', 'bangla'),
			)
		));



		// Background image for body tag
		$this->wp_customize->add_setting('bangla_bg_note', array(
				'default'           => '',
				'sanitize_callback' => 'esc_attr'
		    )
		);
		$this->wp_customize->add_control( new bangla_Customize_Alert_Control( $this->wp_customize, 'bangla_bg_note', array(
			'label'       => 'Background Alert',
			'section'     => 'background_image',
			'settings'    => 'bangla_bg_note',
			'type'        => 'alert',
			'priority'    => 1,
			'text'        => esc_html_x('You must set your layout mode Boxed for use this feature. Otherwise you can\'t see what happening in background', 'backend', 'bangla'),
			'alert_type' => 'warning',
		    )) 
		);

		$this->wp_customize->add_panel('colors', array(
			'title' => esc_html_x('Colors', 'backend', 'bangla'),
			'priority' => 45
		));

		$this->wp_customize->add_section('colors_global', array(
			'title' => esc_html_x('Global Colors', 'backend', 'bangla'),
			'panel' => 'colors',
		));

		$this->wp_customize->add_section('colors_button', array(
			'title' => esc_html_x('Button Colors', 'backend', 'bangla'),
			'panel' => 'colors',
		));

		$this->wp_customize->add_section('colors_header', array(
			'title' => esc_html_x('Header Colors', 'backend', 'bangla'),
			'panel' => 'colors',
		));

		$this->wp_customize->add_section('colors_menu', array(
			'title' => esc_html_x('Menu Colors', 'backend', 'bangla'),
			'panel' => 'colors',
		));

		$this->wp_customize->add_section('colors_offcanvas', array(
			'title' => esc_html_x('Offcanvas Colors', 'backend', 'bangla'),
			'panel' => 'colors',
		));

		$this->wp_customize->add_section('colors_footer', array(
			'title' => esc_html_x('Footer Colors', 'backend', 'bangla'),
			'panel' => 'colors',
		));

		$this->wp_customize->add_control(new WP_Customize_Color_Control( $this->wp_customize, 'background_color', array(
			'label'       => esc_html_x('Global Background Color', 'backend', 'bangla'),
			'section'     => 'colors_global',
			'description' => esc_html_x('Please select layout boxed for check your global page background.', 'backend', 'bangla'),
        )));
        

        // Add global color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'global_color', array(
        	'default'           => '#666666',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
        		'color' => array(
        			'body',
        		),
        	)
        )));

        // Add global color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'global_color', array(
        	'label'       => esc_html_x( 'Global Text Color', 'backend', 'bangla' ),
        	'section'     => 'colors_global',
        )));


        // Add global link color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'primary_background_color', array(
        	'default'           => '#3FB8FD',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
				'background-color' => array(
					'.broxme_wp-button-primary',
					'.broxme_wp-section-primary',
					'.broxme_wp-background-primary',
					'.broxme_wp-card-primary',
					
				),
        	)
        )));

        // Add global link color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'primary_background_color', array(
        	'label'       => esc_html_x( 'Primary Background Color', 'backend', 'bangla' ),
        	'section'     => 'colors_global',
        )));


        // Add global link color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'primary_color', array(
        	'default'           => '#3FB8FD',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
        		'color' => array(
					'a',
					'.broxme_wp-link',
					'.broxme_wp-text-primary',
					'.broxme_wp-alert-primary',
					'.we-are-open li div.broxme_wp-width-expand span',
					'.woocommerce .star-rating span',
				),
				'border-color' => array(
					'.broxme_wp-input:focus', 
					'.broxme_wp-select:focus', 
					'.broxme_wp-textarea:focus',
					'.broxme_wp-bottom.broxme_wp-section-custom .broxme_wp-button-default:hover',
				),
				'background-color' => array(
					'.broxme_wp-label',
					'.broxme_wp-subnav-pill > .broxme_wp-active > a',
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-nav>li.broxme_wp-active>a::before',
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-nav>li:hover>a::before', 
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-nav>li>a:focus::before', 
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-nav>li>a.broxme_wp-open::before',
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-nav>li.broxme_wp-active>a::after',
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-nav>li:hover>a::after', 
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-nav>li>a:focus::after', 
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-nav>li>a.broxme_wp-open::after',
					'body:not(.broxme_wp-custom-header) .broxme_wp-header:not(.broxme_wp-light) .broxme_wp-navbar-nav>li.broxme_wp-active>a::before',
					'body:not(.broxme_wp-custom-header) .broxme_wp-header:not(.broxme_wp-light) .broxme_wp-navbar-nav>li.broxme_wp-active>a::after',
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-dropdown-nav>li.broxme_wp-active>a:after',
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-dropdown ul.broxme_wp-navbar-dropdown-nav ul li.broxme_wp-active a:after',
					'[class*=\'navbar-style\'] .broxme_wp-header .broxme_wp-navbar .broxme_wp-navbar-nav > li:hover > a::before', 
					'[class*=\'navbar-style\'] .broxme_wp-header .broxme_wp-navbar .broxme_wp-navbar-nav > li:hover > a::after', 
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-dropdown ul.broxme_wp-navbar-dropdown-nav li.broxme_wp-parent > a:after',
				),
				'background-color|lighten(5)' => array(
					'.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle', 
					'.woocommerce .widget_price_filter .ui-slider .ui-slider-handle',
				),
        	)
        )));

        // Add global link color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'primary_color', array(
        	'label'       => esc_html_x( 'Primary Color', 'backend', 'bangla' ),
        	'section'     => 'colors_global',
        )));



        // Add global link hover color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'primary_hover_color', array(
        	'default'           => '#238FF7',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
        		'color' => array(
        			'a:hover',
        			'.broxme_wp-link:hover',
        			'.broxme_wp-text-primary:hover',
        			'.broxme_wp-alert-primary:hover',
        		),
        		'background-color' => array(
        			'.broxme_wp-header .broxme_wp-navbar-dropdown ul.broxme_wp-navbar-dropdown-nav li.broxme_wp-active > a:after',
        			'[class*=\'navbar-style\'] .broxme_wp-navbar .broxme_wp-navbar-nav > li.broxme_wp-active > a::before',
        			'[class*=\'navbar-style\'] .broxme_wp-navbar .broxme_wp-navbar-nav > li.broxme_wp-active > a::after',
        		),
        		'border-color' => array(
        			
        		),
        	)
        )));

        // Add global link hover color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'primary_hover_color', array(
        	'label'       => esc_html_x( 'Primary Hover Color', 'backend', 'bangla' ),
        	'section'     => 'colors_global',
        )));


        // Add secondary color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'secondary_background_color', array(
        	'default'           => '#454f58',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
				'background-color' => array(
					'.broxme_wp-button-secondary',
					'.broxme_wp-section-secondary',
					'.broxme_wp-background-secondary',
					'.broxme_wp-card-secondary',
				),
        	)
        )));

        // Add secondary color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'secondary_background_color', array(
        	'label'       => esc_html_x( 'Secondary Background Color', 'backend', 'bangla' ),
        	'section'     => 'colors_global',
        )));


        // Add secondary color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'secondary_color', array(
        	'default'           => '#454f58',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
        		'color' => array(
					'.broxme_wp-text-secondary',
					'.broxme_wp-alert-secondary',
				),
        	)
        )));

        // Add secondary color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'secondary_color', array(
        	'label'       => esc_html_x( 'Secondary Color', 'backend', 'bangla' ),
        	'section'     => 'colors_global',
        )));


        // Add muted color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'muted_color', array(
        	'default'           => '#999999',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
        		'color' => array(
					'.broxme_wp-text-muted',
					'.broxme_wp-alert-muted',
				),
        	)
        )));

        // Add muted color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'muted_color', array(
        	'label'       => esc_html_x( 'Muted Text Color', 'backend', 'bangla' ),
        	'section'     => 'colors_global',
        )));

        // Add muted color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'muted_background_color', array(
        	'default'           => '#f3f7f9',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
				'background-color' => array(
					'.broxme_wp-button-muted',
					'.broxme_wp-section-muted',
					'.broxme_wp-background-muted',
					'.broxme_wp-card-muted',
				),
        	)
        )));

        // Add muted color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'muted_background_color', array(
        	'label'       => esc_html_x( 'Muted Background Color', 'backend', 'bangla' ),
        	'section'     => 'colors_global',
        )));


         // Add button default color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'button_default_background_color', array(
        	'default'           => '#ffffff',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
				'background-color' => array(
					'.broxme_wp-button-default',
				),
				'background-color|lighten(5)' => array(
					'.broxme_wp-button-default:hover',
				),
        	)
        )));

        // Add button default color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'button_default_background_color', array(
        	'label'       => esc_html_x( 'Default Background Color', 'backend', 'bangla' ),
        	'section'     => 'colors_button',
        )));


         // Add button default color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'button_default_color', array(
        	'default'           => '#222222',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
				'color' => array(
					'.broxme_wp-button-default',
				),
				'color|lighten(5)' => array(
					'.broxme_wp-button-default:hover',
				),
        	)
        )));

        // Add button default color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'button_default_color', array(
        	'label'       => esc_html_x( 'Default Color', 'backend', 'bangla' ),
        	'section'     => 'colors_button',
        )));


         // Add button primary color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'button_primary_background_color', array(
        	'default'           => '#3FB8FD',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
				'background-color' => array(
					'.broxme_wp-button-primary',
        			'.broxme_wp-button-primary:active', 
        			'.broxme_wp-button-primary.broxme_wp-active',
        			'.broxme_wp-button-primary:focus',
				),
				'background-color|lighten(5)' => array(
					'.broxme_wp-button-primary:hover',
				),
        	)
        )));

        // Add button primary color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'button_primary_background_color', array(
        	'label'       => esc_html_x( 'Primary Background Color', 'backend', 'bangla' ),
        	'section'     => 'colors_button',
        )));


         // Add button primary color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'button_primary_color', array(
        	'default'           => '#ffffff',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
				'color' => array(
					'.broxme_wp-button-primary',
				),
				'color|lighten(5)' => array(
					'.broxme_wp-button-primary:hover',
				),
        	)
        )));

        // Add button primary color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'button_primary_color', array(
        	'label'       => esc_html_x( 'Primary Color', 'backend', 'bangla' ),
        	'section'     => 'colors_button',
        )));


         // Add button secondary color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'button_secondary_background_color', array(
        	'default'           => '#757579',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
				'background-color' => array(
					'.broxme_wp-button-secondary',
				),
				'background-color|lighten(5)' => array(
					'.broxme_wp-button-secondary:hover',
				),
        	)
        )));

        // Add button secondary color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'button_secondary_background_color', array(
        	'label'       => esc_html_x( 'Secondary Background Color', 'backend', 'bangla' ),
        	'section'     => 'colors_button',
        )));


         // Add button secondary color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'button_secondary_color', array(
        	'default'           => '#ffffff',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
				'color' => array(
					'.broxme_wp-button-secondary',
				),
				'color|lighten(5)' => array(
					'.broxme_wp-button-secondary:hover',
				),
        	)
        )));

        // Add button secondary color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'button_secondary_color', array(
        	'label'       => esc_html_x( 'Secondary Color', 'backend', 'bangla' ),
        	'section'     => 'colors_button',
        )));


         // Add button danger color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'button_danger_background_color', array(
        	'default'           => '#ee395b',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
				'background-color' => array(
					'.broxme_wp-button-danger',
				),
				'background-color|lighten(5)' => array(
					'.broxme_wp-button-danger:hover',
				),
        	)
        )));

        // Add button danger color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'button_danger_background_color', array(
        	'label'       => esc_html_x( 'Danger Background Color', 'backend', 'bangla' ),
        	'section'     => 'colors_button',
        )));


         // Add button danger color setting.
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'button_danger_color', array(
        	'default'           => '#ffffff',
        	'sanitize_callback' => 'sanitize_hex_color',
        	'css_map' => array(
				'color' => array(
					'.broxme_wp-button-danger',
				),
				'color|lighten(5)' => array(
					'.broxme_wp-button-danger:hover',
				),
        	)
        )));

        // Add button danger color control.
        $this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'button_muted_color', array(
        	'label'       => esc_html_x( 'Danger Color', 'backend', 'bangla' ),
        	'section'     => 'colors_button',
        )));








        // Add page background color setting and control.
		$this->wp_customize->add_setting( 'browser_header_color', array(
			'default'           => '#454f58',
			'sanitize_callback' => 'sanitize_hex_color',
		));

		$this->wp_customize->add_control(new WP_Customize_Color_Control( $this->wp_customize, 'browser_header_color', array(
			'label'       => esc_html_x('Browser Header Color', 'backend', 'bangla'),
			'section'     => 'colors_global',
			'description' => esc_html_x('This color for mobile browser header. This color works only mobile view.' , 'backend', 'bangla'),
        )));



		/**
		 * Footer Customizer Settings
		 */

		// footer appearance
		$this->wp_customize->add_section('footer', array(
			'title' => esc_html_x('Footer', 'backend', 'bangla'),
			'description' => esc_html_x( 'All bangla theme specific settings.', 'backend', 'bangla' ),
			'priority' => 140
		));

		$this->wp_customize->add_setting('bangla_footer_widgets', array(
			'default' => 1,
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control($this->wp_customize, 'bangla_footer_widgets',
	        array(
				'priority'    => 1,
				'label'       => esc_html_x('Show Footer Widgets', 'backend', 'bangla'),
				'section'     => 'footer',
				'settings'    => 'bangla_footer_widgets',
				'type'        => 'select',
				'choices'     => array(
					1 => esc_html_x('Yes', 'backend', 'bangla'),
					0 => esc_html_x('No', 'backend', 'bangla')
				)
	        )
		));


		$this->wp_customize->add_setting('bangla_footer_columns', array(
			'default' => 4,
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_footer_columns', array(
			'label'    => esc_html_x('Footer Columns:', 'backend', 'bangla'),
			'section'  => 'footer',
			'settings' => 'bangla_footer_columns', 
			'type'     => 'select',
			'choices'  => array(
				1 => esc_html_x('1 Column', 'backend', 'bangla'),
				2 => esc_html_x('2 Columns', 'backend', 'bangla'),
				3 => esc_html_x('3 Columns', 'backend', 'bangla'),
				4 => esc_html_x('4 Columns', 'backend', 'bangla')
			)
		));
		
		$this->wp_customize->add_setting('bangla_footer_fce', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control('bangla_footer_fce', array(
			'label'       => esc_html_x('First Column Double Width', 'backend', 'bangla'),
			'description' => esc_html_x('some times your need first footer column double size so you can checked it.', 'backend', 'bangla'),
			'section'     => 'footer',
			'settings'    => 'bangla_footer_fce',
			'type'        => 'checkbox'
		));

		//header section
		if (class_exists('Woocommerce')){
			$this->wp_customize->add_section('woocommerce', array(
				'title' => esc_html_x('WooCommerce', 'backend', 'bangla'),
				'priority' => 99
			));


			$this->wp_customize->add_setting('bangla_woocommerce_sidebar', array(
				'default' => 'sidebar-left',
				'sanitize_callback' => 'bangla_sanitize_choices',
			));
			$this->wp_customize->add_control(new bangla_Customize_Layout_Control( $this->wp_customize, 'bangla_woocommerce_sidebar', 
				array(
					'label'       => esc_html_x('Shop Page Sidebar', 'backend', 'bangla'),
					'description' => esc_html_x('Make sure you add your widget in shop widget position.', 'backend', 'bangla'),
					'section'     => 'woocommerce',
					'settings'    => 'bangla_woocommerce_sidebar', 
					'choices' => array(
						"sidebar-left"  => esc_html_x('Sidebar Left', 'backend', 'bangla'), 
						"full"          => esc_html_x('Fullwidth', 'backend', 'bangla'),
						"sidebar-right" => esc_html_x('Sidebar Right', 'backend', 'bangla'),
					),
					//'active_callback' => 'is_front_page',
				)
			));

			//avatar shape
			$this->wp_customize->add_setting('bangla_woocommerce_cart', array(
				'default'           => 'no',
				'sanitize_callback' => 'bangla_sanitize_choices'
			));
			$this->wp_customize->add_control('bangla_woocommerce_cart', array(
				'label'       => esc_html_x('Shopping Cart Icon in Header:', 'backend', 'bangla'),
				'description' => esc_html_x('Enable / Disable Shopping Cart Icon', 'backend', 'bangla'),
				'section'     => 'woocommerce',
				'settings'    => 'bangla_woocommerce_cart', 
				'type'        => 'select',
				'choices'     => array(
					'no'      => esc_html_x('No Need', 'backend', 'bangla'),
					'header'  => esc_html_x('Yes (in header)', 'backend', 'bangla'),
					'toolbar' => esc_html_x('Yes (in toolbar)', 'backend', 'bangla'),
				)
			));


			$this->wp_customize->add_setting('bangla_woocommerce_columns', array(
				'default' => 3,
				'sanitize_callback' => 'bangla_sanitize_choices'
			) );
			$this->wp_customize->add_control('bangla_woocommerce_columns', array(
				'label'    => esc_html_x('Product Columns:', 'backend', 'bangla'),
				'section'  => 'woocommerce',
				'settings' => 'bangla_woocommerce_columns', 
				'type'     => 'select',
				'choices'  => array(
					2 => esc_html_x('2 Columns', 'backend', 'bangla'),
					3 => esc_html_x('3 Columns', 'backend', 'bangla'),
					4 => esc_html_x('4 Columns', 'backend', 'bangla')
				)
			));


			$this->wp_customize->add_setting('bangla_woocommerce_limit', array(
				'default' => 9,
				'sanitize_callback' => 'esc_attr'
			));
			$this->wp_customize->add_control('bangla_woocommerce_limit', array(
				'label'       => esc_html_x('Items per Shop Page: ', 'backend', 'bangla'),
				'description' => esc_html_x('Enter how many items you want to show on Shop pages & Categorie Pages before Pagination shows up (Default: 9)', 'backend', 'bangla'),
				'section'     => 'woocommerce',
				'settings'    => 'bangla_woocommerce_limit'
			));

			$this->wp_customize->add_setting('bangla_woocommerce_sort', array(
				'default' => 1,
				'sanitize_callback' => 'bangla_sanitize_checkbox'
			));
			$this->wp_customize->add_control('bangla_woocommerce_sort', array(
				'label'       => esc_html_x('Shop Sort', 'backend', 'bangla'),
				'description' => esc_html_x('(Enable / Disable sort-by function on Shop Pages)', 'backend', 'bangla'),
				'section'     => 'woocommerce',
				'settings'    => 'bangla_woocommerce_sort',
				'type'        => 'checkbox'
			));


			$this->wp_customize->add_setting('bangla_woocommerce_result_count', array(
				'default' => 1,
				'sanitize_callback' => 'bangla_sanitize_checkbox'
			));
			$this->wp_customize->add_control('bangla_woocommerce_result_count', array(
				'label'       => esc_html_x('Shop Result Count', 'backend', 'bangla'),
				'description' => esc_html_x('(Enable / Disable Result Count on Shop Pages)', 'backend', 'bangla'),
				'section'     => 'woocommerce',
				'settings'    => 'bangla_woocommerce_result_count',
				'type'        => 'checkbox'
			));

			$this->wp_customize->add_setting('bangla_woocommerce_cart_button', array(
				'default' => 1,
				'sanitize_callback' => 'bangla_sanitize_checkbox'
			));
			$this->wp_customize->add_control('bangla_woocommerce_cart_button', array(
				'label'       => esc_html_x('Add to Cart Button', 'backend', 'bangla'),
				'description' => esc_html_x('(Enable / Disable "Add to Cart"-Button on Shop Pages)', 'backend', 'bangla'),
				'section'     => 'woocommerce',
				'settings'    => 'bangla_woocommerce_cart_button',
				'type'        => 'checkbox'
			));

			$this->wp_customize->add_setting('bangla_woocommerce_upsells', array(
				'default' => 0,
				'sanitize_callback' => 'bangla_sanitize_checkbox'
			));
			$this->wp_customize->add_control('bangla_woocommerce_upsells', array(
				'label'       => esc_html_x('Upsells Products', 'backend', 'bangla'),
				'description' => esc_html_x('(Enable / Disable to show upsells Products on Product Item Details)', 'backend', 'bangla'),
				'section'     => 'woocommerce',
				'settings'    => 'bangla_woocommerce_upsells',
				'type'        => 'checkbox'
			));
			$this->wp_customize->add_setting('bangla_woocommerce_related', array(
				'default' => 1,
				'sanitize_callback' => 'bangla_sanitize_checkbox'
			));
			$this->wp_customize->add_control('bangla_woocommerce_related', array(
				'label'       => esc_html_x('Related Products', 'backend', 'bangla'),
				'description' => esc_html_x('(Enable / Disable to show related Products on Product Item Details)', 'backend', 'bangla'),
				'section'     => 'woocommerce',
				'settings'    => 'bangla_woocommerce_related',
				'type'        => 'checkbox'
			));
		}

		// Add dropdown background color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'menu_link_color', array(
			'default'           => '#999999',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-nav > li > a',
				),
			)
		)));

		// Add dropdown background color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'menu_link_color', array(
			'label'       => esc_html_x( 'Menu Link Color', 'backend', 'bangla' ),
			'section'     => 'colors_menu',
		)));


		// Add dropdown background color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'menu_link_hover_color', array(
			'default'           => '#666666',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-nav > li:hover > a',
				),
			)
		)));

		// Add dropdown background color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'menu_link_hover_color', array(
			'label'       => esc_html_x( 'Menu Hover Color', 'backend', 'bangla' ),
			'section'     => 'colors_menu',
		)));


		// Add dropdown background color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'menu_link_active_color', array(
			'default'           => '#333333',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'body:not(.broxme_wp-custom-header) .broxme_wp-header .broxme_wp-navbar-nav > li.broxme_wp-active > a',
				),
			)
		)));

		// Add dropdown background color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'menu_link_active_color', array(
			'label'       => esc_html_x( 'Menu Active Color', 'backend', 'bangla' ),
			'section'     => 'colors_menu',
		)));


		// Add dropdown background color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'dropdown_background_color', array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'background-color' => array(
					'.broxme_wp-header .broxme_wp-navbar-dropbar',
					'.broxme_wp-header .broxme_wp-navbar-dropdown:not(.broxme_wp-navbar-dropdown-dropbar)',
					'.broxme_wp-header .broxme_wp-navbar-dropdown:not(.broxme_wp-navbar-dropdown-dropbar) .sub-dropdown>ul',
				),
			)
		)));

		// Add dropdown background color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'dropdown_background_color', array(
			'label'       => esc_html_x( 'Dropdown Background Color', 'backend', 'bangla' ),
			'section'     => 'colors_menu',
		)));


		// Add dropdown link color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'dropdown_link_color', array(
			'default'           => '#666666',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.broxme_wp-header .broxme_wp-navbar-dropdown-nav>li>a',
					'.broxme_wp-header .broxme_wp-nav li>a',
					'.broxme_wp-header .broxme_wp-navbar-dropdown-nav .broxme_wp-nav-sub a',
				),
			)
		)));

		// Add dropdown link color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'dropdown_link_color', array(
			'label'       => esc_html_x( 'Dropdown Link Color', 'backend', 'bangla' ),
			'section'     => 'colors_menu',
		)));


		// Add dropdown link hover color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'dropdown_link_hover_color', array(
			'default'           => '#000000',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.broxme_wp-header .broxme_wp-navbar-dropdown-nav li > a:hover', 
					'.broxme_wp-header .broxme_wp-navbar-dropdown-nav li > a:focus',
					'.broxme_wp-header .broxme_wp-navbar-dropdown-nav .broxme_wp-nav-sub a:hover', 
					'.broxme_wp-header .broxme_wp-navbar-dropdown-nav .broxme_wp-nav-sub a:focus',
					'.broxme_wp-header .broxme_wp-navbar-dropdown-nav li.broxme_wp-active > a',
					'.broxme_wp-header .broxme_wp-navbar-dropdown .sub-dropdown>ul li.broxme_wp-active > a',
				),
			)
		)));

		// Add dropdown link hover color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'dropdown_link_hover_color', array(
			'label'       => esc_html_x( 'Dropdown Link Hover Color', 'backend', 'bangla' ),
			'section'     => 'colors_menu',
		)));


		// Add offcanvas background color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'offcanvas_background_color', array(
			'default'           => '#222',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'background-color' => array(
					'.broxme_wp-offcanvas-bar',
				),
			)
		)));
		// Add offcanvas background color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'offcanvas_background_color', array(
			'label'       => esc_html_x( 'Offcanvas Background Color', 'backend', 'bangla' ),
			'section'     => 'colors_offcanvas',
		)));

		
		// Add offcanvas text color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'offcanvas_text_color', array(
			'default'           => '#d9d9d9',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.broxme_wp-offcanvas-bar',
					'.broxme_wp-offcanvas-bar .broxme_wp-search-input',
					'.broxme_wp-offcanvas-bar .broxme_wp-search-icon.broxme_wp-icon',
					'.broxme_wp-offcanvas-bar .broxme_wp-search-input::placeholder',
				),
			)
		)));
		// Add offcanvas text color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'offcanvas_text_color', array(
			'label'           => esc_html_x( 'Text Color', 'backend', 'bangla' ),
			'section'         => 'colors_offcanvas',
		)));


		// Add offcanvas link color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'offcanvas_link_color', array(
			'default'           => '#a6a6a6',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.broxme_wp-offcanvas-bar .broxme_wp-icon',
					'.broxme_wp-offcanvas-bar #nav-offcanvas li a',
				),
			)
		)));
		// Add offcanvas link color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'offcanvas_link_color', array(
			'label'           => esc_html_x( 'Link Color', 'backend', 'bangla' ),
			'section'         => 'colors_offcanvas',
		)));


		// Add offcanvas link active color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'offcanvas_link_active_color', array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.broxme_wp-offcanvas-bar #nav-offcanvas li.broxme_wp-active a',
				),
			)
		)));
		// Add offcanvas link active color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'offcanvas_link_active_color', array(
			'label'           => esc_html_x( 'Link Active Color', 'backend', 'bangla' ),
			'section'         => 'colors_offcanvas',
		)));


		// Add offcanvas link hover color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'offcanvas_link_hover_color', array(
			'default'           => '#d9d9d9',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.broxme_wp-offcanvas-bar .broxme_wp-icon:hover',
					'.broxme_wp-offcanvas-bar #nav-offcanvas li a:hover',
				),
			)
		)));
		// Add offcanvas link hover color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'offcanvas_link_hover_color', array(
			'label'           => esc_html_x( 'Link Hover Color', 'backend', 'bangla' ),
			'section'         => 'colors_offcanvas',
		)));

		
		// Add offcanvas border color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'offcanvas_border_color', array(
			'default'           => '#a6a6a6',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'border-color' => array(
					'.broxme_wp-offcanvas-bar .offcanvas-search .broxme_wp-search .broxme_wp-search-input',
					'.broxme_wp-offcanvas-bar hr',
				),
			)
		)));
		// Add offcanvas border color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'offcanvas_border_color', array(
			'label'           => esc_html_x( 'Border Color', 'backend', 'bangla' ),
			'section'         => 'colors_offcanvas',
		)));


		// Bottom bg style setting
		$this->wp_customize->add_setting('bangla_bottom_bg_style', array(
			'default' => 'secondary',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));

		$this->wp_customize->add_control('bangla_bottom_bg_style', array(
			'label'    => esc_html_x('Background Style', 'backend', 'bangla'),
			'section'  => 'footer',
			'settings' => 'bangla_bottom_bg_style', 
			'type'     => 'select',
			'choices'  => array(
				'default'   => esc_html_x('Default', 'backend', 'bangla'),
				'muted'     => esc_html_x('Muted', 'backend', 'bangla'),
				'primary'   => esc_html_x('Primary', 'backend', 'bangla'),
				'secondary' => esc_html_x('Secondary', 'backend', 'bangla'),
				'media'     => esc_html_x('Image', 'backend', 'bangla'),
				'custom'    => esc_html_x('Custom Color', 'backend', 'bangla'),
			)
		));

		// Add footer background color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'bottom_background_color', array(
			'default'           => '#454f58',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'background-color' => array(
					'.broxme_wp-bottom.broxme_wp-section-custom',
				),
				'border-color|lighten(5)' => array(
					'.broxme_wp-bottom.broxme_wp-section-custom .broxme_wp-grid-divider > :not(.broxme_wp-first-column)::before',
					'.broxme_wp-bottom.broxme_wp-section-custom hr', 
					'.broxme_wp-bottom.broxme_wp-section-custom .broxme_wp-hr',
					'.broxme_wp-bottom.broxme_wp-section-custom .broxme_wp-grid-divider.broxme_wp-grid-stack>.broxme_wp-grid-margin::before',
				),
				'background-color|lighten(2)' => array(
					'.broxme_wp-bottom.broxme_wp-section-custom .widget_tag_cloud a',
				),
			)
		)));

		// Add footer background color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'bottom_background_color', array(
			'label'           => esc_html_x( 'Custom Background Color', 'backend', 'bangla' ),
			'section'         => 'footer',
			'active_callback' => 'bangla_bottom_bg_custom_color_check',
		)));


		$this->wp_customize->add_setting( 'bangla_bottom_bg_img' , array(
			'sanitize_callback' => 'esc_url'
		));
		$this->wp_customize->add_control( new WP_Customize_Image_Control( $this->wp_customize, 'bangla_bottom_bg_img', array(
		    'label'    => esc_html_x( 'Background Image', 'backend', 'bangla' ),
		    'section'  => 'footer',
		    'settings' => 'bangla_bottom_bg_img',
		    'active_callback' => 'bangla_bottom_bg_style_check',
		)));


		$this->wp_customize->add_setting('bangla_bottom_bg_img_position', array(
			'default' => '',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_bottom_bg_img_position', array(
			'label'    => esc_html_x('Background Position', 'backend', 'bangla'),
			'description' => esc_html_x('Set the initial background position, relative to the section layer.', 'backend', 'bangla'),
			'section'  => 'footer',
			'settings' => 'bangla_bottom_bg_img_position', 
			'type'     => 'select',
			'choices'  => array(
				'top-left'      => esc_html_x('Top Left', 'backend', 'bangla'),
				'top-center'    => esc_html_x('Top Center', 'backend', 'bangla'),
				'top-right'     => esc_html_x('Top Right', 'backend', 'bangla'),
				'center-left'   => esc_html_x('Center Left', 'backend', 'bangla'),
				''              => esc_html_x('Center Center', 'backend', 'bangla'),
				'center-right'  => esc_html_x('Center Right', 'backend', 'bangla'),
				'bottom-left'   => esc_html_x('Bottom Left', 'backend', 'bangla'),
				'bottom-center' => esc_html_x('Bottom Center', 'backend', 'bangla'),
				'bottom-right'  => esc_html_x('Bottom Right', 'backend', 'bangla'),
			),
			'active_callback' => 'bangla_bottom_bg_img_check',
		)));

		$this->wp_customize->add_setting('bangla_bottom_bg_img_fixed', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control('bangla_bottom_bg_img_fixed', array(
			'label'           => esc_html_x('Fix the background with regard to the viewport.', 'backend', 'bangla'),
			'section'         => 'footer',
			'settings'        => 'bangla_bottom_bg_img_fixed',
			'type'            => 'checkbox',
			'active_callback' => 'bangla_bottom_bg_img_check',
		));

		$this->wp_customize->add_setting('bangla_bottom_bg_img_visibility', array(
			'default' => 'm',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_bottom_bg_img_visibility', array(
			'label'       => esc_html_x('Background Visibility', 'backend', 'bangla'),
			'description' => esc_html_x('Display the image only on this device width and larger.', 'backend', 'bangla'),
			'section'     => 'footer',
			'settings'    => 'bangla_bottom_bg_img_visibility', 
			'type'        => 'select',
			'choices'     => array(
				's'  => esc_html_x('Small (Phone Landscape)', 'backend', 'bangla'),
				'm'  => esc_html_x('Medium (Tablet Landscape)', 'backend', 'bangla'),
				'l'  => esc_html_x('Large (Desktop)', 'backend', 'bangla'),
				'xl' => esc_html_x('X-Large (Large Screens)', 'backend', 'bangla'),
			),
			'active_callback' => 'bangla_bottom_bg_img_check',
		));


		$this->wp_customize->add_setting('bangla_bottom_txt_style', array(
			'default'           => 0,
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_bottom_txt_style', array(
			'label'    => esc_html_x('Text Color', 'backend', 'bangla'),
			'section'  => 'footer',
			'settings' => 'bangla_bottom_txt_style', 
			'type'     => 'select',
			'choices'  => array(
				0        => esc_html_x('Default', 'backend', 'bangla'),
				'light'  => esc_html_x('Light', 'backend', 'bangla'),
				'dark'   => esc_html_x('Dark', 'backend', 'bangla'),
				'custom' => esc_html_x('Custom', 'backend', 'bangla'),
			)
		));

		// Add footer text color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'footer_text_color', array(
			'default'           => '#a5a5a5',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.broxme_wp-bottom.broxme_wp-section-custom',
					'.broxme_wp-bottom a', 
					'.broxme_wp-bottom .broxme_wp-link', 
					'.broxme_wp-bottom .broxme_wp-text-primary', 
					'.broxme_wp-bottom .broxme_wp-alert-primary',
				),
				'color|lighten(30)' => array(
					'.broxme_wp-bottom.broxme_wp-section-custom .broxme_wp-card-title',
					'.broxme_wp-bottom.broxme_wp-section-custom h3',
					'.broxme_wp-bottom a:hover', 
					'.broxme_wp-bottom .broxme_wp-link:hover', 
					'.broxme_wp-bottom .broxme_wp-text-primary:hover', 
					'.broxme_wp-bottom .broxme_wp-alert-primary:hover',
				),
				'color|darken(5)' => array(
					'.broxme_wp-bottom.broxme_wp-section-custom .widget_tag_cloud a',
				),
				'border-color' => array(
					'.broxme_wp-bottom.broxme_wp-section-custom .broxme_wp-button-default',
				),
			)
		)));

		// Add footer text color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'footer_text_color', array(
			'label'       => esc_html_x( 'Custom Text Color', 'backend', 'bangla' ),
			'section'     => 'footer',
			'active_callback' => 'bangla_bottom_txt_custom_color_check'
		)));


		$this->wp_customize->add_setting('bangla_bottom_width', array(
			'default' => 'default',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_bottom_width', array(
			'label'    => esc_html_x('Width', 'backend', 'bangla'),
			'section'  => 'footer',
			'settings' => 'bangla_bottom_width', 
			'type'     => 'select',
			'choices'  => array(
				'default' => esc_html_x('Default', 'backend', 'bangla'),
				'small'   => esc_html_x('Small', 'backend', 'bangla'),
				'large'   => esc_html_x('Large', 'backend', 'bangla'),
				'expand'  => esc_html_x('Expand', 'backend', 'bangla'),
				0        => esc_html_x('Full', 'backend', 'bangla'),
			)
		));


		$this->wp_customize->add_setting('bangla_bottom_padding', array(
			'default' => 'medium',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_bottom_padding', array(
			'label'    => esc_html_x('Padding', 'backend', 'bangla'),
			'section'  => 'footer',
			'settings' => 'bangla_bottom_padding', 
			'type'     => 'select',
			'choices'  => array(
				'default' => esc_html_x('Default', 'backend', 'bangla'),
				'small'  => esc_html_x('Small', 'backend', 'bangla'),
				'medium' => esc_html_x('Medium', 'backend', 'bangla'),
				'large'  => esc_html_x('Large', 'backend', 'bangla'),
				'none'   => esc_html_x('None', 'backend', 'bangla'),
			)
		));

		$this->wp_customize->add_setting('bangla_bottom_gutter', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_bottom_gutter', array(
			'label'    => esc_html_x('Gutter', 'backend', 'bangla'),
			'section'  => 'footer',
			'settings' => 'bangla_bottom_gutter', 
			'type'     => 'select',
			'choices'  => array(
				'small'    => esc_html_x('Small', 'backend', 'bangla'),
				'medium'   => esc_html_x('Medium', 'backend', 'bangla'),
				0          => esc_html_x('Default', 'backend', 'bangla'),
				'large'    => esc_html_x('Large', 'backend', 'bangla'),
				'collapse' => esc_html_x('Collapse', 'backend', 'bangla'),
			)
		));



		$this->wp_customize->add_setting('bangla_bottom_column_divider', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control('bangla_bottom_column_divider', array(
			'label'           => esc_html_x('Display dividers between grid cells', 'backend', 'bangla'),
			'description'     => esc_html_x('(Set the grid gutter width and display dividers between grid cells.)', 'backend', 'bangla'),
			'section'         => 'footer',
			'settings'        => 'bangla_bottom_column_divider',
			'active_callback' => 'bangla_bottom_gutter_collapse_check',
			'type'            => 'checkbox'
		));

		$this->wp_customize->add_setting('bangla_bottom_vertical_align', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control('bangla_bottom_vertical_align', array(
			'label'       => esc_html_x('Vertically center grid cells.', 'backend', 'bangla'),
			'section'     => 'footer',
			'settings'    => 'bangla_bottom_vertical_align',
			'type'        => 'checkbox'
		));


		$this->wp_customize->add_setting('bangla_bottom_match_height', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control('bangla_bottom_match_height', array(
			'label'       => esc_html_x('Stretch the panel to match the height of the grid cell.', 'backend', 'bangla'),
			'section'     => 'footer',
			'settings'    => 'bangla_bottom_match_height',
			'type'        => 'checkbox'
		));

		$this->wp_customize->add_setting('bangla_bottom_breakpoint', array(
			'default' => 'm',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_bottom_breakpoint', array(
			'label'       => esc_html_x('Breakpoint', 'backend', 'bangla'),
			'description' => esc_html_x('Set the breakpoint from which grid cells will stack.', 'backend', 'bangla'),
			'section'     => 'footer',
			'settings'    => 'bangla_bottom_breakpoint', 
			'type'        => 'select',
			'choices'     => array(
				's'  => esc_html_x('Small (Phone Landscape)', 'backend', 'bangla'),
				'm'  => esc_html_x('Medium (Tablet Landscape)', 'backend', 'bangla'),
				'l'  => esc_html_x('Large (Desktop)', 'backend', 'bangla'),
				'xl' => esc_html_x('X-Large (Large Screens)', 'backend', 'bangla'),
			)
		));

		// Copyright Section
		$this->wp_customize->add_section('copyright', array(
			'title' => esc_html_x('Copyright', 'backend', 'bangla'),
			'description' => esc_html_x( 'Copyright section settings here.', 'backend', 'bangla' ),
			'priority' => 142
		));


		$this->wp_customize->add_setting('bangla_copyright_bg_style', array(
			'default' => 'secondary',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_copyright_bg_style', array(
			'label'    => esc_html_x('Background Style', 'backend', 'bangla'),
			'section'  => 'copyright',
			'settings' => 'bangla_copyright_bg_style', 
			'type'     => 'select',
			'choices'  => array(
				'default'   => esc_html_x('Default', 'backend', 'bangla'),
				'muted'     => esc_html_x('Muted', 'backend', 'bangla'),
				'primary'   => esc_html_x('Primary', 'backend', 'bangla'),
				'secondary' => esc_html_x('Secondary', 'backend', 'bangla'),
				'media'     => esc_html_x('Image', 'backend', 'bangla'),
				'custom'    => esc_html_x('Custom Color', 'backend', 'bangla'),
			)
		));


		// Add footer background color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'bangla_copyright_background_color', array(
			'default'           => '#454f58',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'background-color' => array(
					'.broxme_wp-copyright.broxme_wp-section-custom',
				),
			)
		)));

		// Add footer background color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'bangla_copyright_background_color', array(
			'label'           => esc_html_x( 'Custom Background Color', 'backend', 'bangla' ),
			'section'         => 'copyright',
			'active_callback' => 'bangla_copyright_bg_custom_color_check',
		)));


		$this->wp_customize->add_setting( 'bangla_copyright_bg_img' , array(
			'sanitize_callback' => 'esc_url'
		));
		$this->wp_customize->add_control( new WP_Customize_Image_Control( $this->wp_customize, 'bangla_copyright_bg_img', array(
		    'label'    => esc_html_x( 'Background Image', 'backend', 'bangla' ),
		    'section'  => 'copyright',
		    'settings' => 'bangla_copyright_bg_img',
		    'active_callback' => 'bangla_copyright_bg_style_check',
		)));


		$this->wp_customize->add_setting('bangla_copyright_txt_style', array(
			'default'           => 'light',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_copyright_txt_style', array(
			'label'    => esc_html_x('Text Color', 'backend', 'bangla'),
			'section'  => 'copyright',
			'settings' => 'bangla_copyright_txt_style', 
			'type'     => 'select',
			'choices'  => array(
				0       => esc_html_x('Default', 'backend', 'bangla'),
				'light' => esc_html_x('Light', 'backend', 'bangla'),
				'dark'  => esc_html_x('Dark', 'backend', 'bangla'),
			)
		));

		$this->wp_customize->add_setting('bangla_copyright_width', array(
			'default' => 'default',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_copyright_width', array(
			'label'    => esc_html_x('Width', 'backend', 'bangla'),
			'section'  => 'copyright',
			'settings' => 'bangla_copyright_width', 
			'type'     => 'select',
			'choices'  => array(
				'default' => esc_html_x('Default', 'backend', 'bangla'),
				'small'   => esc_html_x('Small', 'backend', 'bangla'),
				'large'   => esc_html_x('Large', 'backend', 'bangla'),
				'expand'  => esc_html_x('Expand', 'backend', 'bangla'),
				0        => esc_html_x('Full', 'backend', 'bangla'),
			)
		));


		$this->wp_customize->add_setting('bangla_copyright_padding', array(
			'default' => 'small',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_copyright_padding', array(
			'label'    => esc_html_x('Padding', 'backend', 'bangla'),
			'section'  => 'copyright',
			'settings' => 'bangla_copyright_padding', 
			'type'     => 'select',
			'choices'  => array(
				'small'  => esc_html_x('Small', 'backend', 'bangla'),
				'medium' => esc_html_x('Medium', 'backend', 'bangla'),
				'large'  => esc_html_x('Large', 'backend', 'bangla'),
				'none'   => esc_html_x('None', 'backend', 'bangla'),
			)
		));



		$this->wp_customize->add_setting('bangla_copyright_text_custom_show', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control($this->wp_customize, 'bangla_copyright_text_custom_show',
	        array(
				'label'    => esc_html_x('Show Custom Copyright Text', 'backend', 'bangla'),
				'section'  => 'copyright',
				'settings' => 'bangla_copyright_text_custom_show',
				'type'     => 'checkbox',
	        )
		));
		
		//copyright Content
		$this->wp_customize->add_setting('bangla_copyright_text_custom', array(
			'default'           => 'Theme Designed by <a href="'.esc_url( esc_html_x( 'https://www.broxme_wphemes.com', 'backend', 'bangla')).' ">broxme_wphemes Ltd</a>',
			'sanitize_callback' => 'bangla_sanitize_textarea'
		));
		$this->wp_customize->add_control( new bangla_Customize_Textarea_Control( $this->wp_customize, 'bangla_copyright_text_custom', array(
			'label'           => esc_html_x('Copyright Text', 'backend', 'bangla'),
			'section'         => 'copyright',
			'settings'        => 'bangla_copyright_text_custom',
			'active_callback' => 'bangla_copyright_text_custom_show_check',
			'type'            => 'textarea',
		)));


		// Copyright Section
		$this->wp_customize->add_section('totop', array(
			'title' => esc_html_x('Go To Top', 'backend', 'bangla'),
			'description' => esc_html_x( 'Go to top show/hide, layout and style here.', 'backend', 'bangla' ),
			'priority' => 143
		));

		/*
		 * "go to top" link
		 */
		$this->wp_customize->add_setting('bangla_totop_show', array(
			'default' => 1,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control($this->wp_customize, 'bangla_totop_show',
	        array(
				'label'    => esc_html_x('Show "Go to top" link', 'backend', 'bangla'),
				'section'  => 'totop',
				'settings' => 'bangla_totop_show',
				'type'     => 'checkbox'
	        )
		));

		$this->wp_customize->add_setting('bangla_totop_bg_style', array(
			'default' => 'secondary',
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_totop_bg_style', array(
			'label'    => esc_html_x('Background Style', 'backend', 'bangla'),
			'section'  => 'totop',
			'settings' => 'bangla_totop_bg_style', 
			'type'     => 'select',
			'choices'  => array(
				'default'   => esc_html_x('Default (White)', 'backend', 'bangla'),
				'muted'     => esc_html_x('Muted', 'backend', 'bangla'),
				'primary'   => esc_html_x('Primary', 'backend', 'bangla'),
				'secondary' => esc_html_x('Secondary', 'backend', 'bangla'),
				//'media'     => esc_html_x('Image', 'backend', 'bangla'),
				//'custom'    => esc_html_x('Custom Color', 'backend', 'bangla'),
			),
			'active_callback' => 'bangla_totop_check',
		));

		$this->wp_customize->add_setting('bangla_totop_align', array(
			'default' => 'left',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_totop_align', array(
			'label'    => esc_html_x('Alignment', 'backend', 'bangla'),
			'description' => esc_html_x('Set go to top alignment from here.', 'backend', 'bangla'),
			'section'  => 'totop',
			'settings' => 'bangla_totop_align', 
			'type'     => 'select',
			'choices'  => array(
				'left'      => esc_html_x('Bottom Left', 'backend', 'bangla'),
				'center'    => esc_html_x('Bottom Center', 'backend', 'bangla'),
				'right'     => esc_html_x('Bottom Right', 'backend', 'bangla'),
			),
			'active_callback' => 'bangla_totop_check',
		)));

		$this->wp_customize->add_setting('bangla_totop_radius', array(
			'default' => 'circle',
			'sanitize_callback' => 'bangla_sanitize_choices'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_totop_radius', array(
			'label'    => esc_html_x('Alignment', 'backend', 'bangla'),
			'description' => esc_html_x('Set go to top alignment from here.', 'backend', 'bangla'),
			'section'  => 'totop',
			'settings' => 'bangla_totop_radius', 
			'type'     => 'select',
			'choices'  => array(
				0        => esc_html_x('Squire', 'backend', 'bangla'),
				'rounded' => esc_html_x('Rounded', 'backend', 'bangla'),
				'circle' => esc_html_x('Circle', 'backend', 'bangla'),
			),
			'active_callback' => 'bangla_totop_check',
		)));




		//Cookie bar section and settings
		$this->wp_customize->add_section('cookie', array(
			'title' => esc_html_x('Cookie Bar', 'backend', 'bangla'),
			'description' => esc_html_x( 'Show cookie accept notification on your website.', 'backend', 'bangla' ),
			'priority' => 150
		));


		$this->wp_customize->add_setting('bangla_cookie', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_cookie', array(
			'label'    => esc_html_x('Show Cookie Notification', 'backend', 'bangla'),
			'section'  => 'cookie',
			'settings' => 'bangla_cookie', 
			'type'     => 'select',
			'choices'  => array(
				1  => esc_html_x('Yes please!', 'backend', 'bangla'),
				0 => esc_html_x('No Need', 'backend', 'bangla'),
			)
		));


		// Add cookie background color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'bangla_cookie_background', array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'background-color' => array(
					'#cookie-bar',
				),
			)
		)));

		// Add cookie background color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'bangla_cookie_background', array(
			'label'           => esc_html_x( 'Background Color', 'backend', 'bangla' ),
			'section'         => 'cookie',
			//'active_callback' => 'bangla_bottom_bg_custom_color_check',
		)));

		// Add cookie text color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'bangla_cookie_text_color', array(
			'default'           => '#666666',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'#cookie-bar',
				),
			)
		)));

		// Add cookie text color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'bangla_cookie_text_color', array(
			'label'           => esc_html_x( 'Text Color', 'backend', 'bangla' ),
			'section'         => 'cookie',
			//'active_callback' => 'bangla_bottom_bg_custom_color_check',
		)));

		$this->wp_customize->add_setting('bangla_cookie_message', array(
			'default' => esc_html__( 'We use cookies to ensure that we give you the best experience on our website.', 'bangla' ),
			'sanitize_callback' => 'bangla_sanitize_text'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_cookie_message', array(
			'label'       => esc_html_x('Message', 'backend', 'bangla'),
			'description' => esc_html_x('Set cookie message from here.', 'backend', 'bangla'),
			'section'     => 'cookie',
			'settings'    => 'bangla_cookie_message', 
			'type'        => 'text',
		)));

		$this->wp_customize->add_setting('bangla_cookie_expire_days', array(
			'default' => 365,
			'sanitize_callback' => 'bangla_sanitize_text'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_cookie_expire_days', array(
			'label'       => esc_html_x('Expire Days', 'backend', 'bangla'),
			'description' => esc_html_x('Set how many days to keep the cookies', 'backend', 'bangla'),
			'section'     => 'cookie',
			'settings'    => 'bangla_cookie_expire_days', 
			'type'        => 'text',
		)));


		$this->wp_customize->add_setting('bangla_cookie_accept_button', array(
			'default' => 1,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_cookie_accept_button', array(
			'label'       => esc_html_x('Accept Button', 'backend', 'bangla'),
			'description' => esc_html_x('(Show accept button in cookie message area)', 'backend', 'bangla'),
			'section'     => 'cookie',
			'settings'    => 'bangla_cookie_accept_button', 
			'type'        => 'checkbox',
		)));

		$this->wp_customize->add_setting('bangla_cookie_decline_button', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_cookie_decline_button', array(
			'label'       => esc_html_x('Decline Button', 'backend', 'bangla'),
			'description' => esc_html_x('(Show decline button in cookie message area)', 'backend', 'bangla'),
			'section'     => 'cookie',
			'settings'    => 'bangla_cookie_decline_button', 
			'type'        => 'checkbox',
		)));


		$this->wp_customize->add_setting('bangla_cookie_policy_button', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_cookie_policy_button', array(
			'label'       => esc_html_x('Policy Button', 'backend', 'bangla'),
			'description' => esc_html_x('(Show policy button in cookie message area)', 'backend', 'bangla'),
			'section'     => 'cookie',
			'settings'    => 'bangla_cookie_policy_button', 
			'type'        => 'checkbox',
		)));

		$this->wp_customize->add_setting('bangla_cookie_policy_url', array(
			'default' => '/privacy-policy/',
			'sanitize_callback' => 'esc_url'
		));

		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_cookie_policy_url', array(
			'label'           => esc_html_x('Policy Page URL', 'backend', 'bangla'),
			'description'     => esc_html_x('Set how many days to keep the cookies', 'backend', 'bangla'),
			'section'         => 'cookie',
			'settings'        => 'bangla_cookie_policy_url', 
			'type'            => 'text',
			'active_callback' => 'bangla_cookie_policy_button_check',
		)));

		$this->wp_customize->add_setting('bangla_cookie_position', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_cookie_position', array(
			'label'       => esc_html_x('Show Message at Button', 'backend', 'bangla'),
			'section'     => 'cookie',
			'settings'    => 'bangla_cookie_position', 
			'type'        => 'checkbox',
		)));


		$this->wp_customize->add_setting('bangla_cookie_debug', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_checkbox'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_cookie_debug', array(
			'label'       => esc_html_x('Debug Mode', 'backend', 'bangla'),
			'description' => esc_html_x('(If you checked so cookie will not hide even you click accept button.)', 'backend', 'bangla'),
			'section'     => 'cookie',
			'settings'    => 'bangla_cookie_debug', 
			'type'        => 'checkbox',
		)));



		//Preloader section and settings
		$this->wp_customize->add_section('preloader', array(
			'title' => esc_html_x('Preloader', 'backend', 'bangla'),
			'description' => esc_html_x( 'Show preloader for pre-loading your website.', 'backend', 'bangla' ),
			'priority' => 150
		));


		$this->wp_customize->add_setting('bangla_preloader', array(
			'default' => 0,
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_preloader', array(
			'label'    => esc_html_x('Show Preloader', 'backend', 'bangla'),
			'section'  => 'preloader',
			'settings' => 'bangla_preloader', 
			'type'     => 'select',
			'choices'  => array(
				1  => esc_html_x('Yes please!', 'backend', 'bangla'),
				0 => esc_html_x('No Need', 'backend', 'bangla'),
			)
		));
		

		$this->wp_customize->add_setting('bangla_preloader_logo', array(
			'default' => 1,
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_preloader_logo', array(
			'label'    => esc_html_x('Show Logo', 'backend', 'bangla'),
			'section'  => 'preloader',
			'settings' => 'bangla_preloader_logo', 
			'type'     => 'select',
			'choices'  => array(
				1        => esc_html_x('Yes please!', 'backend', 'bangla'),
				0        => esc_html_x('No Need', 'backend', 'bangla'),
				'custom' => esc_html_x('Custom Logo', 'backend', 'bangla'),
			)
		));

		$this->wp_customize->add_setting( 'bangla_preloader_custom_logo' , array(
			'sanitize_callback' => 'esc_url'
		));
		$this->wp_customize->add_control( new WP_Customize_Image_Control( $this->wp_customize, 'bangla_preloader_custom_logo', array(
			'label'           => esc_html_x( 'Logo', 'backend', 'bangla' ),
			'section'         => 'preloader',
			'settings'        => 'bangla_preloader_custom_logo',
			'active_callback' => 'bangla_preloader_logo_check',
		)));


		$this->wp_customize->add_setting('bangla_preloader_text', array(
			'default' => 1,
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_preloader_text', array(
			'label'    => esc_html_x('Show Text', 'backend', 'bangla'),
			'section'  => 'preloader',
			'settings' => 'bangla_preloader_text', 
			'type'     => 'select',
			'choices'  => array(
				1        => esc_html_x('Yes please!', 'backend', 'bangla'),
				0        => esc_html_x('No Need', 'backend', 'bangla'),
				'custom' => esc_html_x('Custom Text', 'backend', 'bangla'),
			)
		));



		$this->wp_customize->add_setting('bangla_preloader_custom_text', array(
			'sanitize_callback' => 'bangla_sanitize_text'
		));
		$this->wp_customize->add_control(new WP_Customize_Control( $this->wp_customize, 'bangla_preloader_custom_text', array(
			'label'           => esc_html_x('Text', 'backend', 'bangla'),
			'section'         => 'preloader',
			'settings'        => 'bangla_preloader_custom_text', 
			'type'            => 'text',
			'active_callback' => 'bangla_preloader_text_check',
		)));

		// Add preloader background color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'bangla_preloader_background', array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'background-color' => array(
					'.pg-loading-screen',
				),
			)
		)));


		// Add preloader background color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'bangla_preloader_background', array(
			'label'           => esc_html_x( 'Background Color', 'backend', 'bangla' ),
			'section'         => 'preloader',
			//'active_callback' => 'bangla_bottom_bg_custom_color_check',
		)));

		// Add preloader text color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'bangla_preloader_text_color', array(
			'default'           => '#666666',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'color' => array(
					'.pg-loading-screen',
				),
			)
		)));

		// Add preloader text color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'bangla_preloader_text_color', array(
			'label'           => esc_html_x( 'Text Color', 'backend', 'bangla' ),
			'section'         => 'preloader',
			//'active_callback' => 'bangla_bottom_bg_custom_color_check',
		)));

		$this->wp_customize->add_setting('bangla_preloader_animation', array(
			'default' => 1,
			'sanitize_callback' => 'bangla_sanitize_choices'
		) );
		$this->wp_customize->add_control('bangla_preloader_animation', array(
			'label'    => esc_html_x('Show Animation', 'backend', 'bangla'),
			'section'  => 'preloader',
			'settings' => 'bangla_preloader_animation', 
			'type'     => 'select',
			'choices'  => array(
				1        => esc_html_x('Yes please!', 'backend', 'bangla'),
				0        => esc_html_x('No Need', 'backend', 'bangla'),
			)
		));

		// Add preloader text color setting.
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'bangla_preloader_animation_color', array(
			'default'           => '#666666',
			'sanitize_callback' => 'sanitize_hex_color',
			'css_map' => array(
				'background-color' => array(
					'.broxme_wp-spinner > div',
				),
			)
		)));

		// Add preloader text color control.
		$this->wp_customize->add_control( new WP_Customize_Color_Control( $this->wp_customize, 'bangla_preloader_animation_color', array(
			'label'           => esc_html_x( 'Animation Color', 'backend', 'bangla' ),
			'section'         => 'preloader',
			'active_callback' => 'bangla_preloader_animation_check',
		)));



		//typography section
		$this->wp_customize->add_panel('typography', array(
			'title' => esc_html_x('Typography', 'backend', 'bangla'),
			'priority' => 41
		));
		$this->wp_customize->add_section('typography_heading', array(
			'title' => esc_html_x('Heading', 'backend', 'bangla'),
			'panel' => 'typography',
		));
		$this->wp_customize->add_section('typography_menu', array(
			'title' => esc_html_x('Menu', 'backend', 'bangla'),
			'panel' => 'typography',
		));
		$this->wp_customize->add_section('typography_body', array(
			'title' => esc_html_x('Body', 'backend', 'bangla'),
			'panel' => 'typography',
		));
		$this->wp_customize->add_section('typography_global', array(
			'title' => esc_html_x('Global Settings', 'backend', 'bangla'),
			'panel' => 'typography',
		));

		//Add setting Heading font family settings
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'base_heading_font_family', array(
			'default'           => 'Roboto',
			'transport'         => 'postMessage',
			'sanitize_callback' => false,
		        'css_map' => array(
					'font-family' => array(
	                   'h1, h2, h3, h4, h5, h6',
					)
				)
		)));

		// Add Heading Font Control
		$this->wp_customize->add_control( 'base_heading_font_family', array(
			'label'    => esc_html_x( 'Heading Font Family', 'backend', 'bangla' ),
			'section'  => 'typography_heading',
			'settings' => 'base_heading_font_family',
			'type'     => 'text',
		));
                
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'base_heading_font_weight', array(
			'default'           => '600',
			'transport'         => 'postMessage',
			'sanitize_callback' => false,
		        'css_map' => array(
					'font-weight' => array(
	                    'h1, h2, h3, h4, h5, h6',
					)
				)
		)));

		$this->wp_customize->add_control( 'base_heading_font_weight', array(
			'label'    => esc_html_x( 'Heading Font Weight', 'backend', 'bangla' ),
			'section'  => 'typography_heading',
			'settings' => 'base_heading_font_weight',
			'type'     => 'text',
			'description' => esc_html_x( 'Important: Not all fonts support every font-weight.', 'backend', 'bangla' ),
		));


		//Add setting Heading font family settings
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'base_body_font_family', array(
			'default'           => 'Open Sans',
			'transport'         => 'postMessage',
			'sanitize_callback' => false,
		        'css_map' => array(
					'font-family' => array(
	                    'body'
					)
				)
		)));

		// Add Heading Font Control
		$this->wp_customize->add_control( 'base_body_font_family', array(
			'label'    => esc_html_x( 'Body Font Family', 'backend', 'bangla' ),
			'section'  => 'typography_body',
			'settings' => 'base_body_font_family',
			'type'     => 'text',
		));
                
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'base_body_font_weight', array(
			'default'           => '300',
			'transport'         => 'postMessage',
			'sanitize_callback' => false,
		        'css_map' => array(
					'font-weight' => array(
	                    'body'
					)
				)
		)));

		$this->wp_customize->add_control( 'base_body_font_weight', array(
			'label'    => esc_html_x( 'Body Font Weight', 'backend', 'bangla' ),
			'section'  => 'typography_body',
			'settings' => 'base_body_font_weight',
			'type'     => 'text',
			'description' => esc_html_x( 'Important: Not all fonts support every font-weight.', 'backend', 'bangla' ),
		));

		
		//Add setting Heading font family settings
		$this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'base_menu_font_family', array(
			'default'           => 'Roboto',
			'transport'         => 'postMessage',
			'sanitize_callback' => false,
		        'css_map' => array(
					'font-family' => array(
	                    '.broxme_wp-navbar-nav > li > a'
					)
				)
		)));

		// Add Menu Font Control
		$this->wp_customize->add_control( 'base_menu_font_family', array(
			'label'    => esc_html_x( 'Menu Font Family', 'backend', 'bangla' ),
			'section'  => 'typography_menu',
			'settings' => 'base_menu_font_family',
			'type'     => 'text',
		));
                
        $this->wp_customize->add_setting( new bangla_Customizer_Dynamic_CSS( $this->wp_customize, 'base_menu_font_weight', array(
			'default'           => '700',
			'transport'         => 'postMessage',
			'sanitize_callback' => false,
		        'css_map' => array(
					'font-weight' => array(
	                    '.broxme_wp-navbar-nav > li > a'
					)
				)
		)));

		$this->wp_customize->add_control( 'base_menu_font_weight', array(
			'label'       => esc_html_x( 'Menu Font Weight', 'backend', 'bangla' ),
			'section'     => 'typography_menu',
			'settings'    => 'base_menu_font_weight',
			'type'        => 'text',
			'description' => esc_html_x( 'Important: Not all fonts support every font-weight.', 'backend', 'bangla' ),
		));


		// Font subset
		$this->wp_customize->add_setting( 'google_font_subsets', array(
			'default' => 'latin',
			'sanitize_callback' => false,
		) );

		$this->wp_customize->add_control( new bangla_Customize_Multicheck_Control( $this->wp_customize, 'google_font_subsets', array(
			'label'    => esc_html_x( 'Font Subsets', 'backend', 'bangla' ),
			'section'  => 'typography_global',
			'settings' => 'google_font_subsets',
			'choices'  => array(
				'latin'        => 'latin',
				'latin-ext'    => 'latin-ext',
				'cyrillic'     => 'cyrillic',
				'cyrillic-ext' => 'cyrillic-ext',
				'greek'        => 'greek',
				'greek-ext'    => 'greek-ext',
				'vietnamese'   => 'vietnamese',
			),
		)));


		if ( isset( $this->wp_customize->selective_refresh ) ) {
			$this->wp_customize->selective_refresh->add_partial( 'blogname', array(
				'selector' => '.broxme_wp-header a.broxme_wp-logo-text',
				'container_inclusive' => false,
				'render_callback' => 'bangla_customize_partial_blogname',
			));
			$this->wp_customize->selective_refresh->add_partial( 'bangla_logo_default', array(
				'selector' => '.broxme_wp-header a.broxme_wp-logo-img',
				'container_inclusive' => false,
			));
			$this->wp_customize->selective_refresh->add_partial( 'blogdescription', array(
				'selector' => '.site-description',
				'container_inclusive' => false,
				'render_callback' => 'bangla_customize_partial_blogdescription',
			));

			$this->wp_customize->selective_refresh->add_partial( 'bangla_show_copyright_text', array(
				'selector' => '.copyright-txt',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'bangla_copyright_text_custom_show', array(
				'selector' => '.copyright-txt',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'bangla_search_position', array(
				'selector' => '.broxme_wp-header .broxme_wp-search',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'bangla_search_position', array(
				'selector' => '.broxme_wp-header a.broxme_wp-search-icon',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'bangla_toolbar_social', array(
				'selector' => '.broxme_wp-toolbar .social-link',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'bangla_toolbar_left_custom', array(
				'selector' => '.broxme_wp-toolbar-l .custom-text',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'bangla_toolbar_right_custom', array(
				'selector' => '.broxme_wp-toolbar-r .custom-text',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'bangla_woocommerce_cart', array(
				'selector' => '.broxme_wp-cart-popup',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'nav_menu_locations[primary]', array(
				'selector' => '.broxme_wp-header .broxme_wp-navbar-nav',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'nav_menu_locations[toolbar]', array(
				'selector' => '.broxme_wp-toolbar .broxme_wp-toolbar-menu',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'nav_menu_locations[footer]', array(
				'selector' => '.broxme_wp-copyright .broxme_wp-copyright-menu',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'bangla_top_link', array(
				'selector' => '.broxme_wp-totop-scroller',
				'container_inclusive' => false,
			));


			$this->wp_customize->selective_refresh->add_partial( 'bangla_mobile_offcanvas_style', array(
				'selector' => '.broxme_wp-header-mobile .broxme_wp-navbar-toggle',
				'container_inclusive' => false,
			));

			$this->wp_customize->selective_refresh->add_partial( 'bangla_titlebar_layout', array(
				'selector' => '.broxme_wp-titlebar h1',
				'container_inclusive' => false,
			));
		}
	}


	public function bangla_toolbar_left_elements() {
		$toolbar_elements = array();
		$description      = get_bloginfo( 'description', 'display' );
		if (function_exists('icl_object_id')) {
			$toolbar_elements['wpml'] = esc_html_x( 'Language Switcher', 'backend', 'bangla' );
		}
		if ($description) {
			$toolbar_elements['tagline'] = esc_html_x('Tagline', 'backend', 'bangla');
		}
		if (has_nav_menu('toolbar')) {
			$toolbar_elements['menu'] = esc_html_x('Toolbar Menu', 'backend', 'bangla');
		}
		$toolbar_elements['social'] = esc_html_x('Social Link', 'backend', 'bangla');
		$toolbar_elements['custom-left'] = esc_html_x('Custom Text', 'backend', 'bangla');
		return $toolbar_elements;
	}

	public function bangla_toolbar_right_elements() {
		$toolbar_elements = array();
		$description      = get_bloginfo( 'description', 'display' );
		if (function_exists('icl_object_id')) {
			$toolbar_elements['wpml'] = esc_html_x( 'Language Switcher', 'backend', 'bangla' );
		}
		if ($description) {
			$toolbar_elements['tagline'] = esc_html_x('Tagline', 'backend', 'bangla');
		}
		if (has_nav_menu('toolbar')) {
			$toolbar_elements['menu'] = esc_html_x('Toolbar Menu', 'backend', 'bangla');
		}
		$toolbar_elements['social'] = esc_html_x('Social Link', 'backend', 'bangla');
		$toolbar_elements['custom-right'] = esc_html_x('Custom Text', 'backend', 'bangla');
		return $toolbar_elements;
	}

	public function bangla_font_weight() {
		$font_weight = array(
				''    => esc_html_x( 'Default', 'backend', 'bangla' ),
				'100' => esc_html_x( 'Extra Light: 100', 'backend', 'bangla' ),
				'200' => esc_html_x( 'Light: 200', 'backend', 'bangla' ),
				'300' => esc_html_x( 'Book: 300', 'backend', 'bangla' ),
				'400' => esc_html_x( 'Normal: 400', 'backend', 'bangla' ),
				'600' => esc_html_x( 'Semibold: 600', 'backend', 'bangla' ),
				'700' => esc_html_x( 'Bold: 700', 'backend', 'bangla' ),
				'800' => esc_html_x( 'Extra Bold: 800', 'backend', 'bangla' ),
			);
		return $font_weight;
	}

	/**
	 * Render the site title for the selective refresh partial.
	 *
	 * @since bangla 1.0
	 * @see bangla_customize_register_colors()
	 *
	 * @return void
	 */
	public function bangla_customize_partial_blogname() {
		bloginfo( 'name' );
	}

	/**
	 * Render the site tagline for the selective refresh partial.
	 *
	 * @since bangla 1.0
	 * @see bangla_customize_register_colors()
	 *
	 * @return void
	 */
	public function bangla_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}

	/**
	 * Cache the rendered CSS after the settings are saved in the DB.
	 * This is purely a performance improvement.
	 *
	 * Used by hook: add_action( 'customize_save_after' , array( $this, 'cache_rendered_css' ) );
	 *
	 * @return void
	 */
	public function cache_rendered_css() {
 		set_theme_mod( 'cached_css', $this->render_css() );
	}

	/**
	 * Get the dimensions of the logo image when the setting is saved
	 * This is purely a performance improvement.
	 *
	 * Used by hook: add_action( 'customize_save_logo_img' , array( $this, 'save_logo_dimensions' ), 10, 1 );
	 *
	 * @return void
	 */
	public static function save_logo_dimensions( $setting ) {
		$logo_width_height = '';
		$img_data          = getimagesize( esc_url( $setting->post_value() ) );

		if ( is_array( $img_data ) ) {
			$logo_width_height = $img_data[3];
		}

		set_theme_mod( 'logo_width_height', $logo_width_height );
	}

	/**
	 * Render the CSS from all the settings which are of type `bangla_Customizer_Dynamic_CSS`
	 *
	 * @return string text/css
	 */
	public function render_css() {
		$out = '';
		foreach ( $this->get_dynamic_css_settings() as $setting ) {
			$out .= $setting->render_css();
		}

		return $out;
	}

        /**
	 * Get only the CSS settings of type `bangla_Customizer_Dynamic_CSS`.
	 *
	 * @see is_dynamic_css_setting
	 * @return array
	 */
	public function get_dynamic_css_settings() {
		return array_filter( $this->wp_customize->settings(), array( $this, 'is_dynamic_css_setting' ) );
	}

	/**
	 * Helper conditional function for filtering the settings.
	 *
	 * @see
	 * @param  mixed  $setting
	 * @return boolean
	 */
	protected static function is_dynamic_css_setting( $setting ) {
		return is_a( $setting, 'bangla_Customizer_Dynamic_CSS' );
	}

	/**
	 * Dynamically generate the JS for previewing the settings of type `bangla_Customizer_Dynamic_CSS`.
	 *
	 * This function is better for the UX, since all the color changes are transported to the live
	 * preview frame using the 'postMessage' method. Since the JS is generated on-the-fly and we have a single
	 * entry point of entering settings along with related css properties and classes, we cannnot forget to
	 * include the setting in the customizer itself. Neat, man!
	 *
	 * @return string text/javascript
	 */
	public function customize_footer_js() {
		$settings = $this->get_dynamic_css_settings();

		ob_start();
		?>

			<script type="text/javascript">
				'use strict';
				//bangla customizer color live preview
				( function( $ ) {
				    var style = []
			
				<?php
					foreach ( $settings as $key_id => $setting ) :
				?>
					style['<?php echo esc_attr($key_id) ?>'] = '';
					wp.customize( '<?php echo esc_attr($key_id); ?>', function( value ) {
					   
						value.bind( function( newval ) {
						     style['<?php echo esc_attr($key_id) ?>'] = '';
						<?php
							foreach ( $setting->get_css_map() as $css_prop_raw => $css_selectors ) {
								
								extract( $setting->filter_css_property( $css_prop_raw ) );
                                if($lighten){
                                    echo 'newval = LightenDarkenColor(newval,'.$lighten.' ); ';
                                }
								// background image needs a little bit different treatment
								if ( 'background-image' === $css_prop ) {
									echo 'newval = "url(\'" + newval + "\')";' . PHP_EOL;
								}else if ( 'font-family' === $css_prop ) {
                                    echo 'WebFont.load({
                                        google: {
                                          families: [newval]
                                        }
                                    });newval = \'"\'+newval+\'"\';
                                        newval = newval.replace(",", \'","\'); ';
                                }
								printf( 'style["%1$s"]  += "%2$s{ %3$s: "+ newval + " }" %4$s ' .  '+"\r\n"; '."\r\n",$key_id, $setting->plain_selectors_for_all_groups( $css_prop_raw ), $css_prop, PHP_EOL);
							}
						?>
						   add_style(style); 	    
						});
						
					} );
					<?php
					foreach ($setting->get_css_map() as $css_prop_raw => $css_selectors) {
	                                      
					    extract($setting->filter_css_property($css_prop_raw));
						if($lighten){
							$value = $value;
						} else {
							$value = $setting->render_css_save();
						}
					   
					    if ( 'background-image' === $css_prop ) {
							$value = 'url(\''.$value.'\');';
					    }
					    printf('style["%1$s"]  += "%2$s{ %3$s: %5$s }" %4$s ' . '+"\r\n"; ' . "\r\n", $key_id, $setting->plain_selectors_for_all_groups($css_prop_raw),
						    $css_prop, PHP_EOL, $value);
					}
					?>
					add_style(style);
				<?php
					endforeach;
					?>
				    function add_style(style){
					    var str_style = '';
					    var key;
					    for(key in style){
							if(style[key]){
							    str_style += '/*' + key + "*/\r\n";
							    str_style += style[key] + "\r\n";
							}
					    }
					    $('#custome_live_preview').html(str_style)

				    }
	                                
                    function LightenDarkenColor(col, amt) {  
                        var usePound = false;
                        if (col[0] == "#") {
                            col = col.slice(1);
                            usePound = true;
                        }
                        var num = parseInt(col,16);
                        var r = (num >> 16) + amt;
                        if (r > 255) r = 255;
                        else if  (r < 0) r = 0;
                        var b = ((num >> 8) & 0x00FF) + amt;
                        if (b > 255) b = 255;
                        else if  (b < 0) b = 0;
                        var g = (num & 0x0000FF) + amt;
                        if (g > 255) g = 255;
                        else if (g < 0) g = 0;
                        return (usePound?"#":"") + (g | (b << 8) | (r << 16)).toString(16);
                    }
				} )( jQuery );
			</script>
		<?php
		echo ob_get_clean();
	}
	
    public function hook_custom_css() { ?>
    	<script src="//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
		<style id='custome_live_preview'></style>
    	<?php
    }

}