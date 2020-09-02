<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bangla
 */
	
	$loading_html   = [];
	$logo_default   = get_theme_mod('bangla_logo_default');
	$custom_logo    = get_theme_mod('bangla_preloader_custom_logo');
	$logo           = get_theme_mod('bangla_preloader_logo', 1);
	$final_logo     = ($logo == 'custom') ? $custom_logo : $logo_default;
	$text           = get_theme_mod('bangla_preloader_text', 1);
	$custom_text    = get_theme_mod('bangla_preloader_custom_text');
	$site_name      = get_bloginfo( 'name' );
	$default_text   = sprintf(esc_html__( 'Please Wait, %s is Loading...', 'bangla' ), $site_name );
	$animation      = get_theme_mod('bangla_preloader_animation', 1);
	$animation_html = '<div class="broxme_wp-spinner broxme_wp-spinner-three-bounce"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>';
	if ($animation) {
		$loading_html[] = $animation_html;
	}
	
	if ($text) {
		$loading_html[]   = $default_text;
	} elseif ($text == 'custom') {
		$loading_html[]   = $custom_text;
	}


	$settings = [
		'logo'        => ($logo) ? $final_logo : '',
		'loadingHtml' => implode( " ", $loading_html ),
	];


?>
<script type="text/javascript">
	window.loading_screen = window.pleaseWait(<?php echo json_encode($settings); ?>);
	window.onload=function(){
		window.setTimeout(function(){
		    loading_screen.finish();
		},3000);
	}
</script>
