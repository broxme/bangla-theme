<?php if (is_active_sidebar('drawer')) : ?>
<?php
	$class             = ['broxme_wp-drawer', 'broxme_wp-section'];
	$container_class   = [];
	$grid_class        = ['broxme_wp-grid'];
	$background_style  = get_theme_mod( 'bangla_drawer_bg_style', 'secondary' );
	$width             = get_theme_mod( 'bangla_drawer_width', 'default');
	$padding           = get_theme_mod( 'bangla_drawer_padding', 'small' );
	$text              = get_theme_mod( 'bangla_drawer_txt_style' );
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
	$wrapper_bg = ($background_style) ? ' broxme_wp-background-'.$background_style : '';
?>

<div class="drawer-wrapper<?php echo esc_url($wrapper_bg); ?>">
	<div id="tm-drawer" <?php echo bangla_helper::attrs(['class' => $class]) ?> hidden>
		<div <?php echo bangla_helper::attrs(['class' => $container_class]) ?>>
			<div <?php echo bangla_helper::attrs(['class' => $grid_class]) ?> broxme_wp-grid>
				<?php dynamic_sidebar('drawer'); ?>
			</div>
		</div>
	</div>
	<a href="javascript:void(0);" class="drawer-toggle broxme_wp-position-top-right broxme_wp-margin-small-right" broxme_wp-toggle="target: #tm-drawer; animation: broxme_wp-animation-slide-top; queued: true"><span broxme_wp-icon="icon: chevron-down"></span></a>
</div>
<?php endif; ?>