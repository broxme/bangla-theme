<article id="post-<?php the_ID() ?>" <?php post_class('broxme_wp-article post-format-gallery broxme_wp-text-'.get_theme_mod('bangla_blog_align', 'center')) ?> data-permalink="<?php the_permalink() ?>" typeof="Article">

    <?php get_template_part( 'template-parts/post-format/schema-meta' ); ?>
    
    <?php 
    //$images = get_post_meta( get_the_ID(), 'bangla_blog_gallery', true );
    $images = rwmb_meta( 'bangla_blog_gallery', 'type=image_advanced&size=bangla_blog' );
    if (!empty($images)) : ?>

    <div class="post-image-gallery broxme_wp-position-relative broxme_wp-overflow-hidden broxme_wp-blog-thumbnail broxme_wp-margin-large-bottom">
        <div class="swiper-wrapper" broxme_wp-lightbox>
            <?php if (has_post_thumbnail()) : ?>
                <div class="swiper-slide">
                    <?php echo  the_post_thumbnail('bangla_blog', array('class' => 'broxme_wp-width-1-1'));  ?>
                </div>
            <?php endif; ?>
            
            <?php foreach ( $images as $image) : ?> 
                <div class="swiper-slide">
                <a href="<?php echo esc_url($image['full_url']); ?>" title="<?php echo esc_attr($image['title']); ?>">
                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" width="<?php echo esc_attr($image['width']); ?>" height="<?php echo esc_attr($image['height']); ?>" class="" />
                </a>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <img class="broxme_wp-blog-entry-overlay" src="<?php echo get_template_directory_uri(); ?>/images/blog-entry-overlay.svg" alt="">
    </div>

    <?php endif ?>
    
    


    <div class="broxme_wp-margin-medium-bottom broxme_wp-container broxme_wp-container-small">
        <?php get_template_part( 'template-parts/post-format/title' ); ?>

        <?php if(get_theme_mod('bangla_blog_meta', 1)) :?>
        <?php get_template_part( 'template-parts/post-format/meta' ); ?>
        <?php endif; ?>
    </div>
    
    <div class="broxme_wp-container broxme_wp-container-small">
        <?php get_template_part( 'template-parts/post-format/content' ); ?>

        <?php get_template_part( 'template-parts/post-format/read-more' ); ?>
    </div>

</article>