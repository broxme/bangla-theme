<?php if(!is_single() or is_single()) :?>

	<?php if(!is_single()) :?>
	<h3 class="broxme_wp-margin-remove-top">
	    <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" class="broxme_wp-link-reset"><?php the_title(); ?></a>
	    <?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
	            <?php printf( '<span class="sticky-post featured">%s</span>', esc_html__( 'Featured', 'bangla') ); ?>
	    <?php endif; ?>
	</h3>
	<?php elseif(is_single()) :?>
	    <h3 class="broxme_wp-margin-remove-top"><?php the_title(); ?></h3>
	<?php endif ?>

<?php endif ?>