<?php if(is_home() and get_theme_mod('bangla_blog_readmore', 1)) :?>

<p class="broxme_wp-text-center broxme_wp-margin-medium">
	<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" class="broxme_wp-button broxme_wp-button-text"><?php esc_html_e('Read More...', 'bangla'); ?></a>
</p>

<?php endif; ?>