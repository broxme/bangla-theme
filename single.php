<?php get_header(); 

// Layout
$position = (get_post_meta( get_the_ID(), 'bangla_page_layout', true )) ? get_post_meta( get_the_ID(), 'bangla_page_layout', true ) : get_theme_mod( 'bangla_page_layout', 'sidebar-right' );
?>



<div<?php echo bangla_helper::section(); ?>>
	<div<?php echo bangla_helper::container(); ?>>
		<div<?php echo bangla_helper::grid(); ?>>
			<div class="broxme_wp-width-expand">
				<main class="tm-content broxme_wp-text-<?php echo get_theme_mod('bangla_blog_align', 'left'); ?>">
			
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
						<?php get_template_part( 'template-parts/post-format/entry', get_post_format() ); ?>
						
						
						<?php get_template_part( 'template-parts/author' ); ?>
							
						<?php if(get_theme_mod('bangla_related_post')) { ?>	
						
								<?php //for use in the loop, list 5 post titles related to first tag on current post
								$tags = wp_get_post_tags($post->ID);
								if($tags) {
								?>

								<div id="related-posts">
									<h3 class="broxme_wp-heading-bullet broxme_wp-margin-medium-bottom"><?php esc_html_e('Related Posts', 'bangla'); ?></h3>
									<ul class="broxme_wp-list broxme_wp-list-divider">
										<?php  $first_tag = $tags[0]->term_id;
										  $args=array(
										    'tag__in' => array($first_tag),
										    'post__not_in' => array($post->ID),
										    'showposts'=>4
										   );
										  $my_query = new WP_Query($args);
										  if( $my_query->have_posts() ) {
										    while ($my_query->have_posts()) : $my_query->the_post(); ?>
										      <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Link to <?php the_title_attribute(); ?>" class="broxme_wp-link-reset broxme_wp-margin-small-right"><?php the_title(); ?></a> <span class="broxme_wp-article-meta"><?php the_time(get_option('date_format')); ?></span></li>
										      <?php
										    endwhile;
										    wp_reset_postdata();
										  } ?>
									</ul>
								</div>

								<hr class="broxme_wp-margin-large-top broxme_wp-margin-large-bottom">
								
								<?php } // end if $tags ?>

						<?php } ?>
					
						<?php comments_template(); ?>
						
						<?php if(get_theme_mod('bangla_blog_next_prev', 1)) { ?>

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

			<?php if(is_active_sidebar( 'blog-widgets' ) and ($position == 'sidebar-left' or $position == 'sidebar-right')) : ?>
				<aside<?php echo bangla_helper::sidebar($position); ?>>
				    <?php get_sidebar(); ?>
				</aside> <!-- end aside -->
			<?php endif; ?>
			
		</div> <!-- end grid -->
	</div> <!-- end container -->
</div> <!-- end tm main -->
	
<?php get_footer(); ?>