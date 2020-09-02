<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bangla
 */

if(get_post_meta( get_the_ID(), 'bangla_copyright', true ) != 'hide') {

	$class             = ['broxme_wp-copyright', 'broxme_wp-section'];
	$container_class   = [];
	$grid_class        = ['broxme_wp-grid', 'broxme_wp-flex', 'broxme_wp-flex-middle'];
	$background_style  = get_theme_mod( 'bangla_copyright_bg_style', 'secondary' );
	$width             = get_theme_mod( 'bangla_copyright_width', 'default');
	$padding           = get_theme_mod( 'bangla_copyright_padding', 'small' );
	$text              = get_theme_mod( 'bangla_copyright_txt_style' );
	$breakpoint        = get_theme_mod( 'bangla_bottom_breakpoint', 'm' );
	
	$class[]           = ($background_style) ? 'broxme_wp-section-'.$background_style : '';
	$class[]           = ($text) ? 'broxme_wp-'.$text : '';
	if ($padding != 'none') {
		$class[]       = ($padding) ? 'broxme_wp-section-'.$padding : '';
	} elseif ($padding == 'none') {
		$class[]       = ($padding) ? 'broxme_wp-padding-remove-vertical' : '';
	}
	
	$container_class[] = ($width) ? 'broxme_wp-container broxme_wp-container-'.$width : '';
	
	$grid_class[]      = ($breakpoint) ? 'broxme_wp-child-width-expand@'.$breakpoint : '';

	?>

	<div id="broxme_wpCopyright"<?php echo bangla_helper::attrs(['class' => $class]) ?>>
		<div<?php echo bangla_helper::attrs(['class' => $container_class]) ?>>
			<div<?php echo bangla_helper::attrs(['class' => $grid_class]) ?> broxme_wp-grid>
				<div class="broxme_wp-width-expand@m">	
					<?php									 
					if (has_nav_menu('copyright')) { echo wp_nav_menu( array( 'theme_location' => 'copyright', 'container_class' => 'broxme_wp-copyright-menu broxme_wp-display-inline-block', 'menu_class' => 'broxme_wp-subnav broxme_wp-subnav-line broxme_wp-subnav-divider broxme_wp-margin-small-bottom', 'depth' => 1 ) ); }
					
					if(get_theme_mod('bangla_copyright_text_custom_show')) : ?>
						<div class="copyright-txt"><?php echo wp_kses_post(get_theme_mod('bangla_copyright_text_custom')); ?></div>
					<?php else : ?>								
						<div class="copyright-txt">&copy; <?php esc_html_e('Copyright', 'bangla') ?> <?php echo esc_html(date("Y ")); ?> <?php esc_html_e('All Rights Reserved by', 'bangla') ?> <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo( 'name' );?>"> <?php echo esc_html(bloginfo('name')); ?> </a></div>
					<?php endif; ?>
				</div>
				<div class="broxme_wp-width-auto@m">
					<?php get_template_part( 'template-parts/copyright-social'); ?>
				</div>
			</div>
		</div>
	</div>

	<?php 
}