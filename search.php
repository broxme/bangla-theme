<?php 

get_header();

// Layout
$position = (is_active_sidebar('search-results-widgets')) ? get_theme_mod( 'bangla_page_layout', 'sidebar-right' ) : '';

?>

<div<?php echo bangla_helper::section(); ?>>
	<div<?php echo bangla_helper::container(); ?>>
		<div<?php echo bangla_helper::grid(); ?>>
			<div class="broxme_wp-width-expand">
				<main class="tm-content">

					<h3><?php esc_html_e('New Search', 'bangla') ?></h3>

					<p><?php esc_html_e('If you are not happy with the results below please do another search', 'bangla') ?></p>

					<?php get_search_form(); ?>

					<div class="broxme_wp-clearfix"></div>
				
					<hr class="broxme_wp-article-divider">

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
						<article id="post-<?php the_ID(); ?>" <?php post_class('broxme_wp-article entry-search'); ?>>						        
						    <div class="entry-wrap">

						        <div class="entry-title">
						            <h3 class="broxme_wp-article-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__('Permalink to %s', 'bangla'), the_title_attribute('echo=0') ); ?>" rel="bookmark" class="broxme_wp-link-reset"><?php the_title(); ?></a></h3>
						        </div>

						        <div class="entry-type">
						        <?php if( get_post_type($post->ID) == 'post' ){ ?>
						        	<?php echo esc_html__('Blog Post', 'bangla'); ?>
						        <?php } elseif( get_post_type($post->ID) == 'page' ){ ?>
						        	<?php echo esc_html__('Page', 'bangla'); ?>
						        <?php } elseif( get_post_type($post->ID) == 'tribe_events' ){ ?>
						        	<?php echo esc_html__('Event', 'bangla'); ?>
						        <?php } elseif( get_post_type($post->ID) == 'campaign' ){ ?>
						        	<?php echo esc_html__('Campaign', 'bangla'); ?>
						        <?php } elseif( get_post_type($post->ID) == 'product' ){ ?>
						        	<?php echo esc_html__('Product', 'bangla'); ?>
						        <?php } ?>
						        </div>

					        	<?php if (bangla_custom_excerpt(100) != '') { ?>
										<div class="entry-content">
											<?php echo wp_kses_post(bangla_custom_excerpt(100)); ?>
										</div>
					        	<?php } ?>

					        	<?php
					        		$post_title = get_the_title();
				        		if (empty($post_title)) : ?>
						        		<p class="broxme_wp-article-meta">
						        			<?php if(get_the_date()) : ?>
						        				<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__('Permalink to %s', 'bangla'), the_title_attribute('echo=0') ); ?>" rel="bookmark" class="broxme_wp-link-reset"><time><?php printf(get_the_date()); ?></time></a>
						        			<?php endif; ?>
						        			<?php if(get_the_author()) : ?>
						        		    <?php printf(esc_html__('Written by %s.', 'bangla'), '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'" title="'.get_the_author().'">'.get_the_author().'</a>'); ?>
						        		    <?php endif; ?>

						        		    <?php if(get_the_category_list()) : ?>
						        		        <?php printf(esc_html__('Posted in %s', 'bangla'), get_the_category_list(', ')); ?>
						        		    <?php endif; ?>
						        		</p>
					        	<?php endif; ?>

						    </div>

						</article><!-- #post -->
						
					<?php endwhile; ?>
		
					<?php get_template_part( 'template-parts/pagination' ); ?>
	
					<?php else : ?>
						<div class="broxme_wp-alert broxme_wp-alert-warning broxme_wp-text-large"><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'bangla') ?></div>
					<?php endif; ?>
				</main> <!-- end main -->
			</div> <!-- end content -->

			<?php if($position == 'sidebar-left' || $position == 'sidebar-right') : ?>
				<aside<?php echo bangla_helper::sidebar($position); ?>>
				    <?php get_sidebar(); ?>
				</aside> <!-- end aside -->
			<?php endif; ?>

		</div> <!-- end grid -->
	</div> <!-- end container -->
</div> <!-- end main -->
	
<?php get_footer(); ?>