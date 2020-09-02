<?php
$author_desc = get_the_author_meta('description');
?>

<?php if(!is_single() or is_single()) :?>

		<?php if(!is_single()) :?>
		<p><?php echo wp_kses_post(rooten_custom_excerpt(50)); ?></p>

		<?php elseif(is_single()) :?>
		    <?php the_content(); ?>

		    <?php if(empty($author_desc) or have_comments()) : ?>
		    	<hr class="broxme_wp-margin-large-top broxme_wp-margin-large-bottom">
			<?php endif ?>
		<?php endif ?>

		<?php
		wp_link_pages( array(
			'before'      => '<div class="page-links"><span>' . esc_html__( 'Pages:', 'bangla' ).'</span>',
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		) );

		?>

<?php endif ?>