<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bangla
 */

// Those settings comes from customizer for control boxed layout + preloader loading
$layout        = get_theme_mod('bangla_global_layout', 'full');
$padding       = get_theme_mod('bangla_global_padding');
$boxed_class   = ['broxme_wp-page'];
$boxed_class[] = $padding ? 'broxme_wp-page-padding' : '';
$preloader     = get_theme_mod('bangla_preloader');

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php endif; ?>
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">


<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    
    <?php if ($preloader) : ?>
		<?php get_template_part('template-parts/preloader'); ?>
	<?php endif ?>
	
	<?php // this condition for boxed mode layout those started div end in end of the footer.php file ?>
	<?php if ($layout == 'boxed') : ?>
	<div<?php echo bangla_helper::attrs(['class' => $boxed_class]) ?>>
	    <div <?php echo ($layout == 'boxed') ? 'class="broxme_wp-margin-auto"' : '' ?>>
	<?php endif ?>
			
			<?php // If you select page template blank so this condition will be activate ?>
			<?php if (!is_page_template( 'page-blank.php' ) and !is_404()) : ?>
				<?php get_template_part('template-parts/drawer');	?>
				
				<div class="tm-header-wrapper">
					<?php get_template_part( 'template-parts/toolbar' ); ?>

					
					<?php get_template_part('template-parts/headers/header-mobile'); ?>
					
					<?php get_template_part('template-parts/headers/header-default'); ?>
				</div>

				<?php // this is page title that show after header and top of body section ?>
				<?php if (!is_front_page() and !is_page_template( 'page-homepage.php' )) : ?>
					<?php get_template_part('template-parts/titlebar');	?>	
				<?php endif; ?>
				
				<?php get_template_part('template-parts/slider');	?>
			<?php endif; ?>