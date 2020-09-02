<?php
    $title       = 'yes';  // TODO
    $meta        = 'yes';  // TODO
    $excerpt     = 'no';  // TODO
    $align       = 'center';  // TODO
    $social_link = 'yes'; // TODO

?>

<div class="broxme_wp-portfolio-content-wrapper broxme_wp-box-shadow-small broxme_wp-portfolio-align-<?php echo esc_attr($align); ?>">

    <?php if (has_post_thumbnail()) : ?>
        <div class="portfolio-thumbnail broxme_wp-position-relative broxme_wp-overflow-hidden">
            <div class="portfolio-thumbnail-design">
                <?php get_template_part( 'template-parts/portfolio/media' ); ?>
                <div class="broxme_wp-portfolio-overlay broxme_wp-position-cover broxme_wp-overlay broxme_wp-overlay-gradient broxme_wp-position-z-index"></div>
            </div>  
        </div>
    <?php endif; ?>

    <?php if(( $title==='yes') or ( $meta==='yes') or ( $excerpt==='yes')) { ?>
        <div class="broxme_wp-portfolio-desc broxme_wp-padding broxme_wp-position-relative broxme_wp-position-z-index">

            <?php if( $title==='yes') { ?>
                <?php get_template_part( 'template-parts/portfolio/title' ); ?>
            <?php }; 

            if( $meta==='yes') {

                echo get_the_term_list(get_the_ID(),'experiences', '<ul class="broxme_wp-portfolio-meta broxme_wp-flex-'.$align.' broxme_wp-margin-small-top broxme_wp-margin-remove"><li>', '</li><li>', '</li></ul>' );
            }; 

            if( $social_link === 'yes') { 
                get_template_part( 'template-parts/portfolio/social-link' );
            }; ?>


            <?php if( $excerpt==='yes') { ?>
                <div class="broxme_wp-container broxme_wp-text-<?php echo esc_attr($align); ?> broxme_wp-container-small">
                        <?php get_template_part( 'template-parts/portfolio/content' ); ?>
                </div>
            <?php }; ?>
        </div>
    <?php }; ?>
</div>