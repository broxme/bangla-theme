<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" role="search" class="broxme_wp-search broxme_wp-search-default broxme_wp-width-1-1">
    <span broxme_wp-search-icon></span>
    <input id="<?php echo esc_attr($unique_id); ?>" name="s" placeholder="<?php esc_html_e('Search...', 'bangla'); ?>" type="search" class="broxme_wp-search-input">
</form>