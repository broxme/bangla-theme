<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bangla
 */


$position = (get_post_meta( get_the_ID(), 'bangla_page_layout', true )) ? get_post_meta( get_the_ID(), 'bangla_page_layout', true ) : get_theme_mod( 'bangla_page_layout', 'sidebar-right' );

if($position == 'sidebar-left' || $position == 'sidebar-right') { ?>

	<aside<?php echo bangla_helper::sidebar(); ?>>
	    <?php get_sidebar(); ?>
	</aside> <!-- end aside -->

<?php }
