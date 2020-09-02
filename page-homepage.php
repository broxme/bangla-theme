<?php 
/* Template Name: Homepage */

get_header();

$bg_style         = get_theme_mod( 'bangla_body_bg_style');
//$mainbody_width = get_theme_mod( 'bangla_body_width');
$text             = get_theme_mod( 'bangla_body_txt_style' );
$id 			  = 'broxme_wp-main';


$class            = ['broxme_wp-section', 'broxme_wp-padding-remove-vertical'];
$class[]          = ($bg_style) ? 'broxme_wp-section-'.$bg_style : '';
$class[]          = ($text) ? 'broxme_wp-'.$text : '';


?>



<div<?php echo bangla_helper::attrs(['id' => $id, 'class' => $class]); ?>>
	<div class="">
		<main class="broxme_wp-home">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<?php the_content(); ?>

			<?php endwhile; endif; ?>
		</main> <!-- end main -->

	</div> <!-- end container -->
</div> 
	
<?php get_footer(); ?>