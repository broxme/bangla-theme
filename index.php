<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bangla
 */

get_header();

// Layout
$position = (get_post_meta( get_the_ID(), 'bangla_page_layout', true )) ? get_post_meta( get_the_ID(), 'bangla_page_layout', true ) : get_theme_mod( 'bangla_page_layout', 'sidebar-right' );

?>

<div<?php echo bangla_helper::section('main'); ?>>
	<div<?php echo bangla_helper::container(); ?>>
		<div<?php echo bangla_helper::grid(); ?>>
			
			<div class="broxme_wp-width-expand">
				<main class="broxme_wp-content">
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

						<?php get_template_part( 'template-parts/post-format/entry', get_post_format() ); ?>

					<?php endwhile; endif; ?>
					
					<?php get_template_part( 'template-parts/pagination' ); ?>
				</main> <!-- end main -->
			</div> <!-- end content -->
			

			<?php if( is_active_sidebar( 'blog-widgets' ) and ($position == 'sidebar-left' or $position == 'sidebar-right')) : ?>
				<aside<?php echo bangla_helper::sidebar($position); ?>>
				    <?php get_sidebar(); ?>
				</aside> <!-- end aside -->
			<?php endif; ?>
			
		</div> <!-- end grid -->
	</div> <!-- end container -->
</div> <!-- end main -->
	
<?php get_footer(); ?>