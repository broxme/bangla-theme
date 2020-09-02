<?php if (class_exists('Woocommerce')) { 
	
	$layout_c        = get_theme_mod('bangla_header_layout', 'horizontal-left');
	$layout_m        = get_post_meta( get_the_ID(), 'bangla_header_layout', true );
	$layout          = (!empty($layout_m) and $layout_m != 'default') ? $layout_m : $layout_c;

	$cart = get_theme_mod('bangla_woocommerce_cart');

	if($cart !== 'no') { 
	global $woocommerce; 
	$bangla_wcrtl = (is_rtl()) ? 'left' : 'right';
	$offset = ( $cart == 'toolbar') ? 15 : 32;
	?>
	
	<div class="broxme_wp-cart-popup">
		<a href="<?php echo esc_url($woocommerce->cart->wc_get_cart_url()); ?>" id="shopping-btn" class="broxme_wp-shopping-cart" title="<?php esc_html_e('View Cart', 'bangla'); ?>">
			<span broxme_wp-icon="icon: cart"></span>
			<?php
				$product_bumber = $woocommerce->cart->cart_contents_count; 
				if ($cart == 'header') {
					if ( sizeof( $woocommerce->cart->cart_contents ) != 0 ) {
						echo '<span class="pcount">'.esc_html($product_bumber).'</span>';
					} 
				}
				if ($cart == 'toolbar') {
					echo '<div class="broxme_wp-hidden-small broxme_wp-display-inline">';
					if ( sizeof( $woocommerce->cart->cart_contents ) == 0 ) {
						esc_html_e('Cart is Empty', 'bangla');
					} else {
						echo sprintf( _n( '%s Item in cart', '%s Items in cart', $product_bumber, 'bangla' ), $product_bumber );
					}
					echo '</div>';
				} 
			?>
		</a>
		
		<?php if (!in_array($layout, ['side-left', 'side-right'])) : ?>
			<?php if ( sizeof( $woocommerce->cart->cart_contents ) != 0 and !is_checkout() and !is_cart()) : ?>
				<div class="cart-dropdown" broxme_wp-drop="mode: hover; offset: <?php echo esc_attr($offset); ?>">
					<div class="broxme_wp-card broxme_wp-card-body broxme_wp-card-default">
						<?php if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) { the_widget( 'WC_Widget_Cart', '' ); } ?>
					</div>
				</div>
			<?php endif; ?>
		<?php endif; ?>

	</div>
	<?php }
} ?>