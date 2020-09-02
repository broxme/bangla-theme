<?php
$description = get_bloginfo( 'description', 'display' );
if ( $description || is_customize_preview() ) : ?>
	<p class="site-description"><?php echo esc_html($description); ?></p>
<?php endif; ?>