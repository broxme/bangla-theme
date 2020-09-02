<article id="post-<?php the_ID() ?>" <?php post_class('broxme_wp-article post-format-video broxme_wp-text-'.get_theme_mod('bangla_blog_align', 'center')) ?> data-permalink="<?php the_permalink() ?>" typeof="Article">

    <?php get_template_part( 'template-parts/post-format/schema-meta' ); ?>

    <?php 
    $video = get_post_meta( get_the_ID(), 'bangla_blog_video', true );
    if (!empty($video)) : ?>
    <?php $video_src = get_post_meta( get_the_ID(), 'bangla_blog_videosrc', true ); 
    if (!empty($video_src) and $video_src = 'embedcode' ) : ?>
        <div class="post-video<?php echo (is_single()) ? ' broxme_wp-margin-large-bottom' : ' broxme_wp-margin-bottom'; ?>">
            <?php echo wp_kses(get_post_meta( get_the_ID(), 'bangla_blog_video', true ), bangla_allowed_tags()); ?>
        </div>
    <?php else : ?>

        <div class="post-video<?php echo (is_single()) ? ' broxme_wp-margin-large-bottom' : ' broxme_wp-margin-bottom'; ?>">
            <?php echo wp_oembed_get(esc_url($video)); ?>
        </div>

    <?php endif; ?>


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