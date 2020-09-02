<?php

$style             = get_theme_mod( 'bangla_search_style', 'default');
$search            = [];
$toggle            = ['class' => 'broxme_wp-search-icon broxme_wp-padding-remove-horizontal'];
$layout_c          = get_theme_mod('bangla_header_layout', 'horizontal-left');
$layout_m          = get_post_meta( get_the_ID(), 'bangla_header_layout', true );
$layout            = (!empty($layout_m) and $layout_m != 'default') ? $layout_m : $layout_c;
$position          = get_theme_mod( 'bangla_search_position', 'header');
$id                = esc_attr( uniqid( 'search-form-' ) );
$attrs['class']    = array_merge(['broxme_wp-search'], isset($attrs['class']) ? (array) $attrs['class'] : []);
$search            = [];
$search['class']   = [];
$search['class'][] = 'broxme_wp-search-input';

if (($layout == 'side-left' or $layout == 'side-right') and $position == 'menu') {
    $style = 'default';
}
// TODO
$navbar = [
    'dropdown_align'    => get_theme_mod( 'bangla_dropdown_align', 'left' ),
    'dropdown_click'    => get_theme_mod( 'bangla_dropdown_click' ),
    'dropdown_boundary' => get_theme_mod( 'bangla_dropdown_boundary' ),
    'dropbar'           => get_theme_mod( 'bangla_dropbar' ),
];

if ($style) {
    $search['autofocus'] = true;
}

if ($style == 'modal') {
    $search['class'][] = 'broxme_wp-text-center';
    $attrs['class'][] = 'broxme_wp-search-large';
} else {
    $attrs['class'][] = 'broxme_wp-search-default';
}

if (in_array($style, ['dropdown', 'justify'])) {
    $attrs['class'][] = 'broxme_wp-search-navbar';
    $attrs['class'][] = 'broxme_wp-width-1-1';
}

?>

<?php if ($style == 'default') : // TODO renders the default style only ?>

    <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search" <?php echo bangla_helper::attrs($attrs) ?>>
        <span broxme_wp-search-icon></span>
        <input id="<?php echo esc_attr($id); ?>" name="s" placeholder="<?php esc_html_e('Search...', 'bangla'); ?>" type="search" class="broxme_wp-search-input">
    </form>

<?php elseif ($style == 'drop') : ?>

    <a<?php echo bangla_helper::attrs($toggle) ?> href="#" broxme_wp-search-icon></a>
    <div broxme_wp-drop="mode: click; pos: left-center; offset: 0">
        <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search" <?php echo bangla_helper::attrs($attrs) ?>>
            <span broxme_wp-search-icon></span>
            <input id="<?php echo esc_attr($id); ?>" name="s" placeholder="<?php esc_html_e('Search...', 'bangla'); ?>" type="search" class="broxme_wp-search-input">
        </form>
    </div>

<?php elseif (in_array($style, ['dropdown', 'justify'])) :

    $drop = [
        'mode'           => 'click',
        'cls-drop'       => 'broxme_wp-navbar-dropdown',
        'boundary'       => $navbar['dropdown_align'] ? '!nav' : false,
        'boundary-align' => $navbar['dropdown_boundary'],
        'pos'            => $style == 'justify' ? 'bottom-justify' : 'bottom-right',
        'flip'           => 'x',
        'offset'         => !$navbar['dropbar'] ? 28 : 0
    ];

    ?>

    <a<?php echo bangla_helper::attrs($toggle) ?> href="#" broxme_wp-search-icon></a>
    <div class="broxme_wp-navbar-dropdown broxme_wp-width-medium" <?php echo bangla_helper::attrs(['broxme_wp-drop' => json_encode(array_filter($drop))]) ?>>
        <div class="broxme_wp-grid broxme_wp-grid-small broxme_wp-flex-middle">
            <div class="broxme_wp-width-expand">
               <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search" <?php echo bangla_helper::attrs($attrs) ?>>
                   <span broxme_wp-search-icon></span>
                   <input id="<?php echo esc_attr($id); ?>" name="s" placeholder="<?php esc_html_e('Search...', 'bangla'); ?>" type="search" class="broxme_wp-search-input">
               </form>
            </div>
            <div class="broxme_wp-width-auto">
                <a class="broxme_wp-navbar-dropdown-close" href="#" broxme_wp-close></a>
            </div>
        </div>

    </div>

<?php elseif ($style == 'modal') : ?>

    <a<?php echo bangla_helper::attrs($toggle) ?> href="#<?php echo esc_attr($id).'-modal' ?>" broxme_wp-search-icon broxme_wp-toggle></a>

    <div id="<?php echo esc_attr($id).'-modal' ?>" class="broxme_wp-modal-full" broxme_wp-modal>
        <div class="broxme_wp-modal-dialog broxme_wp-modal-body broxme_wp-flex broxme_wp-flex-center broxme_wp-flex-middle" broxme_wp-height-viewport>
            <button class="broxme_wp-modal-close-full" type="button" broxme_wp-close></button>
            <div class="broxme_wp-search broxme_wp-search-large">
               <form id="search-230" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search" <?php echo bangla_helper::attrs($attrs) ?>>
                    <input id="<?php echo esc_attr($id); ?>" name="s" placeholder="<?php esc_html_e('Type Word and Hit Enter', 'bangla'); ?>" type="search" class="broxme_wp-search-input broxme_wp-text-center" autofocus="">
               </form>
            </div>
        </div>
    </div>

<?php endif ?>
