<?php
/* Template Name: Blog */

	get_header(); 

	// Layout
	$position = (get_post_meta( get_the_ID(), 'bangla_page_layout', true )) ? get_post_meta( get_the_ID(), 'bangla_page_layout', true ) : get_theme_mod( 'bangla_page_layout', 'sidebar-right' );

	$grid_class = ['broxme_wp-grid'];


	$large        = rwmb_meta( 'bangla_blog_columns' );
	$medium       = rwmb_meta( 'bangla_blog_columns_medium' );
	$small        = rwmb_meta( 'bangla_blog_columns_small' );
	
	$grid_class[] = ($large != null) ? 'broxme_wp-child-width-1-'.$large.'@l' : 'broxme_wp-child-width-1-3@l' ;
	$grid_class[] = ($medium != null) ? 'broxme_wp-child-width-1-'.$medium.'@m' : 'broxme_wp-child-width-1-2@m';
	$grid_class[] = ($small != null) ? 'broxme_wp-child-width-1-'.$small : 'broxme_wp-child-width-1-1';
	$column_gap   = rwmb_meta( 'bangla_blog_columns_gap');
	$limit        = rwmb_meta( 'bangla_blog_limit');
	$grid_class[] = ($column_gap) ? 'broxme_wp-grid-'.$column_gap : '';


	global $wp_query;
	// Pagination fix to work when set as Front Page
	// $paged = get_query_var('paged') ? get_query_var('paged') : 1;
	if ( get_query_var('paged') ) { $paged = get_query_var('paged'); } elseif ( get_query_var('page') ) { $paged = get_query_var('page'); } else { $paged = 1; }	

	// Get Categories
	$categories = rwmb_meta( 'bangla_blog_categories');

	$args = array(
		'posts_per_page' => intval($limit),
		'post_status' => 'publish',
		'orderby'     => 'date',
		'order'       => 'DESC',
		'cat'         => $categories,
		'paged'       => $paged
	);
	$wp_query = new WP_Query($args);

?>

<div<?php echo bangla_helper::section('main'); ?>>
	<div<?php echo bangla_helper::container(); ?>>
		<div<?php echo bangla_helper::grid(); ?>>
			
			<div class="broxme_wp-width-expand">
				<main class="tm-content">
					<?php if ($large != 1) : ?>
						<div<?php echo bangla_helper::attrs(['class' => $grid_class]) ?> broxme_wp-grid>
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
								<div>
									<?php get_template_part( 'template-parts/post-format/entry', get_post_format() ); ?>
								</div>
							<?php endwhile; endif; ?>
						</div>
					<?php else : ?>
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							<?php get_template_part( 'template-parts/post-format/entry', get_post_format() ); ?>
						<?php endwhile; endif; ?>
					<?php endif; ?>

					<?php get_template_part( 'template-parts/pagination' ); ?>
				</main> <!-- end main -->
			</div> <!-- end content -->

			<?php if($position == 'sidebar-left' || $position == 'sidebar-right') : ?>
				<aside<?php echo bangla_helper::sidebar($position); ?>>
				    <?php get_sidebar(); ?>
				</aside> <!-- end aside -->
			<?php endif; ?>
			
		</div> <!-- end grid -->
	</div> <!-- end container -->
</div> 
	
<?php get_footer(); ?>
