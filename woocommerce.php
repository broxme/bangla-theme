<?php get_header(); ?>


<?php 
	$product_columns = get_theme_mod('bangla_woocommerce_columns', 3);
	$wooclass = 'product-columns-'.$product_columns;
	// Get WooCommerce Layout from Theme Options
	$position = get_theme_mod('bangla_woocommerce_sidebar', 'sidebar-left');

?>

<div<?php echo bangla_helper::section('main'); ?>>
	<div<?php echo bangla_helper::container(); ?>>
		<div<?php echo bangla_helper::grid(); ?>>


		<?php
		// Single Products Page
		if(is_product()){
			?>

			<div class="broxme_wp-width-expand">
				<main class="tm-content">

					<?php woocommerce_content(); ?>

				</main> <!-- end main -->
			</div> <!-- end width -->	

			<?php

			// Main Shop Layout
			} else { ?>
				<div class="broxme_wp-width-expand">
					<main class="tm-content <?php echo esc_attr($wooclass); ?>">
						<?php woocommerce_content(); ?>
					</main> <!-- end main -->
				</div> <!-- end width -->
			<?php } // end-if main shop layout ?>

			
			<?php if(is_active_sidebar('shop-widgets') and ($position == 'sidebar-left' or $position == 'sidebar-right')) : ?>
				<aside<?php echo bangla_helper::sidebar($position); ?>>
				   <?php if(is_woocommerce()) {
						/* WooCommerce Sidebar */
						if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('shop-widgets') );
					} ?>
				</aside> <!-- end aside -->
			<?php endif; ?>
	
		</div> <!-- end grid -->
	</div> <!-- end container -->
</div> <!-- end tm main -->
	
<?php get_footer(); ?>