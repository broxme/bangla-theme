<?php 
//$images = get_post_meta( get_the_ID(), 'medium_gallery', true );
$images = rwmb_meta( 'bdthemes_portfolio_altimg', 'type=image_advanced&size=medium' );


?>

<?php if (has_post_thumbnail() and empty($images) ) : ?>
    <div class="">
        <?php if(is_single()) : ?>
            <?php echo  the_post_thumbnail('large', array('class' => 'broxme_wp-width-1-1'));  ?>
        <?php else : ?>
            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
                <?php echo  the_post_thumbnail('medium', array('class' => 'broxme_wp-width-1-1'));  ?>
            </a>
        <?php endif; ?>           
    </div>

<?php elseif (!empty($images)) : ?>
    
    <?php if(is_single()) : ?>
        <div class="">
            <?php echo  the_post_thumbnail('large', array('class' => 'broxme_wp-width-1-1'));  ?>
        </div>
    <?php else : ?>
        <div class="portfolio-image-gallery broxme_wp-position-relative broxme_wp-overflow-hidden" broxme_wp-toggle="target: > .portfolio-img-flip; mode: hover; animation: broxme_wp-animation-fade; queued: true; duration: 300" broxme_wp-lightbox>

                <div class="portfolio-img-flip broxme_wp-position-absolute broxme_wp-position-z-index">
                    <?php echo  the_post_thumbnail('medium', array('class' => 'broxme_wp-width-1-1'));  ?>
                </div>
                
                <?php foreach ( $images as $image) : ?> 
                    <div class="portfolio-img">
                        <a href="<?php echo esc_url($image['full_url']); ?>" title="<?php echo esc_attr($image['title']); ?>">
                            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" width="<?php echo esc_attr($image['width']); ?>" height="<?php echo esc_attr($image['height']); ?>" class="" />
                        </a>
                    </div>
                <?php endforeach; ?>
        </div>
    <?php endif; ?>           

<?php endif ?>
    
