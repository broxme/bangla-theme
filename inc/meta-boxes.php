<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 * You also should read the changelog to know what has been changed before updating.
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/docs/define-meta-boxes
 */

/********************* Meta Box Definitions ***********************/

add_action( 'admin_init', 'rw_register_meta_boxes' );
function rw_register_meta_boxes() {
	
	// load is_plugin_active() function if no available
	if (!function_exists('is_plugin_active')) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
	}
	global $meta_boxes;

	$prefix = 'bangla_';
	$meta_boxes = [];

	/* ----------------------------------------------------- 
	
	if(is_plugin_active('bangla-faq/bangla-faq.php')){ 

		$types = get_terms('faq_filter', 'hide_empty=0');
		$types_array[0] = 'All categories';
		if($types) {
			foreach($types as $type) {
				$types_array[$type->term_id] = $type->name;
			}
		}

	}
    ------------------------------------------------------------*/

	// SIDEBAR ARRAY
	function get_sidebars_array() {
	    global $wp_registered_sidebars;
	    $list_sidebars = [];
	    foreach ( $wp_registered_sidebars as $sidebar ) {
	        $list_sidebars[$sidebar['id']] = $sidebar['name'];
	    }
	    // remove them from the list for better understand purpose
	    unset($list_sidebars['footer-widgets']);
	    unset($list_sidebars['footer-widgets-2']);
	    unset($list_sidebars['footer-widgets-3']);
	    unset($list_sidebars['footer-widgets-4']);
	    unset($list_sidebars['offcanvas']);
	    unset($list_sidebars['fixed-left']);
	    unset($list_sidebars['fixed-right']);
	    unset($list_sidebars['headerbar']);
	    unset($list_sidebars['drawer']);
	    unset($list_sidebars['bottom']);
	    return $list_sidebars;
	}


	/* ----------------------------------------------------- */
	// Blog Categories Filter Array
	$blog_options = []; // fixes a PHP warning when no blog posts at all.
	$blog_categories = get_categories();
	if($blog_categories) {
		foreach ($blog_categories as $category) {
			$blog_options[$category->slug] = $category->name;
		}
	}
	
 
	/* ----------------------------------------------------- */
	// Page Settings
	/* ----------------------------------------------------- */
	
	$meta_boxes[] = array(
		'id'       => 'pagesettings',
		'title'    => 'Page Settings',
		'pages'    => array( 'page' ),
		'context'  => 'normal',
		'priority' => 'high',

		'tabs'      => array(
			'layout' => array(
                'label' => esc_html__('Layout', 'bangla'),
            ),
            'titlebar' => array(
                'label' => esc_html__('Page Titlebar', 'bangla'),
            ),
            'footer' => array(
                'label' => esc_html__('Footer', 'bangla'),
            ),
            'blog'  => array(
                'label' => esc_html__( 'Blog', 'bangla'),
            ),
        ),

        // Tab style: 'default', 'box' or 'left'. Optional
        'tab_style' => 'default',
	
		// List of meta fields
		'fields' => array(
			array(
					'name'		=> 'Toolbar',
					'id'		=> $prefix . "toolbar",
					'type'		=> 'select',
					'options'	=> array(
						null        => esc_html_x('Default (as customizer)', 'backend', 'bangla'),
						true		=> 'Enable',
						false		=> 'Disable'
					),
					'multiple' => false,
					'std'      => null,
					'desc'     => 'Enable or disable the toolbar for this page.',
					'tab'      => 'layout',
			),

			array(
				'name'		=> esc_html_x('Page Layout', 'backend', 'bangla'),
				'id'		=> $prefix . "page_layout",
				'type'		=> 'select',
				'options'	=> array(
					'default'       => esc_html_x('Default', 'backend', 'bangla'),
					'full'          => esc_html_x('Fullwidth', 'backend', 'bangla'),
					'sidebar-right' => esc_html_x('Sidebar Right', 'backend', 'bangla'),
					'sidebar-left'  => esc_html_x('Sidebar Left', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => array( 'default' ),
				'desc'     => wp_kses(_x('<strong>Default:</strong> For usage normal Text Pages<br /> <strong>Full Width:</strong> For pages using Elementor (commonly used)<br /> <strong>Sidebar Left:</strong> Sidebar Left Template<br /> <strong>Sidebar Right:</strong> Sidebar Right Template', 'backend', 'bangla'), array('strong'=>array(), 'br'=>array())),
				'tab'      => 'layout',
			),

			array(
				'name'     => esc_html_x('Sidebar', 'backend', 'bangla'),
				'id'       => $prefix . "sidebar",
				'type'     => 'select',
				'options'  => get_sidebars_array(),
				'multiple' => false,
				'std'      => array( 'show' ),
				'desc'     => esc_html_x('Select the sidebar you wish to display on this page.', 'backend', 'bangla'),
				'tab'      => 'layout',
				'visible'  => array($prefix . 'page_layout', 'starts with', 'sidebar'),
			),

			array(
				'name'		=> esc_html_x('Header Layout', 'backend', 'bangla'),
				'id'		=> $prefix . "header_layout",
				'type'		=> 'image_select',
				'options'	=> array(
					null                   => get_template_directory_uri().'/admin/images/header-default.png',
					'horizontal-left'      => get_template_directory_uri().'/admin/images/header-horizontal-left.png',
					'horizontal-center'    => get_template_directory_uri().'/admin/images/header-horizontal-center.png',
					'horizontal-right'     => get_template_directory_uri().'/admin/images/header-horizontal-right.png',
					'stacked-center-a'     => get_template_directory_uri().'/admin/images/header-stacked-center-a.png',
					'stacked-center-b'     => get_template_directory_uri().'/admin/images/header-stacked-center-b.png',
					'stacked-center-split' => get_template_directory_uri().'/admin/images/header-stacked-center-split.png',
					'stacked-left-a'       => get_template_directory_uri().'/admin/images/header-stacked-left-a.png',
					'stacked-left-b'       => get_template_directory_uri().'/admin/images/header-stacked-left-b.png',
					'toggle-offcanvas'     => get_template_directory_uri().'/admin/images/header-toggle-offcanvas.png',
					'toggle-modal'         => get_template_directory_uri().'/admin/images/header-toggle-modal.png',
					'side-left'            => get_template_directory_uri().'/admin/images/header-side-left.png',
					'side-right'           => get_template_directory_uri().'/admin/images/header-side-right.png',
				),
				'multiple' => false,
				'std'      => array( null ),
				'desc'     => esc_html_x('Override the header style for this page.', 'backend', 'bangla'),
				'tab'      => 'layout',
			),

			array(
				'name'             => esc_html_x('Custom Logo', 'backend', 'bangla'),
				'id'               => $prefix . "custom_logo",
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
				//'image_size'	   => 'medium',
				'desc'             => esc_html_x('Upload Custom Logo for this page.', 'backend', 'bangla'),
				'tab'              => 'layout',
			),
			
			array(
				'name'		=> 'Background Style',
				'id'		=> $prefix . "header_bg_style",
				'type'		=> 'select',
				'options'	=> array(
					null        => esc_html_x('Default (as customizer)', 'backend', 'bangla'),
					'default'   => esc_html_x('Default', 'backend', 'bangla'),
					'muted'     => esc_html_x('Muted', 'backend', 'bangla'),
					'primary'   => esc_html_x('Primary', 'backend', 'bangla'),
					'secondary' => esc_html_x('Secondary', 'backend', 'bangla'),
					'media'     => esc_html_x('Image', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => null,
				'desc'     => 'Select your header style from here.',
				'tab'      => 'layout',
				'hidden' => array($prefix . 'header_layout', '=', false ),
			),
			array(
				'name'             => 'Background Image',
				'id'               => $prefix . "header_bg_img",
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
				'desc'             => 'Upload header Image for the header Style.',
				'tab'              => 'layout',
				'visible'           => array($prefix . 'header_bg_style', '=', 'media'),
			),
			array(
				'name'		=> 'Header Text Style',
				'id'		=> $prefix . "header_txt_style",
				'type'		=> 'select',
				'options'	=> array(
					null    => esc_html_x('Default (as customizer)', 'backend', 'bangla'),
					'light' => esc_html_x('Light', 'backend', 'bangla'),
					'dark'  => esc_html_x('Dark', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => array( null ),
				'desc'     => 'Select your header text style from here.',
				'tab'      => 'layout',
				'hidden' => array($prefix . 'header_layout', '=', false ),
			),
			array(
				'name'		=> 'Header Transparent',
				'id'		=> $prefix . "header_transparent",
				'type'		=> 'select',
				'options'	=> array(
					null    => esc_html_x('Default (as customizer)', 'backend', 'bangla'),
					'no'    => esc_html_x('No', 'backend', 'bangla'),
					'light' => esc_html_x('Overlay (Light)', 'backend', 'bangla'),
					'dark'  => esc_html_x('Overlay (Dark)', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => array( null ),
				'desc'     => 'Select your header transparent style from here.',
				'tab'      => 'layout',
				'hidden' => array($prefix . 'header_layout', 'in', ['side-left', 'side-right', false] ),
			),
			array(
				'name'		=> esc_html_x('Header Sticky', 'backend', 'bangla'),
				'id'		=> $prefix . "header_sticky",
				'type'		=> 'select',
				'options'	=> array(
					null     => esc_html_x('Default (as customizer)', 'backend', 'bangla'),
					'no'     => esc_html_x('No', 'backend', 'bangla'),
					'sticky' => esc_html_x('Sticky', 'backend', 'bangla'),
					'smart'  => esc_html_x('Smart Sticky', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => array( null ),
				'desc'     => esc_html_x('Override the header type for this page.', 'backend', 'bangla'),
				'tab'      => 'layout',
				'hidden' => array($prefix . 'header_layout', 'in', ['side-left', 'side-right'] ),
			),

			array(
				'name'		=> 'Titlebar',
				'id'		=> $prefix . "titlebar",
				'type'		=> 'select',
				'options'	=> array(
					'show' => 'Enable',
					'hide' => 'Disable'
				),
				'multiple' => false,
				'std'      => array( true ),
				'desc'     => 'Enable or disable the Titlebar on this Page.',
				'tab'      => 'titlebar',
			),
			array(
				'name'		=> 'Layout Alignment',
				'id'		=> $prefix . "titlebar_layout",
				'type'		=> 'select',
				'options'	=> array(
					'default' => esc_html_x('Default (set in Theme Customizer)', 'backend', 'bangla'),
					'left'    => esc_html_x('Left Align', 'backend', 'bangla'),
					'center'  => esc_html_x('Center Align', 'backend', 'bangla'),
					'right'   => esc_html_x('Right Align', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => array( 'default' ),
				'desc'     => 'Choose your Titlebar Layout for this Page',
				'tab'      => 'titlebar',
				'hidden' => array($prefix . 'titlebar', '=', 'hide'),
			),
			array(
				'name'		=> 'Background Style',
				'id'		=> $prefix . "titlebar_bg_style",
				'type'		=> 'select',
				'options'	=> array(
					null        => esc_html_x('Default (as customizer)', 'backend', 'bangla'),
					'default'   => esc_html_x('Default', 'backend', 'bangla'),
					'muted'     => esc_html_x('Muted', 'backend', 'bangla'),
					'primary'   => esc_html_x('Primary', 'backend', 'bangla'),
					'secondary' => esc_html_x('Secondary', 'backend', 'bangla'),
					'media'     => esc_html_x('Image', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => array( 'light' ),
				'desc'     => 'Select your titlebar style from here.',
				'tab'      => 'titlebar',
				'hidden' => array($prefix . 'titlebar_layout', '=', 'default'),
			),
			array(
					'name'             => 'Background Image',
					'id'               => $prefix . "titlebar_bg_img",
					'type'             => 'image_advanced',
					'max_file_uploads' => 1,
					'desc'             => 'Upload Titlebar Image for the Titlebar Style.',
					'tab'              => 'titlebar',
					'visible'           => array($prefix . 'titlebar_bg_style', '=', 'media'),
			),
			array(
					'name'		=> 'Text Color',
					'id'		=> $prefix . "titlebar_txt_style",
					'type'		=> 'select',
					'options'	=> array(
						'0'     => esc_html_x('Default', 'backend', 'bangla'),
						'light' => esc_html_x('Light', 'backend', 'bangla'),
						'dark'  => esc_html_x('Dark', 'backend', 'bangla'),
					),
					'multiple' => false,
					'std'      => array( '0' ),
					'desc'     => 'Select your titlebar text color from here.',
					'tab'      => 'titlebar',
					'hidden' => array($prefix . 'titlebar_layout', '=', 'default'),
			),

			array(
					'name'		=> 'Footer Widgets',
					'id'		=> $prefix . "footer_widgets",
					'type'		=> 'select',
					'options'	=> array(
						null        => esc_html_x('Default (as customizer)', 'backend', 'bangla'),
						'show'		=> 'Enable',
						'hide'		=> 'Disable'
					),
					'multiple' => false,
					'std'      => null,
					'desc'     => 'Enable or disable the Footer Widgets on this Page.',
					'tab'      => 'footer',
			),
			array(
					'name'		=> 'Copyright',
					'id'		=> $prefix . "copyright",
					'type'		=> 'select',
					'options'	=> array(
						null        => esc_html_x('Default (as customizer)', 'backend', 'bangla'),
						'show'		=> 'Enable',
						'hide'		=> 'Disable'
					),
					'multiple' => false,
					'std'      => null,
					'desc'     => 'Enable or disable the Footer Copyright Section on this Page.',
					'tab'      => 'footer',
			),
			array(
				'name'       => 'Blog Categories',
				'id'         => $prefix . "blog_categories",
				'type'       => 'taxonomy_advanced',
				'taxonomy'   => 'category',
				'field_type' => 'checkbox_tree',
				'desc'       => 'If nothing is selected, it will show Items from <strong>ALL</strong> categories.',
				'tab'        => 'blog',
			),

			array(
				'name'		=> esc_html_x('Grid Column (Large)', 'backend', 'bangla'),
				'id'		=> $prefix.'blog_columns',
				'type'		=> 'select',
				'options'	=> array(
					'1'   => esc_html_x('1 Column', 'backend', 'bangla'),
					'2'   => esc_html_x('2 Columns', 'backend', 'bangla'),
					'3'   => esc_html_x('3 Columns', 'backend', 'bangla'),
					'4'   => esc_html_x('4 Columns', 'backend', 'bangla'),
					//'5' => esc_html_x('5 Columns', 'backend', 'bangla'),
					//'6' => esc_html_x('6 Columns', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => array( '1' ),
				'tab'      => 'blog',
				//'hidden' => array('jetpack_tm_view', '=', 'list'),
			),

			array(
				'name'		=> esc_html_x('Grid Column (Medium)', 'backend', 'bangla'),
				'id'		=> $prefix.'blog_columns_medium',
				'type'		=> 'select',
				'options'	=> array(
					'1'   => esc_html_x('1 Column', 'backend', 'bangla'),
					'2'   => esc_html_x('2 Columns', 'backend', 'bangla'),
					'3'   => esc_html_x('3 Columns', 'backend', 'bangla'),
					'4'   => esc_html_x('4 Columns', 'backend', 'bangla'),
					//'5' => esc_html_x('5 Columns', 'backend', 'bangla'),
					//'6' => esc_html_x('6 Columns', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => array( '1' ),
				'tab'      => 'blog',
				//'hidden' => array('jetpack_tm_view', '=', 'list'),
			),

			array(
				'name'     => esc_html_x('Grid Column (Small)', 'backend', 'bangla'),
				'id'       => $prefix.'blog_columns_small',
				'type'     => 'select',
				'std'      => array( '1' ),
				'tab'      => 'blog',
				//'hidden' => array('jetpack_tm_view', '=', 'list'),
				'options'  => array(
					'1' => esc_html_x('1 Column', 'backend', 'bangla'),
					'2' => esc_html_x('2 Columns', 'backend', 'bangla'),
					'3' => esc_html_x('3 Columns', 'backend', 'bangla'),
					//'4' => esc_html_x('4 Columns', 'backend', 'bangla'),
					//'5' => esc_html_x('5 Columns', 'backend', 'bangla'),
					//'6' => esc_html_x('6 Columns', 'backend', 'bangla'),
				),
			),

			array(
				'name'  => esc_html_x('Limit', 'backend', 'bangla' ),
				'id'    => $prefix.'blog_limit',
				'desc'  => esc_html_x('Enter limit number for how much item you want to show per page.', 'backend', 'bangla' ),
				'clone' => false,
				'type'  => 'text',
				'std'   => '10',
				'tab'   => 'blog'
			),





			// Testimonials columns control for page
			array(
				'name'		=> esc_html_x('Grid Column (Large)', 'backend', 'bangla'),
				'id'		=> 'jetpack_tm_columns',
				'type'		=> 'select',
				'options'	=> array(
					'2'		=> esc_html_x('2 Columns', 'backend', 'bangla'),
					'3'		=> esc_html_x('3 Columns', 'backend', 'bangla'),
					'4'		=> esc_html_x('4 Columns', 'backend', 'bangla'),
					'5'		=> esc_html_x('5 Columns', 'backend', 'bangla'),
					'6'		=> esc_html_x('6 Columns', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => array( '3' ),
				'tab'      => 'testimonials',
				//'hidden' => array('jetpack_tm_view', '=', 'list'),
			),

			array(
				'name'		=> esc_html_x('Grid Column (Medium)', 'backend', 'bangla'),
				'id'		=> 'jetpack_tm_columns_medium',
				'type'		=> 'select',
				'options'	=> array(
					'1'		=> esc_html_x('1 Column', 'backend', 'bangla'),
					'2'		=> esc_html_x('2 Columns', 'backend', 'bangla'),
					'3'		=> esc_html_x('3 Columns', 'backend', 'bangla'),
					'4'		=> esc_html_x('4 Columns', 'backend', 'bangla'),
					'5'		=> esc_html_x('5 Columns', 'backend', 'bangla'),
					'6'		=> esc_html_x('6 Columns', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => array( '2' ),
				'tab'      => 'testimonials',
				//'hidden' => array('jetpack_tm_view', '=', 'list'),
			),

			array(
				'name'     => esc_html_x('Grid Column (Small)', 'backend', 'bangla'),
				'id'       => 'jetpack_tm_columns_small',
				'type'     => 'select',
				'std'      => array( '1' ),
				'tab'      => 'testimonials',
				//'hidden' => array('jetpack_tm_view', '=', 'list'),
				'options'  => array(
					'1' => esc_html_x('1 Column', 'backend', 'bangla'),
					'2' => esc_html_x('2 Columns', 'backend', 'bangla'),
					'3' => esc_html_x('3 Columns', 'backend', 'bangla'),
					'4' => esc_html_x('4 Columns', 'backend', 'bangla'),
					'5' => esc_html_x('5 Columns', 'backend', 'bangla'),
					'6' => esc_html_x('6 Columns', 'backend', 'bangla'),
				),
			),
		)
	);

	/* ----------------------------------------------------- */
	// Blog Metaboxes
	/* ----------------------------------------------------- */

	$meta_boxes[] = array(
		'id'       => 'pagesettings',
		'title'    => esc_html_x('Blog Post Settings', 'backend', 'bangla' ),
		'pages'    => array( 'post' ),
		'context'  => 'normal',
		'priority' => 'high',
		'tabs'     => array(
            'blog_post'  => array(
                'label' => esc_html__( 'Post Settings', 'bangla'),
            ),
            'gallery'  => array(
                'label' => esc_html__( 'Gallery Settings', 'bangla'),
            ),
            'video'  => array(
                'label' => esc_html__( 'Video Settings', 'bangla'),
            ),
            'audio'  => array(
                'label' => esc_html__( 'Audio Settings', 'bangla'),
            ),
            'link'  => array(
                'label' => esc_html__( 'Link Settings', 'bangla'),
            ),
            'quote'  => array(
                'label' => esc_html__( 'Quote Settings', 'bangla'),
            ),
        ),
        // Tab style: 'default', 'box' or 'left'. Optional
        'tab_style' => 'default',
	
		// List of meta fields
		'fields' => array(
			array(
				'name'		=> 'Titlebar',
				'id'		=> $prefix . "titlebar",
				'type'		=> 'select',
				'options'	=> array(
					'show' => 'Enable',
					'hide' => 'Disable'
				),
				'multiple' => false,
				'std'      => array( true ),
				'desc'     => 'Enable or disable the Titlebar on this Page.',
				'tab'      => 'blog_post',
			),
			array(
				'name'		=> 'Layout Alignment',
				'id'		=> $prefix . "titlebar_layout",
				'type'		=> 'select',
				'options'	=> array(
					'default' => esc_html_x('Default (set in Theme Customizer)', 'backend', 'bangla'),
					'left'    => esc_html_x('Left Align', 'backend', 'bangla'),
					'center'  => esc_html_x('Center Align', 'backend', 'bangla'),
					'right'   => esc_html_x('Right Align', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => array( 'default' ),
				'desc'     => 'Choose your Titlebar Layout for this Page',
				'tab'      => 'blog_post',
				'hidden' => array($prefix . 'titlebar', '=', 'hide'),
			),
			array(
				'name'		=> 'Background Style',
				'id'		=> $prefix . "titlebar_bg_style",
				'type'		=> 'select',
				'options'	=> array(
					'default'   => esc_html_x('Default', 'backend', 'bangla'),
					'muted'     => esc_html_x('Muted', 'backend', 'bangla'),
					'primary'   => esc_html_x('Primary', 'backend', 'bangla'),
					'secondary' => esc_html_x('Secondary', 'backend', 'bangla'),
					'media'     => esc_html_x('Image', 'backend', 'bangla'),
					//'video'     => esc_html_x('Video', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => array( 'light' ),
				'desc'     => 'Select your titlebar style from here.',
				'tab'      => 'blog_post',
				'hidden' => array($prefix . 'titlebar_layout', '=', 'default'),
			),
			array(
				'name'             => 'Background Image',
				'id'               => $prefix . "titlebar_bg_img",
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
				'desc'             => 'Upload Titlebar Image for the Titlebar Style.',
				'tab'              => 'blog_post',
				'visible'          => array($prefix . 'titlebar_bg_style', '=', 'media'),
			),
			array(
				'name'		=> 'Text Color',
				'id'		=> $prefix . "titlebar_txt_style",
				'type'		=> 'select',
				'options'	=> array(
					'0'     => esc_html_x('Default', 'backend', 'bangla'),
					'light' => esc_html_x('Light', 'backend', 'bangla'),
					'dark'  => esc_html_x('Dark', 'backend', 'bangla'),
				),
				'multiple' => false,
				'std'      => array( '0' ),
				'desc'     => 'Select your titlebar text color from here.',
				'tab'      => 'blog_post',
				'hidden' => array($prefix . 'titlebar_layout', '=', 'default'),
			),
			array(
				'name'     => esc_html_x('Hide Featured Image?', 'backend', 'bangla' ),
				'id'       => $prefix . "hideimage",
				'type'     => 'checkbox',
				'multiple' => false,
				'desc'     => esc_html_x('Check this if you want to hide the Featured Image / Gallery on the Blog Detail Page', 'backend', 'bangla' ),
				'tab'      => 'blog_post',
			),

			// Post Format Gallery
			array(
				'name'             => esc_html_x('Gallery Images', 'backend', 'bangla' ),
				'desc'             => esc_html_x('You can upload up to 30 gallery images for a slideshow', 'backend', 'bangla' ),
				'id'               => $prefix . 'blog_gallery',
				'type'             => 'image_advanced',
				'max_file_uploads' => 30,
				'tab'              => 'gallery'
			),

			// Post Format Audio
			array(
				'name'  => esc_html_x('Audio Embed Code', 'backend', 'bangla' ),
				'id'    => $prefix . 'blog_audio',
				'desc'  => esc_html_x('Please enter the Audio Embed Code here.', 'backend', 'bangla' ),
				'clone' => false,
				'type'  => 'textarea',
				'std'   => '',
				'tab'   => 'audio'
			),

			// Post Format Link
			array(
				'name'  => esc_html_x('URL', 'backend', 'bangla' ),
				'id'    => $prefix . 'blog_link',
				'desc'  => esc_html_x('Enter a URL for your link post format. (Don\'t forget the http://)', 'backend', 'bangla' ),
				'clone' => false,
				'type'  => 'text',
				'std'   => '',
				'tab'   => 'link'
			),

			// Post Format Quote
			array(
				'name'  => esc_html_x('Quote', 'backend', 'bangla' ),
				'id'    => $prefix . 'blog_quote',
				'desc'  => esc_html_x('Please enter the text for your quote here.', 'backend', 'bangla' ),
				'clone' => false,
				'type'  => 'textarea',
				'std'   => '',
				'tab'   => 'quote'
			),
			array(
				'name'  => esc_html_x('Quote Source', 'backend', 'bangla' ),
				'id'    => $prefix . 'blog_quotesrc',
				'desc'  => esc_html_x('Please enter the Source of the Quote here.', 'backend', 'bangla' ),
				'clone' => false,
				'type'  => 'text',
				'std'   => '',
				'tab'   => 'quote'
			),


			// Post Format Video
			array(
				'name'      => esc_html_x('Video Source', 'backend', 'bangla' ),
				'id'        => $prefix . 'blog_videosrc',
				'type'      => 'select',
				'options'   => array(
					'videourl'  => esc_html_x('Video URL', 'backend', 'bangla' ),
					'embedcode' => esc_html_x('Embed Code', 'backend', 'bangla' )
				),
				'multiple'  => false,
				'std'       => array( 'videourl' ),
				'tab'       => 'video'
			),
			array(
				'name'  => esc_html_x('Video URL/Embed Code', 'backend', 'bangla' ),
				'id'    => $prefix . 'blog_video',
				'desc'  => wp_kses(_x('If you choose Video URL you can just insert the URL of the <a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">Supported Video Site</a>. Otherwise insert the full embed code.', 'backend', 'bangla' ), array('a'=>array())),
				'clone' => false,
				'type'  => 'textarea',
				'std'   => '',
				'tab'   => 'video'
			),
		)
	);

	
	add_filter( 'rwmb_outside_conditions', function( $conditions ) {
	    $conditions['bangla_page_layout'] = array(
	        'visible' => array('page_template', '!=', 'page-homepage.php')
	    );
	    $conditions['.rwmb-tab-titlebar'] = array(
	        'visible' => array('page_template', '!=', 'page-homepage.php')
	    );
	    $conditions['.rwmb-tab-blog'] = array(
	        'hidden' => array('page_template', '!=', 'page-blog.php')
	    );

	    $conditions['.rwmb-tab-testimonials'] = array(
	        'hidden' => array('page_template', '!=', 'page-testimonials.php')
	    );

	    $conditions['#pagesettings'] = array(
	        'visible' => array('page_template', '!=', 'page-blank.php')
	    );

	    $conditions['.rwmb-tab-gallery'] = array(
	        'hidden' => array('post_format', '!=', 'gallery')
	    );
	    $conditions['.rwmb-tab-video'] = array(
	        'hidden' => array('post_format', '!=', 'video')
	    );
	    $conditions['.rwmb-tab-audio'] = array(
	        'hidden' => array('post_format', '!=', 'audio')
	    );
	    $conditions['.rwmb-tab-quote'] = array(
	        'hidden' => array('post_format', '!=', 'quote')
	    );
	    $conditions['.rwmb-tab-link'] = array(
	        'hidden' => array('post_format', '!=', 'link')
	    );
	    return $conditions;
	});


	/* -------------------------------------------------------------------------

	if(is_plugin_active('broxme_wp-faq/broxme_wp-faq.php')) { 

		$meta_boxes[] = array(
			'id'      => 'faq_info',
			'title'   => esc_html_x( 'FAQ Additional', 'backend', 'bangla'),
			'pages'   => array( 'faq' ),
			'context' => 'normal',			
			'fields'  => array(
				array(
					'name'		=> esc_html_x('Show FAQ Icon', 'backend', 'bangla'),
					'id'		=> 'broxme_wp_show_faq_icon',
					'type'		=> 'radio',
					'options'	=> array(
						'yes'		=> esc_html_x('Yes', 'backend', 'bangla'),
						'no'		=> esc_html_x('No', 'backend', 'bangla')
					),
					'multiple' => false,
					'std'      => array( 'no' ),
				),
				array(
					'name'		=> esc_html_x( 'FAQ Icon', 'backend', 'bangla'),
					'id'		=> 'broxme_wp_faq_icon',
					'desc'		=> esc_html_x( 'Please type a fontawesome icon name for your FAQ. for example: fa fa-home', 'backend', 'bangla'),
					'clone'		=> false,
					'type'		=> 'text',
					'std'		=> '',
					'hidden' => array('broxme_wp_show_faq_icon', '=', 'no'),
				),			
			)
		);
	}
	--------------------------------------------------------------------------------------*/

	
	foreach ( $meta_boxes as $meta_box ) {
		new RW_Meta_Box( $meta_box );
	}
}


/* ----------------------------------------------------- */
// Background Styling
/* ----------------------------------------------------- */
add_action( 'admin_init', 'rw_register_meta_boxes_background' );
function rw_register_meta_boxes_background() {
	
	global $meta_boxes;

	if(get_theme_mod('bangla_global_layout', 'full') == 'boxed') {

		$prefix = 'bangla_';
		$meta_boxes = [];

		$meta_boxes[] = array(
			'id' => 'styling',
			'title' => 'Background Styling Options',
			'pages' => array( 'post', 'page', 'portfolio' ),
			'context' => 'side',
			'priority' => 'low',
		
			// List of meta fields
			'fields' => array(
				array(
					'name'             => 'Background Image URL',
					'id'               => $prefix . 'bgurl',
					'desc'             => '',
					'clone'            => false,
					'type'             => 'image_advanced',
					'max_file_uploads' => 1,
					'std'              => ''
				),
				array(
					'name'		=> 'Style',
					'id'		=> $prefix . "bgstyle",
					'type'		=> 'select',
					'options'	=> array(
						'stretch'		=> 'Stretch Image',
						'repeat'		=> 'Repeat',
						'no-repeat'		=> 'No-Repeat',
						'repeat-x'		=> 'Repeat-X',
						'repeat-y'		=> 'Repeat-Y'
					),
					'multiple'	=> false,
					'std'		=> array( 'stretch' )
				),
				array(
					'name'		=> 'Background Color',
					'id'		=> $prefix . "bgcolor",
					'type'		=> 'color'
				)
			)
		);
		
		foreach ( $meta_boxes as $meta_box ) {
			new RW_Meta_Box( $meta_box );
		}
	}
}