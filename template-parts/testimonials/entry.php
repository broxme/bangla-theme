<?php 
    $address    = get_post_meta( get_the_ID(), 'jetpack_tm_address', true ); 
    $rating     = get_post_meta( get_the_ID(), 'jetpack_tm_rating', true ); 
    $background = get_post_meta( get_the_ID(), 'jetpack_tm_bg_style', true );
    $color      = get_post_meta( get_the_ID(), 'jetpack_tm_color_style', true );
 ?>


<div class="broxme_wp-testimonial-item">

    <div class="broxme_wp-box-shadow-medium">
        <div class="broxme_wp-testimonial-text broxme_wp-position-relative broxme_wp-<?php echo  ($color != null) ? $color : 'dark';  ?> broxme_wp-background-<?php echo  ($background != null) ? $background : 'default';  ?> broxme_wp-padding">

            <?php get_template_part( 'template-parts/testimonials/content' ); ?>

        </div>
    </div>

    <div class="broxme_wp-flex broxme_wp-flex-middle broxme_wp-margin-medium-top">

       <?php if (has_post_thumbnail()) : ?>
           <div class="broxme_wp-testimonial-thumb broxme_wp-margin-medium-right broxme_wp-display-block broxme_wp-overflow-hidden broxme_wp-border-circle broxme_wp-background-cover">

               <?php echo  the_post_thumbnail('thumbnail'); ?>

           </div>
       <?php endif; ?>

        <div>
            <?php get_template_part( 'template-parts/testimonials/title' ); ?>
            <?php if($address !='') : ?>
                <span class="broxme_wp-text-small">, <?php echo esc_html($address); ?></span>
            <?php endif; ?>

            <?php if($rating !='') : ?>
                <ul class="broxme_wp-rating tm-rating-<?php echo esc_attr($rating); ?> broxme_wp-text-muted broxme_wp-grid-collapse" broxme_wp-grid>
                    <li class="broxme_wp-rating-item"><span broxme_wp-icon="star"></span></li>
                    <li class="broxme_wp-rating-item"><span broxme_wp-icon="star"></span></li>
                    <li class="broxme_wp-rating-item"><span broxme_wp-icon="star"></span></li>
                    <li class="broxme_wp-rating-item"><span broxme_wp-icon="star"></span></li>
                    <li class="broxme_wp-rating-item"><span broxme_wp-icon="star"></span></li>
                </ul>
            <?php endif; ?>
        </div>

   </div>
    
</div>

<?php if(is_single() and empty($author_desc)) : ?>
    <div class="broxme_wp-margin-large-top"></div>
<?php endif ?>