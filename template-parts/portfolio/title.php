<?php if(!is_single() or is_single()) :?>
	<?php if(!is_single()) :?>
	<h3 class="broxme_wp-portfolio-title broxme_wp-margin-remove-top broxme_wp-margin-small-bottom">
	    <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" class="broxme_wp-link-reset"><?php the_title(); ?></a>
	    <?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
	            <?php printf( '<span class="sticky-post featured">%s</span>', esc_html__( 'Featured', 'bangla') ); ?>
	    <?php endif; ?>
	</h3>
	
	<?php elseif(is_single()) :?>
		<div class="broxme_wp-article-title">
		    <h1 class="broxme_wp-margin-remove-top broxme_wp-heading-bullet">
		    	<?php the_title(); ?>
		    </h1>
		</div>
	<?php endif ?>
<?php endif ?>