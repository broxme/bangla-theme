<?php if(is_single()) :?>
    <?php 
        $email            = get_post_meta( get_the_ID(), 'bdthemes_portfolio_email', true );
        $phone            = get_post_meta( get_the_ID(), 'bdthemes_portfolio_phone', true );
        $appointment_link = get_post_meta( get_the_ID(), 'bdthemes_portfolio_appointment_link', true );
        $link_title       = get_post_meta( get_the_ID(), 'bdthemes_portfolio_appointment_link_title', true );
        $badge            = get_post_meta( get_the_ID(), 'bdthemes_portfolio_badge', true );
        $social_link      = get_post_meta( get_the_ID(), 'bdthemes_portfolio_social_link', true );
    ?>
    <div class="broxme_wp-card broxme_wp-card-default">
        
        <div class="broxme_wp-position-relative">
            <?php get_template_part( 'template-parts/portfolio/media' ); ?>
            <div class="broxme_wp-position-cover broxme_wp-overlay broxme_wp-overlay-gradient broxme_wp-position-z-index"></div>

            <?php if($social_link != null) : ?>
                <ul class="broxme_wp-list broxme_wp-position-medium broxme_wp-position-bottom-left broxme_wp-position-z-index broxme_wp-margin-remove-bottom">
                <?php foreach ($social_link as $link) : ?>
                    <?php $tooltip = ucfirst(bangla_helper::icon($link)); ?>
                    <li class="broxme_wp-display-inline-block">
                        <a<?php echo bangla_helper::attrs(['href' => $link, 'class' => 'broxme_wp-margin-small-right']); ?> broxme_wp-icon="icon: <?php echo bangla_helper::icon($link); ?>" title="<?php echo esc_attr($tooltip); ?>" broxme_wp-tooltip></a>
                    </li>
                <?php endforeach ?>
                </ul>
            <?php endif; ?>

            
        </div>

        <div class="broxme_wp-card-header">
            <h3 class="broxme_wp-card-title"><?php echo get_the_title( ) . ' '; esc_html_e( 'Info', 'bangla' ); ?></h3>
        </div>

        <div class="broxme_wp-card-body">    
            <?php if($badge != null) : ?>
                <div class="broxme_wp-card-badge broxme_wp-label"><?php echo esc_attr($badge); ?></div>
            <?php endif; ?>

            <ul class="broxme_wp-list broxme_wp-list-divider broxme_wp-margin-small-bottom broxme_wp-padding-remove">

                <?php if($email != null) : ?>
                    <li class="">
                        <div class="broxme_wp-grid-small broxme_wp-flex-bottom" broxme_wp-grid>
                            <div class="broxme_wp-width-expand" broxme_wp-leader><?php echo esc_html_e ('Email: ', 'bangla'); ?></div>
                            <div class="broxme_wp-width-auto broxme_wp-text-bold"><?php echo esc_html($email); ?></div>
                        </div>
                       
                    </li>
                <?php endif; ?>

                <?php if($phone != null) : ?>
                    <li class="">
                        <div class="broxme_wp-grid-small broxme_wp-flex-bottom" broxme_wp-grid>
                            <div class="broxme_wp-width-expand" broxme_wp-leader><?php echo esc_html_e ('Phone Number: ', 'bangla'); ?></div>
                            <div class="broxme_wp-width-auto broxme_wp-text-bold"><?php echo esc_html($phone); ?></div>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <?php if($appointment_link != null) : ?>
            <div class="broxme_wp-card-footer">
                <a href="<?php echo esc_url($appointment_link); ?>" class="broxme_wp-button broxme_wp-button-primary broxme_wp-border-rounded broxme_wp-text-bold broxme_wp-width-1-1"><?php echo ($link_title) ? $link_title : esc_html__( 'Appointment Now', 'bangla' ); ?></a>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>