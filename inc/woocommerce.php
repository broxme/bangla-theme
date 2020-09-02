<?php

// Add WooCommerce Theme Support
add_theme_support('woocommerce');
add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );

$limit        = get_theme_mod('bangla_woocommerce_limit', 9);
$sort         = get_theme_mod('bangla_woocommerce_sort', 1);
$result_count = get_theme_mod('bangla_woocommerce_result_count', 1);
$upsells      = get_theme_mod('bangla_woocommerce_upsells', 0);
$related      = get_theme_mod('bangla_woocommerce_related', 1);
$cart_button  = get_theme_mod('bangla_woocommerce_cart_button', 1);


// disable woocommerce general style
add_filter('woocommerce_enqueue_styles', function ($styles) {
    unset($styles['woocommerce-general']);
    return $styles;
});


// Change number or products per row to 3
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		$product_columns = get_theme_mod('bangla_woocommerce_columns', 3);
		return $product_columns; // 3 products per row
	}
}



// Increase Number of Related Products to 4
if (!function_exists('woocommerce_related_output')) {
	function woocommerce_related_output() {
		global $product, $orderby, $related;
		$args = array(
			'posts_per_page'	=> '3',
			'columns'			=> '3',
		);
		return $args;
	}
}
add_filter( 'woocommerce_output_related_products_args', 'woocommerce_related_output' );

// Change products per page
//add_filter( 'loop_shop_per_page', create_function( '$cols', 'return ' . $limit . ';' ), 20 );


// Toggle Sort by Function
if($sort == 0) {
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
}

// Toggle Result Count
if($result_count == 0) {
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
}

// Toggle Upsell Products
if($upsells == 0) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
}

// Toggle Related Products
if($related == 0) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
}

// Toggle Add to Cart Button
if($cart_button == 0) {
	add_action('init','woocommerce_remove_loop_button');
}

// Remove Cart Cross Sells
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

//change tab position to be inside summary
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
add_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 1);

add_filter( 'woocommerce_show_page_title', '__return_false' );

// add_action( 'init', 'bangla_remove_woo_breadcrumbs' );
// function bangla_remove_woo_breadcrumbs() {
//     remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
// }

// Ajaxfiy WooCommerce Cart
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	
	ob_start(); ?>
	
	<a href="<?php echo esc_url($woocommerce->cart->wc_get_cart_url()); ?>" id="shopping-btn" class="broxme_wp-shopping-cart" title="<?php esc_html_e('View Cart', 'bangla'); ?>">
		<span broxme_wp-icon="icon: cart"></span>
			<?php
				$cart           = get_theme_mod('bangla_woocommerce_cart');
				$product_bumber = $woocommerce->cart->cart_contents_count; 
				if ($cart == 'header') {
					if ( sizeof( $woocommerce->cart->cart_contents ) != 0 ) {
						echo '<span class="pcount">'.esc_html($product_bumber).'</span>';
					}	
				}
				if ($cart == 'toolbar') {
					echo '<div class="broxme_wp-visible@s broxme_wp-display-inline">';
					if ( sizeof( $woocommerce->cart->cart_contents ) == 0 ) {
						esc_html_e('Cart is Empty', 'bangla');
					} else {
						echo sprintf( _n( '%s Item in cart', '%s Items in cart', $product_bumber, 'bangla' ), $product_bumber );
					}
					echo '</div>';
				} 
			?>
	</a>

	<?php
	$fragments['a.broxme_wp-shopping-cart'] = ob_get_clean();
	return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');

// Remove Add to Cart Button
function woocommerce_remove_loop_button(){
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
}


// Wrapping image with div
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);


if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {
    function woocommerce_template_loop_product_thumbnail() {
        echo woocommerce_get_product_thumbnail();
    } 
}
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {   
    function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
        global $post, $woocommerce;
        $output = '<div class="imagewrapper">';

        if ( has_post_thumbnail() ) {               
            $output .= get_the_post_thumbnail( $post->ID, $size );              
        }                       
        $output .= '</div>';
        return $output;
    }
}


// Add Custom Pagination
remove_action('woocommerce_pagination', 'woocommerce_pagination', 10);
function woocommerce_pagination() {
	get_template_part( 'template-parts/pagination' );
}
add_action( 'woocommerce_pagination', 'woocommerce_pagination', 10);



// Add Second Image on Hover by http://jameskoster.co.uk
// License: GNU General Public License v3.0
if ( ! class_exists( 'WC_pif' ) ) {
	class WC_pif {
		public function __construct() {
			//add_action( 'wp_enqueue_scripts', array( $this, 'pif_scripts' ) );														// Enqueue the styles
			add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'woocommerce_template_loop_second_product_thumbnail' ), 11 );
			add_filter( 'post_class', array( $this, 'product_has_gallery' ) );
		}

		// Add pif-has-gallery class to products that have a gallery
		function product_has_gallery( $classes ) {
			global $product;
			$post_type = get_post_type( get_the_ID() );
			if ( ! is_admin() ) {
				if ( $post_type == 'product' ) {
					$attachment_ids = $product->get_gallery_image_ids();
					if ( $attachment_ids ) {
						$classes[] = 'pif-has-gallery';
					}
				}
			}
			return $classes;
		}

		// Display the second thumbnails
		function woocommerce_template_loop_second_product_thumbnail() {
			global $product, $woocommerce;
			$attachment_ids = $product->get_gallery_image_ids();
			if ( $attachment_ids ) {
				$secondary_image_id = @$attachment_ids['0'];
				echo wp_get_attachment_image( $secondary_image_id, 'shop_catalog', '', $attr = array( 'class' => 'secondary-image attachment-shop-catalog' ) );
			}
		}

	}

	$WC_pif = new WC_pif();
}


/*
 * Predefine woocommerce image size
 */
add_action( 'init', 'bangla_woocommerce_image_dimensions', 1 );
function bangla_woocommerce_image_dimensions() {
  	$catalog = array(
		'width' 	=> '246',	// px
		'height'	=> '316',	// px
		'crop'		=> 1 		// true
	);

	$single = array(
		'width' 	=> '485',	// px
		'height'	=> '622',	// px
		'crop'		=> 1 		// true
	);

	$thumbnail = array(
		'width' 	=> '110',	// px
		'height'	=> '110',	// px
		'crop'		=> 0 		// false
	);

	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
}
