<?php

$id             = 'broxme_wp-titlebar';
$titlebar_show  = rwmb_meta('bangla_titlebar');
$class          = '';
$section_media  = [];
$section_image  = '';
$layout         = get_post_meta( get_the_ID(), 'bangla_titlebar_layout', true );
$metabox_layout = (!empty($layout) and $layout != 'default') ? true : false;
$position       = (get_post_meta( get_the_ID(), 'bangla_page_layout', true )) ? get_post_meta( get_the_ID(), 'bangla_page_layout', true ) : get_theme_mod( 'bangla_page_layout', 'sidebar-right' );

if ($metabox_layout) {
    $bg_style = get_post_meta( get_the_ID(), 'bangla_titlebar_bg_style', true );
    $bg_style = ( !empty($bg_style) ) ? $bg_style : get_theme_mod( 'bangla_titlebar_bg_style' );
    $width    = get_post_meta( get_the_ID(), 'bangla_titlebar_width', true );
    $padding  = get_post_meta( get_the_ID(), 'bangla_titlebar_padding', true );
    $text     = get_post_meta( get_the_ID(), 'bangla_titlebar_txt_style', true );
} else {
    $bg_style = get_theme_mod( 'bangla_titlebar_bg_style', 'muted' );
    $width    = get_theme_mod( 'bangla_titlebar_width', 'default' );
    $padding  = get_theme_mod( 'bangla_titlebar_padding', 'medium' );
    $text     = get_theme_mod( 'bangla_titlebar_txt_style' );
}

if (is_array($class)) {
	$class = implode(' ', array_filter($class));
}     

if ($metabox_layout) {
    $section_images = rwmb_meta( 'bangla_titlebar_bg_img', "type=image_advanced&size=standard" );
    foreach ( $section_images as $image ) { 
        $section_image = esc_url($image["url"]);
    }
} else {
    $section_image         = get_theme_mod( 'bangla_titlebar_bg_img' );
}

// Image
if ($section_image &&  $bg_style == 'media') {
    $section_media['style'][] = "background-image: url('{$section_image}');";
    $section_media['class'][] = 'broxme_wp-background-norepeat';
}


$class   = ['broxme_wp-titlebar', 'broxme_wp-section', $class];

$class[] = ($bg_style) ? 'broxme_wp-section-'.$bg_style : '';
$class[] = ($text) ? 'broxme_wp-'.$text : '';
if ($padding != 'none') {
    $class[]       = ($padding) ? 'broxme_wp-section-'.$padding : '';
} elseif ($padding == 'none') {
    $class[]       = ($padding) ? 'broxme_wp-padding-remove-vertical' : '';
}



