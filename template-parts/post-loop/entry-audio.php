<article id="post-<?php the_ID() ?>" <?php post_class('broxme_wp-article post-format-audio') ?> data-permalink="<?php the_permalink() ?>" typeof="Article">

    <?php get_template_part( 'template-parts/post-format/schema-meta' ); ?>

    <?php 
    $audio = get_post_meta( get_the_ID(), 'bangla_blog_audio', true );
    if (!empty($audio)) : ?>

    <div class="post-audio<?php echo (is_single()) ? ' broxme_wp-margin-large-bottom' : ' broxme_wp-margin-bottom'; ?>">

        <?php echo wp_kses($audio, bangla_allowed_tags()); ?>

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