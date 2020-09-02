<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bangla
 */

if(get_theme_mod('bangla_footer_widgets', 1) && get_post_meta( get_the_ID(), 'bangla_footer_widgets', true ) != 'hide') {

	$id                  = 'broxme_wp-bottom';
	$class               = ['broxme_wp-bottom', 'broxme_wp-section'];
	$section             = '';
	$section_media       = [];
	$section_image       = '';
	$container_class     = [];
	$grid_class          = ['broxme_wp-grid', 'broxme_wp-margin'];
	$bottom_width        = get_theme_mod( 'bangla_bottom_width', 'default');
	$breakpoint          = get_theme_mod( 'bangla_bottom_breakpoint', 'm' );
	$vertical_align      = get_theme_mod( 'bangla_bottom_vertical_align' );
	$match_height        = get_theme_mod( 'bangla_bottom_match_height' );
	$column_divider      = get_theme_mod( 'bangla_bottom_column_divider' );
	$gutter              = get_theme_mod( 'bangla_bottom_gutter' );
	$columns             = get_theme_mod( 'bangla_footer_columns', 4);
	$first_column_expand = get_theme_mod( 'bangla_footer_fce');
	
	
	$layout         = get_post_meta( get_the_ID(), 'bangla_bottom_layout', true );
	$metabox_layout = (!empty($layout) and $layout != 'default') ? true : false;
	$position       = (get_post_meta( get_the_ID(), 'bangla_page_layout', true )) ? get_post_meta( get_the_ID(), 'bangla_page_layout', true ) : get_theme_mod( 'bangla_page_layout', 'sidebar-right' );

	if ($metabox_layout) {
	    $bg_style = get_post_meta( get_the_ID(), 'bangla_bottom_bg_style', true );
	    $bg_style = ( !empty($bg_style) ) ? $bg_style : get_theme_mod( 'bangla_bottom_bg_style' );
	    $padding  = get_post_meta( get_the_ID(), 'bangla_bottom_padding', true );
	    $text     = get_post_meta( get_the_ID(), 'bangla_bottom_txt_style', true );
	} else {
	    $bg_style = get_theme_mod( 'bangla_bottom_bg_style', 'secondary' );
	    $padding  = get_theme_mod( 'bangla_bottom_padding', 'medium' );
	    $text     = get_theme_mod( 'bangla_bottom_txt_style' );
	}

     
	    
    if ($metabox_layout) {
        $section_images = rwmb_meta( 'bangla_bottom_bg_img', "type=image_advanced&size=standard" );
        foreach ( $section_images as $image ) { 
            $section_image = esc_url($image["url"]);
        }
        $section_bg_img_pos    = get_post_meta( get_the_ID(), 'bangla_bottom_bg_img_position', true );
        $section_bg_img_attach = get_post_meta( get_the_ID(), 'bangla_bottom_bg_img_fixed', true );
        $section_bg_img_vis    = get_post_meta( get_the_ID(), 'bangla_bottom_bg_img_visibility', true );
    } else {
        $section_image         = get_theme_mod( 'bangla_bottom_bg_img' );
        $section_bg_img_pos    = get_theme_mod( 'bangla_bottom_bg_img_position' );
        $section_bg_img_attach = get_theme_mod( 'bangla_bottom_bg_img_fixed' );
        $section_bg_img_vis    = get_theme_mod( 'bangla_bottom_bg_img_visibility' );
    }

    // Image
    if ($section_image &&  $bg_style == 'media') {
        $section_media['style'][] = "background-image: url('{$section_image}');";
        // Settings
        $section_media['class'][] = 'broxme_wp-background-norepeat';
        $section_media['class'][] = $section_bg_img_pos ? "broxme_wp-background-{$section_bg_img_pos}" : '';
        $section_media['class'][] = $section_bg_img_attach ? "broxme_wp-background-fixed" : '';
        $section_media['class'][] = $section_bg_img_vis ? "broxme_wp-background-image@{$section_bg_img_vis}" : '';
    }

	$class[] = ($position == 'full' and $name == 'broxme_wp-main') ? 'broxme_wp-padding-remove-vertical' : ''; // section spacific override

	$class[] = ($bg_style) ? 'broxme_wp-section-'.$bg_style : '';

	$class[] = ($text) ? 'broxme_wp-'.$text : '';
	if ($padding != 'none') {
	    $class[]       = ($padding) ? 'broxme_wp-section-'.$padding : '';
	} elseif ($padding == 'none') {
	    $class[]       = ($padding) ? 'broxme_wp-padding-remove-vertical' : '';
	}



	$container_class[] = ($bottom_width) ? 'broxme_wp-container broxme_wp-container-'.$bottom_width : '';
	
	$grid_class[]      = ($gutter) ? 'broxme_wp-grid-'.$gutter : '';
	$grid_class[]      = ($column_divider && $gutter != 'collapse') ? 'broxme_wp-grid-divider' : '';
	$grid_class[]      = ($breakpoint) ? 'broxme_wp-child-width-expand@'.$breakpoint : '';
	$grid_class[]      = ($vertical_align) ? 'broxme_wp-flex-middle' : '';
	$match_height = (!$vertical_align && $match_height) ? ' broxme_wp-height-match="target: > div > div > .broxme_wp-card"' : '';
	
	$expand_columns    = intval($columns)+1;
	$column_class      = ($first_column_expand) ? ' broxme_wp-width-1-'.$expand_columns.'@l' : '';

	if (is_active_sidebar('footer-widgets') || is_active_sidebar('footer-widgets-2') || is_active_sidebar('footer-widgets-3') || is_active_sidebar('footer-widgets-4')) : ?>
		<div<?php echo bangla_helper::attrs(['id' => $id, 'class' => $class], $section_media); ?>>
			<div<?php echo bangla_helper::attrs(['class' => $container_class]) ?>>
				
				<?php if (is_active_sidebar('bottom-widgets')) : ?>
					<div class="bottom-widgets broxme_wp-child-width-expand@s" broxme_wp-grid><?php if (dynamic_sidebar('bottom-widgets')); ?></div>
					<hr class="broxme_wp-margin-medium">
				<?php endif; ?>
				
				<div<?php echo bangla_helper::attrs(['class' => $grid_class]) ?> broxme_wp-grid<?php echo esc_attr($match_height); ?>>

					<?php if (is_active_sidebar('footer-widgets') && $columns) : ?>
						<div class="bottom-columns broxme_wp-width-1-3@m"><?php if (dynamic_sidebar('Footer Widgets 1')); ?></div>
					<?php endif; ?>
					<?php if (is_active_sidebar('footer-widgets-2') && $columns > 1) : ?>
						<div class="bottom-columns<?php echo esc_attr($column_class); ?>"><?php if (dynamic_sidebar('Footer Widgets 2')); ?></div>
					<?php endif; ?>
					<?php if (is_active_sidebar('footer-widgets-3') && $columns > 2) : ?>
						<div class="bottom-columns<?php echo esc_attr($column_class); ?>"><?php if (dynamic_sidebar('Footer Widgets 3')); ?></div>
					<?php endif; ?>
					<?php if (is_active_sidebar('footer-widgets-4') && $columns > 3) : ?>
						<div class="bottom-columns<?php echo esc_attr($column_class); ?>"><?php if (dynamic_sidebar('Footer Widgets 4')); ?></div>	
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif;
}