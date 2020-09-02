<?php if(get_theme_mod('bangla_related_post')) { 
	//for use in the loop, list 5 post titles related to first tag on current post
	$tags = wp_get_post_tags($post->ID);
	if($tags) { ?>	
	<hr class="broxme_wp-divider-icon">
	<div id="related-posts">
		<h3><?php esc_html_e('Related Posts', 'bangla'); ?></h3>
		<ul class="broxme_wp-list broxme_wp-list-line">
			<?php  $first_tag = $tags[0]->term_id;
			  $args=array(
			    'tag__in' => array($first_tag),
			    'post__not_in' => array($post->ID),
			    'showposts'=>4
			   );
			  $my_query = new WP_Query($args);
			  if( $my_query->have_posts() ) {
			    while ($my_query->have_posts()) : $my_query->the_post(); ?>
			      <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a> <span class="broxme_wp-article-meta"><?php the_time(get_option('date_format')); ?></span></li>
			      <?php
			    endwhile;
			    wp_reset_postdata();
			  } ?>
		</ul>
	</div>
	
	<?php } // end if $tags 
} ?>