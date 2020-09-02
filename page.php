<?php get_header();

// Layout
$layout = get_post_meta( get_the_ID(), 'bangla_page_layout', true );
$position = (!empty($layout)) ? $layout : get_theme_mod( 'bangla_page_layout', 'sidebar-right' );

$class[] = ($layout !== 'full') ? 'broxme_wp-container' : ''; 

?>



<div<?php echo bangla_helper::section('main'); ?>>
	<div<?php echo bangla_helper::attrs(['class' => $class]) ?>>
		<div<?php echo bangla_helper::grid(); ?>>
			<div class="broxme_wp-width-expand">
				<main class="broxme_wp-content">
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

						<?php the_content(); ?>

						<?php wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>

						<?php if(get_theme_mod('bangla_comment_show', 1) == 1 and comments_open()) { ?>
							<hr class="broxme_wp-margin-large-top broxme_wp-margin-large-bottom">

							<?php comments_template(); ?>
						<?php } ?>

					<?php endwhile; endif; ?>
				</main> <!-- end main -->
			</div> <!-- end content -->

			<?php if($position == 'sidebar-left' or $position == 'sidebar-right') : ?>
				<aside<?php echo bangla_helper::sidebar($position); ?>>
				    <?php get_sidebar(); ?>
				</aside> <!-- end aside -->
			<?php endif; ?>

		</div> <!-- end grid -->
	</div> <!-- end container -->
</div> 
	
<?php get_footer(); ?>
