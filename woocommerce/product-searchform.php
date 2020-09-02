<?php
/**
 * The template for displaying product search form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/product-searchform.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  BroxMe Technology
 * @package WooCommerce/Templates
 * @version 3.5.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<form role="search" method="get" class="broxme_wp-search broxme_wp-search-default broxme_wp-width-1-1" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	<span broxme_wp-search-icon></span>
	<input type="search" id="woocommerce-product-search-field" class="search-field broxme_wp-search-input" placeholder="<?php echo esc_attr_x( 'Search Products&hellip;', 'placeholder', 'bangla' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'bangla' ); ?>" />
	<input type="hidden" name="post_type" value="product" />
</form>
