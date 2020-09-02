<?php 
$classes            = ['broxme_wp-container', 'broxme_wp-flex broxme_wp-flex-middle'];
$mb_toolbar         = (get_post_meta( get_the_ID(), 'bangla_toolbar', true ) != null) ? get_post_meta( get_the_ID(), 'bangla_toolbar', true ) : false;
$broxme_wp_toolbar         = (get_theme_mod( 'bangla_toolbar', 0)) ? 1 : 0;
$toolbar_left       = get_theme_mod( 'bangla_toolbar_left', 'tagline' );
$toolbar_right      = get_theme_mod( 'bangla_toolbar_right', 'social' );
$toolbar_cart       = get_theme_mod( 'bangla_woocommerce_cart' );
$classes[]          = (get_theme_mod( 'bangla_toolbar_fullwidth' )) ? 'broxme_wp-container-expand' : '';
$toolbar_left_hide  = (get_theme_mod( 'bangla_toolbar_left_hide_mobile' )) ? ' broxme_wp-visible@s' : '';
$toolbar_right_hide = (get_theme_mod( 'bangla_toolbar_right_hide_mobile' )) ? ' broxme_wp-visible@s' : '';
$toolbar_full_hide  = ( $toolbar_left_hide and $toolbar_right_hide ) ? ' broxme_wp-visible@s' : '';

?>

<?php if ($broxme_wp_toolbar and $mb_toolbar != true) : ?>
	<div class="broxme_wp-toolbar<?php echo esc_attr($toolbar_full_hide); ?>">
		<div<?php echo bangla_helper::attrs(['class' => $classes]) ?>>

			<?php if (!empty($toolbar_left)) : ?>
			<div class="broxme_wp-toolbar-l<?php echo esc_attr($toolbar_left_hide); ?>"><?php get_template_part( 'template-parts/toolbars/'.$toolbar_left ); ?></div>
			<?php endif; ?>

			<?php if (!empty($toolbar_right) or $toolbar_cart == 'toolbar') : ?>
			<div class="broxme_wp-toolbar-r broxme_wp-margin-auto-left broxme_wp-flex<?php echo esc_attr($toolbar_right_hide); ?>">
				<?php if ($toolbar_cart == 'toolbar') : ?>
					<div class="broxme_wp-display-inline-block">
						<?php get_template_part( 'template-parts/toolbars/'.$toolbar_right ); ?>
					</div>
					<div class="broxme_wp-display-inline-block broxme_wp-margin-small-left">
						<?php get_template_part('template-parts/woocommerce-cart'); ?>
					</div>
				<?php else: ?>
					<?php get_template_part( 'template-parts/toolbars/'.$toolbar_right ); ?>
				<?php endif; ?>
			</div>
			<?php endif; ?>

		</div>
	</div>
<?php endif; ?>