if ( $titlebar_show !== 'hide') : ?>

	<?php 
		global $post;
		$blog_title        = get_theme_mod('bangla_blog_title', esc_html__('Blog', 'bangla'));
		$woocommerce_title = get_theme_mod('bangla_woocommerce_title', esc_html__('Shop', 'bangla'));
		$titlebar_global   = get_theme_mod('bangla_titlebar_layout', 'left');
		$titlebar_metabox  = get_post_meta( get_the_ID(), 'bangla_titlebar_layout', true);
		$title             = get_the_title();

	?>

	<?php if( is_object($post) && !is_archive() &&!is_search() && !is_404() && !is_author() && !is_home() && !is_page() ) { ?>

		<?php if($titlebar_metabox != 'default' && !empty($titlebar_metabox)) { ?>

			<?php  if ($titlebar_metabox == 'left' or $titlebar_metabox == 'center' or $titlebar_metabox == 'right') { ?>
				<div<?php echo bangla_helper::attrs(['id' => $id, 'class' => $class], $section_media); ?>>
					<div<?php echo bangla_helper::container(); ?>>
						<div<?php echo bangla_helper::grid(); ?>>
							<div id="title" class="broxme_wp-width-expand<?php echo ($titlebar_metabox == 'center')?' broxme_wp-text-center':''; ?>">
								<h1 class="broxme_wp-margin-small-bottom"><?php echo esc_html($title); ?></h1>
								<?php echo bangla_breadcrumbs($titlebar_global); ?>
							</div>
							<?php if ($titlebar_metabox != 'center') : ?>
							<div class="broxme_wp-margin-auto-left broxme_wp-position-relative broxme_wp-width-small broxme_wp-visible@s">
								<div class="broxme_wp-position-center-right">
									<a class="broxme_wp-button-text broxme_wp-link-reset" onclick="history.back()"><span class="broxme_wp-margin-small-right" broxme_wp-icon="icon: arrow-left"></span> <?php esc_html_e('Back', 'bangla'); ?></a>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php } elseif (rwmb_meta('bangla_titlebar') == 'notitle') { ?>
				<div id="notitlebar" class="titlebar-no"></div>
			<?php } ?>

		<?php } else { ?>
				<?php
					// Define the Title for different Pages
					if ( is_home() ) { $title = $blog_title; }
					elseif( is_search() ) { 	
						$allsearch = new WP_Query("s=$s&showposts=-1"); 
						$count = $allsearch->post_count; 
						wp_reset_postdata();
						$title = $count . ' '; 
						$title .= esc_html__('Search results for:', 'bangla');
						$title .= ' ' . get_search_query();
					}
					elseif( class_exists('Woocommerce') && is_woocommerce() ) { $title = $woocommerce_title; }
					elseif( is_archive() ) { 
						if (is_category()) { 	$title = single_cat_title('',false); }
						elseif( is_tag() ) { 	$title = esc_html__('Posts Tagged:', 'bangla') . ' ' . single_tag_title('',false); }
						elseif (is_day()) { 	$title = esc_html__('Archive for', 'bangla') . ' ' . get_the_time('F jS, Y'); }
						elseif (is_month()) { 	$title = esc_html__('Archive for', 'bangla') . ' ' . get_the_time('F Y'); }
						elseif (is_year()) { 	$title = esc_html__('Archive for', 'bangla') . ' ' . get_the_time('Y'); }
						elseif (is_author()) { 	$title = esc_html__('Author Archive for', 'bangla') . ' ' . get_the_author(); }
						elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { $title = esc_html__('Blog Archives', 'bangla'); }
						else{
							$title = single_term_title( "", false );
							if ( $title == '' ) { // Fix for templates that are archives
								$post_id = $post->ID;
								$title = get_the_title($post_id);
							} 
						}
					}
					elseif( is_404() ) { $title = esc_html__('Oops, this Page could not be found.', 'bangla'); }
					elseif( get_post_type() == 'post' ) { $title = $blog_title; }
					else { $title = get_the_title(); }
				?>

				<div<?php echo bangla_helper::attrs(['id' => $id, 'class' => $class], $section_media); ?>>
					<div<?php echo bangla_helper::container(); ?>>
						<div<?php echo bangla_helper::grid(); ?>>
							<div id="title" class="<?php echo ($titlebar_metabox == 'center')?'broxme_wp-text-center':''; ?>">
								<h1 class="broxme_wp-margin-small-bottom"><?php echo esc_html($title); ?></h1>
								<?php echo bangla_breadcrumbs($titlebar_global); ?>
							</div>
							<?php if ($titlebar_metabox != 'center') :?>
							<div class="broxme_wp-margin-auto-left broxme_wp-position-relative broxme_wp-width-small broxme_wp-visible@s">
								<div class="broxme_wp-position-center-right">
									<a class="broxme_wp-button-text broxme_wp-link-reset" onclick="history.back()"><span class="broxme_wp-margin-small-right" broxme_wp-icon="icon: arrow-left"></span> <?php esc_html_e('Back', 'bangla'); ?></a>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

		<?php } // End Else ?>

	<?php } else { // If no post page ?>
		
		<?php if($titlebar_metabox != 'default' && !empty($titlebar_metabox)) { ?>

			<?php  if ($titlebar_metabox == 'left' or $titlebar_metabox == 'center' or $titlebar_metabox == 'right') { ?>
				<div<?php echo bangla_helper::attrs(['id' => $id, 'class' => $class], $section_media); ?>>
					<div<?php echo bangla_helper::container(); ?>>
						<div<?php echo bangla_helper::grid(); ?>>
							<div id="title" class="broxme_wp-width-expand<?php echo ($titlebar_metabox == 'center')?' broxme_wp-text-center':''; ?>">
								<h1 class="broxme_wp-margin-small-bottom"><?php echo esc_html($title); ?></h1>
								<?php echo bangla_breadcrumbs($titlebar_global); ?>
							</div>
							<?php if ($titlebar_metabox != 'center') : ?>
							<div class="broxme_wp-margin-auto-left broxme_wp-position-relative broxme_wp-width-small broxme_wp-visible@s">
								<div class="broxme_wp-position-center-right">
									<a class="broxme_wp-button-text broxme_wp-link-reset" onclick="history.back()"><span class="broxme_wp-margin-small-right" broxme_wp-icon="icon: arrow-left"></span> <?php esc_html_e('Back', 'bangla'); ?></a>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php } elseif (rwmb_meta('bangla_titlebar') == 'notitle') { ?>
				<div id="notitlebar" class="titlebar-no"></div>
			<?php } ?>

		<?php } else { ?>

			<?php
				// Define the Title for different Pages
				if ( is_home() ) { $title = $blog_title; }
				elseif( is_search() ) { 	
					$allsearch = new WP_Query("s=$s&showposts=-1"); 
					$count = $allsearch->post_count; 
					wp_reset_postdata();
					$title = $count . ' '; 
					$title .= esc_html__('Search results for:', 'bangla');
					$title .= ' ' . get_search_query();
				}
				elseif( class_exists('Woocommerce') && is_woocommerce() ) { $title = $woocommerce_title; }
				elseif( is_archive() ) { 
					if (is_category()) { 	$title = single_cat_title('',false); }
					elseif( is_tag() ) { 	$title = esc_html__('Posts Tagged:', 'bangla') . ' ' . single_tag_title('',false); }
					elseif (is_day()) { 	$title = esc_html__('Archive for', 'bangla') . ' ' . get_the_time('F jS, Y'); }
					elseif (is_month()) { 	$title = esc_html__('Archive for', 'bangla') . ' ' . get_the_time('F Y'); }
					elseif (is_year()) { 	$title = esc_html__('Archive for', 'bangla') . ' ' . get_the_time('Y'); }
					elseif (is_author()) { 	$title = esc_html__('Author Archive for', 'bangla') . ' ' . get_the_author(); }
					elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { $title = esc_html__('Blog Archives', 'bangla'); }
					else{
						$title = single_term_title( "", false );
						if ( $title == '' ) { // Fix for templates that are archives
							$post_id = $post->ID;
							$title = get_the_title($post_id);
						} 
					}
				}
				elseif( is_404() ) { $title = esc_html__('Oops, this Page could not be found.', 'bangla'); }
				elseif( get_post_type() == 'post' ) { $title = $blog_title; }
				else { $title = get_the_title(); }
			?>
			
			<?php if($titlebar_global == 'left' or $titlebar_global == 'center' or $titlebar_global == 'right') { ?>
				<div<?php echo bangla_helper::attrs(['id' => $id, 'class' => $class], $section_media); ?>>
					<div<?php echo bangla_helper::container(); ?>>
						<div<?php echo bangla_helper::grid(); ?>>
							<div id="title" class="broxme_wp-width-expand<?php echo ($titlebar_global == 'center')?' broxme_wp-text-center':''; ?>">
								<h1 class="broxme_wp-margin-small-bottom"><?php echo esc_html($title); ?></h1>
								<?php echo bangla_breadcrumbs($titlebar_global); ?>
							</div>
							<?php if ($titlebar_global != 'center') : ?>
							<div class="broxme_wp-margin-auto-left broxme_wp-position-relative broxme_wp-width-small broxme_wp-visible@s">
								<div class="broxme_wp-position-center-right">
									<a class="broxme_wp-button-text broxme_wp-link-reset" onclick="history.back()"><span class="broxme_wp-margin-small-right" broxme_wp-icon="icon: arrow-left"></span> <?php esc_html_e('Back', 'bangla'); ?></a>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php } elseif($titlebar_global == 'notitle') { ?>
				<div id="notitlebar" class="titlebar-no"></div>
			<?php } ?>	
		<?php } ?>

	<?php } // End Else ?>

<?php endif;