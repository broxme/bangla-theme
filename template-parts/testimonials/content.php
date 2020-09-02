<?php if(!is_single() or is_single()) :?>
		<?php if(!is_single()) :?>
		<p><?php echo wp_kses_post(bangla_custom_excerpt(50)); ?></p>

		<?php elseif(is_single()) :?>
		    <?php the_content(); ?>
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