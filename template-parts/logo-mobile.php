<?php
$logo                   = get_theme_mod('bangla_logo_mobile');
$logo_width             = get_theme_mod('bangla_logo_width_mobile');
$width                  = ($logo_width) ? $logo_width : '';
$img_atts               = [];
$img_atts['class'][]    = 'broxme_wp-responsive-height';
$img_atts['style'][]    = 'width:'.esc_attr($width);
$img_atts['src'][]      = esc_url($logo);
$img_atts['itemprop'][] = 'logo';
$img_atts['alt'][]      = get_bloginfo( 'name' );

?>

<a href="<?php echo esc_url(home_url('/')); ?>"<?php echo bangla_helper::attrs(['class' => 'broxme_wp-logo broxme_wp-navbar-item']) ?> itemprop="url">
    <?php if ($logo) : ?>
        <img<?php echo bangla_helper::attrs($img_atts) ?>>
    <?php else : ?>
        <?php bloginfo( 'name' );?>
    <?php endif; ?>
</a>