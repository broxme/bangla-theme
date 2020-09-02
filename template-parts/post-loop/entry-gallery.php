<article id="post-<?php the_ID() ?>" <?php post_class('broxme_wp-article post-format-gallery') ?> data-permalink="<?php the_permalink() ?>" typeof="Article">

    <?php get_template_part( 'template-parts/post-format/schema-meta' ); ?>
    
    <?php 
    //$images = get_post_meta( get_the_ID(), 'bangla_blog_gallery', true );
    $images = rwmb_meta( 'bangla_blog_gallery', 'type=image_advanced&size=bangla_blog' );
    if (!empty($images)) : ?>

    <div class="post-image-gallery<?php echo (is_single()) ? ' broxme_wp-margin-large-bottom' : ' broxme_wp-margin-bottom'; ?>">
        <div class="image-lightbox owl-carousel owl-theme" data-owl-carousel='{"margin": 10, "items": 1, "nav": true, "navText": "", "loop": true}'>
            <?php 
            foreach ( $images as $image) {
                echo '<div class="carousel-cell"><a href="'.esc_url($image['full_url']).'" title="'.esc_attr($image['title']).'"><img src="'.esc_url($image['url']).'" alt="'.esc_attr($image['alt']).'" width="'.esc_attr($image['width']).'" height="'.esc_attr($image['height']).'" class="broxme_wp-border-rounded" /></a></div>';
            } ?>
        </div>
    </div>

    <?php endif ?>
    



    <div class="broxme_wp-margin-medium-bottom broxme_wp-container broxme_wp-container-small broxme_wp-text-center">
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