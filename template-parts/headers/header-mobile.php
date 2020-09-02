<?php

// Options
$logo            = get_theme_mod('bangla_logo_mobile');;
$mobile          = get_theme_mod('mobile', []);
$logo_align      = get_theme_mod('bangla_mobile_logo_align', 'center');
$menu_align      = get_theme_mod('bangla_mobile_menu_align', 'left');
$search_align    = get_theme_mod('bangla_mobile_search_align', 'right');
$offcanvas_style = get_theme_mod('bangla_mobile_offcanvas_style', 'offcanvas');
$offcanvas_mode  = get_theme_mod('bangla_mobile_offcanvas_mode', 'slide');
$menu_text       = get_theme_mod('bangla_mobile_menu_text');
$shadow          = get_theme_mod('bangla_header_shadow', 'small');
$break_point     = 'broxme_wp-hidden@'.get_theme_mod('bangla_mobile_break_point', 'm');
$class           = ['tm-header-mobile', $break_point];
$class[]         = ($shadow) ? 'broxme_wp-box-shadow-'.$shadow : '';


$offcanvas_color = get_theme_mod( 'bangla_offcanvas_color', 'dark' );
$offcanvas_color = ($offcanvas_color !== 'custom') ? 'broxme_wp-'.$offcanvas_color : 'custom-color';


$search_align = false; // TODO

?>
<div<?php echo bangla_helper::attrs(['class' => $class]) ?>>
    <nav class="broxme_wp-navbar-container" broxme_wp-navbar>

        <?php if ($logo_align == 'left' || $menu_align == 'left' || $search_align == 'left') : ?>
        <div class="broxme_wp-navbar-left">

            <?php if ($menu_align == 'left') : ?>
            <a class="broxme_wp-navbar-toggle" href="#tm-mobile" broxme_wp-toggle<?php echo ($offcanvas_style == 'dropdown') ? '="animation: true"' : '' ?>>
                <span broxme_wp-navbar-toggle-icon></span>
                <?php if ($menu_text) : ?>
                <span class="broxme_wp-margin-small-left"><?php esc_html_e('Menu', 'bangla') ?></span>
                <?php endif ?>
            </a>
            <?php endif ?>

            <?php if ($search_align == 'left') : ?>
            <a class="broxme_wp-navbar-item"><?php esc_html_e('Search', 'bangla') ?></a>
            <?php endif ?>

            <?php if ($logo_align == 'left') : ?>
            <?php get_template_part( 'template-parts/logo-mobile' ); ?>
            <?php endif ?>

        </div>
        <?php endif ?>

        <?php if ($logo_align == 'center') : ?>
        <div class="broxme_wp-navbar-center">
            <?php get_template_part( 'template-parts/logo-mobile' ); ?>
        </div>
        <?php endif ?>

        <?php if ($logo_align == 'right' || $menu_align == 'right' || $search_align == 'right') : ?>
        <div class="broxme_wp-navbar-right">

            <?php if ($logo_align == 'right') : ?>
            <?php get_template_part( 'template-parts/logo-mobile' ); ?>
            <?php endif ?>

            <?php if ($search_align == 'right') : ?>
            <a class="broxme_wp-navbar-item"><?php esc_html_e('Search', 'bangla') ?></a>
            <?php endif ?>

            <?php if ($menu_align == 'right') : ?>
            <a class="broxme_wp-navbar-toggle" href="#tm-mobile" broxme_wp-toggle<?php echo ($offcanvas_style) == 'dropdown' ? '="animation: true"' : '' ?>>
                <?php if ($menu_text) : ?>
                <span class="broxme_wp-margin-small-right"><?php esc_html_e('Menu', 'bangla') ?></span>
                <?php endif ?>
                <span broxme_wp-navbar-toggle-icon></span>
            </a>
            <?php endif ?>

        </div>
        <?php endif ?>

    </nav>

    <?php if ($shadow == 'special') : ?>
        <div class="tm-header-shadow">
            <div></div>
        </div>
    <?php endif; ?>

    <?php if (is_active_sidebar('offcanvas') or has_nav_menu('offcanvas')) :

        if ($offcanvas_style == 'offcanvas') : ?>
        <div id="tm-mobile" class="<?php echo esc_attr($offcanvas_color); ?>" broxme_wp-offcanvas mode="<?php echo esc_html($offcanvas_mode); ?>" overlay>
            <div class="broxme_wp-offcanvas-bar broxme_wp-dark">
                <?php get_template_part( 'template-parts/offcanvas' ); ?>
            </div>
        </div>
        <?php endif ?>

        <?php if ($offcanvas_style == 'modal') : ?>
        <div id="tm-mobile" class="broxme_wp-modal-full <?php echo esc_attr($offcanvas_color); ?>" broxme_wp-modal>
            <div class="broxme_wp-modal-dialog broxme_wp-modal-body">
                <button class="broxme_wp-modal-close-full" type="button" broxme_wp-close></button>
                <div class="broxme_wp-flex broxme_wp-flex-center broxme_wp-flex-middle" broxme_wp-height-viewport>
                    <?php get_template_part( 'template-parts/offcanvas' ); ?>
                </div>
            </div>
        </div>
        <?php endif ?>

        <?php if ($offcanvas_style == 'dropdown') : ?>
        <div class="broxme_wp-position-relative broxme_wp-position-z-index">
            <div id="tm-mobile" class="broxme_wp-box-shadow-medium<?php echo ($offcanvas_mode == 'slide') ? ' broxme_wp-position-top' : '' ?> <?php echo esc_attr($offcanvas_color); ?>" hidden>
                <div class="broxme_wp-background-default broxme_wp-padding">
                    <?php get_template_part( 'template-parts/offcanvas' ); ?>
                </div>
            </div>
        </div>
        <?php endif ?>

    <?php else : ?>
        <div id="tm-mobile" class="<?php echo esc_attr($offcanvas_color); ?>" broxme_wp-offcanvas mode="<?php echo esc_html($offcanvas_mode); ?>" overlay>
            <div class="broxme_wp-offcanvas-bar">
                <?php esc_html_e( 'Ops! You don\'t have any menu or widget in Off-canvas. Please add some menu in Off-canvas menu position or add some widget in Off-canvas widget position for view them here.', 'bangla' ); ?>
            </div>
        </div>
    <?php endif; ?>
</div>