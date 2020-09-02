<article id="post-<?php the_ID() ?>" <?php post_class('broxme_wp-article post-format-link') ?> data-permalink="<?php the_permalink() ?>" typeof="Article">

    <?php get_template_part( 'template-parts/post-format/schema-meta' ); ?>

    <?php 
    $link_url = get_post_meta( get_the_ID(), 'bangla_blog_link', true );

    if (!empty($link_url)) : ?>

    <div class="post-link broxme_wp-border-rounded<?php echo (is_single()) ? ' broxme_wp-margin-large-bottom' : ' broxme_wp-margin-bottom'; ?>">
        <a href="<?php echo esc_url($link_url) ?>" title="<?php printf( esc_attr__('Link to %s', 'bangla'), the_title_attribute('echo=0') ); ?>" target="_blank"><?php the_title(); ?><span><?php echo esc_html($link_url) ?></span></a>
    </div>
    <?php endif ?>
    
    <?php if(is_single()) : ?>
        <div class="broxme_wp-margin-medium-bottom broxme_wp-container broxme_wp-container-small broxme_wp-text-center">
            <?php if(get_theme_mod('bangla_blog_meta', 1)) :?>
            <?php get_template_part( 'template-parts/post-format/meta' ); ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</article>