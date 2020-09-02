<?php 

if(get_theme_mod('bangla_blog_next_prev', 1)) { ?>
	<ul class="broxme_wp-pagination">
	    <li>
	    	<?php
	    		$pre_btn_txt = '<span class="broxme_wp-margin-small-right" broxme_wp-pagination-previous></span> '. esc_html__('Previous', 'bangla'); 
	    		previous_post_link('%link', "{$pre_btn_txt}", FALSE); 
	    	?>
	    </li>
	    <li class="broxme_wp-margin-auto-left">
	    	<?php 
	    		$next_btn_txt = esc_html__('Next', 'bangla') . ' <span class="broxme_wp-margin-small-left" broxme_wp-pagination-next></span>';
    			next_post_link('%link', "{$next_btn_txt}", FALSE); 
    		?>
	    </li>
	</ul>
<?php }