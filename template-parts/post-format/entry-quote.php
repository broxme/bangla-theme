<article id="post-<?php the_ID() ?>" <?php post_class('broxme_wp-article post-format-quote broxme_wp-text-'.get_theme_mod('bangla_blog_align', 'center')) ?> data-permalink="<?php the_permalink() ?>" typeof="Article">

    <?php get_template_part( 'template-parts/post-format/schema-meta' ); ?>
    
    <?php 
    $quote_text = get_post_meta( get_the_ID(), 'bangla_blog_quote', true );
    $quote_src = get_post_meta( get_the_ID(), 'bangla_blog_quotesrc', true );

    if (!empty($quote_text)) : ?>
    <div class="post-quote<?php echo (is_single()) ? ' broxme_wp-margin-large-bottom' : ' broxme_wp-margin-bottom'; ?>">
        <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__('Permalink to %s', 'bangla'), the_title_attribute('echo=0') ); ?>" class="quote-text"><?php echo esc_html($quote_text); ?>
        <span class="quote-source"><?php echo esc_html($quote_src); ?></span></a>
    </div>

    <?php else : ?>

        <?php echo 'Please insert a Quote'; ?>

    <?php endif ?>
    
    <?php if(is_single()) : ?>
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
    <?php endif ?>

</article>