<?php
$author_desc     = get_the_author_meta('description');
$company_name    = get_post_meta( get_the_ID(), 'bdthemes_company_name', true );
$complition_date = get_post_meta( get_the_ID(), 'bdthemes_complition_date', true );
$project_link    = get_post_meta( get_the_ID(), 'bdthemes_project_link', true );


?>

<article id="post-<?php the_ID() ?>" <?php post_class('broxme_wp-article') ?> data-permalink="<?php the_permalink() ?>" typeof="Article">

    <?php get_template_part( 'template-parts/portfolio/schema-meta' ); ?>
    
    <?php if (!is_single()) : ?>
        <?php get_template_part( 'template-parts/portfolio/media' ); ?>    
    <?php endif; ?>

    <div class="">
        <div class="broxme_wp-card broxme_wp-padding-large broxme_wp-grid" broxme_wp-grid>

            <div class="broxme_wp-width-3-5@m broxme_wp-hidden@m">
                <?php get_template_part( 'template-parts/portfolio/title' ); ?>
            </div>

            <ul class="broxme_wp-list broxme_wp-width-2-5@m">

                <?php if ( ! empty( $company_name ) ) : ?>
                    <li><span class="broxme_wp-width-1-2 broxme_wp-display-inline-block broxme_wp-text-bold"><?php echo __('Company/Owner Name', 'bangla'); ?></span><span class="broxme_wp-width-1-2 broxme_wp-display-inline-block"><span class="broxme_wp-margin-medium-right">:</span><?php echo esc_attr( $company_name ); ?></span></li>
                <?php endif; ?>

                <li><span class="broxme_wp-width-1-2 broxme_wp-display-inline-block broxme_wp-text-bold"><?php echo __('Starting Date', 'bangla'); ?></span><span class="broxme_wp-width-1-2 broxme_wp-display-inline-block"><span class="broxme_wp-margin-medium-right">:</span><?php echo esc_attr(get_the_date('M d, Y')); ?></span></li>

                <?php if ( ! empty( $complition_date ) ) : ?>
                    <li><span class="broxme_wp-width-1-2 broxme_wp-display-inline-block broxme_wp-text-bold"><?php echo __('Complition Date', 'bangla'); ?></span><span class="broxme_wp-width-1-2 broxme_wp-display-inline-block"><span class="broxme_wp-margin-medium-right">:</span><?php echo esc_attr( $complition_date ); ?></span></li>
                <?php endif; ?>

                <?php if ( ! empty( $project_link ) ) : ?>
                    <li><span class="broxme_wp-width-1-2 broxme_wp-display-inline-block broxme_wp-text-bold"><?php echo __('Project Link', 'bangla'); ?></span><span class="broxme_wp-width-1-2 broxme_wp-display-inline-block"><a href="<?php echo esc_url( $project_link ); ?>" class="broxme_wp-button-link broxme_wp-link-reset" broxme_wp-icon="arrow-right"><span class="broxme_wp-margin-medium-right">:</span><?php echo __('Click Here ', 'bangla'); ?></a></li>
                <?php endif; ?>

            </ul>

            <div class="broxme_wp-width-3-5@m broxme_wp-text-right broxme_wp-visible@m">
                <?php get_template_part( 'template-parts/portfolio/title' ); ?>
            </div>

        </div>
        <?php

        $images = rwmb_meta( 'bdthemes_gallery', 'type=image_advanced&size=parlour_blog' );

        if ( ! empty( $images ) ) {

            ?>
            <div class="broxme_wp-margin-medium-bottom" broxme_wp-slideshow>
                <ul class="broxme_wp-slideshow-items">
                    <?php 
                    foreach ( $images as $image) {
                        echo '<li><img src="" alt="" broxme_wp-cover><img src="'.esc_url($image['url']).'" alt="'.esc_attr($image['alt']).'" broxme_wp-cover></li>';
                    } ?>
                </ul>
            </div>
            <?php

        } else {
            get_template_part( 'template-parts/portfolio/media' );
        }

        ?>
        <div class="broxme_wp-padding">

            <?php get_template_part( 'template-parts/portfolio/content' ); ?>
            
            <div>
                <?php edit_post_link(esc_html__('Edit this post', 'bangla'), '<div class="broxme_wp-button-text">','</div>'); ?>               
                <?php get_template_part( 'template-parts/portfolio/read-more' ); ?>
            </div>
        </div>

    </div>
</article>

<?php if(is_single() and empty($author_desc)) : ?>
    <hr class="broxme_wp-margin-remove-top broxme_wp-margin-medium-bottom">
<?php endif ?>