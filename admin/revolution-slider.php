<?php

/**
 * Remove Rev Slider Metabox
 */
if (is_admin()) {

	function rooten_remove_revolution_slider_meta_boxes() {
		remove_meta_box( 'mymetabox_revslider_0', 'page', 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'post', 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'give_forms', 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'tribe_events', 'normal' );
		remove_meta_box( 'mymetabox_revslider_0', 'product', 'normal' );
	}

	add_action( 'do_meta_boxes', 'rooten_remove_revolution_slider_meta_boxes' );
}

// Remove meta tag from header
function rooten_remove_revslider_meta_tag() {
    return '';  
} 
add_filter( 'revslider_meta_generator', 'rooten_remove_revslider_meta_tag' );