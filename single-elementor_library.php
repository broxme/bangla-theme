<?php get_header();

?>

<div class="broxme_wp-section broxme_wp-section broxme_wp-padding-remove-vertical">
			
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		
		<?php the_content(); ?>

	<?php endwhile; endif; ?>
		
</div> 
	
<?php get_footer(); ?>