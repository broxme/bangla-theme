<?php get_header(); 

// Layout
$position = (get_post_meta( get_the_ID(), 'bangla_page_layout', true )) ? get_post_meta( get_the_ID(), 'bangla_page_layout', true ) : get_theme_mod( 'bangla_page_layout', 'sidebar-right' );
$width = '1-3';
?>

<div<?php echo bangla_helper::section(); ?>>
	<div<?php echo bangla_helper::container(); ?>>
		<div<?php echo bangla_helper::grid(); ?>>
			<div class="broxme_wp-width-expand">
				<main class="tm-content">
			
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
						<?php get_template_part( 'template-parts/testimonials/entry' ); ?>
					
						<?php if(get_theme_mod('bangla_service_next_prev', 1)) { ?>

							<hr>	

							<ul class="broxme_wp-pagination">
							    <li>
							    	<?php
							        	$pre_btn_txt = '<span class="broxme_wp-margin-small-right" broxme_wp-pagination-previous></span> '. esc_html__('Previous', 'bangla'); 
							        	previous_post_link('%link', "{$pre_btn_txt}", FALSE); 
							        ?>
							        
							    </li>
							    <li class="broxme_wp-margin-auto-left">
							    	<?php $next_btn_txt = esc_html__('Next', 'bangla') . ' <span class="broxme_wp-margin-small-left" broxme_wp-pagination-next></span>';
		                    			next_post_link('%link', "{$next_btn_txt}", FALSE); ?>
		                    	</li>
							</ul>
						<?php } ?>
				
					<?php endwhile; endif; ?>
				</main> <!-- end main -->
			</div> <!-- end expand -->

			<?php if($position == 'sidebar-left' || $position == 'sidebar-right' ) : ?>
				<aside<?php echo bangla_helper::sidebar($position, $width); ?>>
					<?php get_sidebar(); ?>
				</aside> <!-- end aside -->
			<?php endif; ?>
			
		</div> <!-- end grid -->
	</div> <!-- end container -->
</div> <!-- end tm main -->
	
<?php get_footer(); ?>