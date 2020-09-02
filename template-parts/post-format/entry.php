<article id="post-<?php the_ID() ?>" <?php post_class('broxme_wp-article broxme_wp-text-'.get_theme_mod('bangla_blog_align', 'left')) ?> data-permalink="<?php the_permalink() ?>" typeof="Article">

    <?php get_template_part( 'template-parts/post-format/schema-meta' ); ?>

    <?php if (has_post_thumbnail()) : ?>
        <div class="broxme_wp-margin-large-bottom tm-blog-thumbnail">
            <?php if(is_single()) : ?>
                <?php echo  the_post_thumbnail('bangla_blog', array('class' => 'broxme_wp-border-rounded'));  ?>
            <?php else : ?>
                <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
                    <?php echo  the_post_thumbnail('bangla_blog', array('class' => 'broxme_wp-border-rounded'));  ?>
                </a>
            <?php endif; ?>           
        </div>
    <?php endif; ?>


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