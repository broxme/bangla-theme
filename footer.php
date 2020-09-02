<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bangla
 */

	$layout        = get_theme_mod('bangla_global_layout', 'full');
	$cookie_bar    = get_theme_mod( 'bangla_cookie');
	$totop_show    = get_theme_mod('bangla_totop_show', 1);
	$totop_align   = get_theme_mod('bangla_totop_align', 'left');
	$totop_radius  = get_theme_mod('bangla_totop_radius', 'circle');
	$totop_bg      = get_theme_mod('bangla_totop_bg_style', 'secondary'); // TODO
	$totop_class   = ['broxme_wp-totop-scroller', 'broxme_wp-totop', 'broxme_wp-position-medium', 'broxme_wp-position-fixed'];
	$totop_class[] = ($totop_align) ? 'broxme_wp-position-bottom-'.$totop_align : 'broxme_wp-position-bottom-left';
	$totop_class[] = ($totop_radius) ? 'broxme_wp-border-'.$totop_radius : '';
	$totop_class[] = ($totop_bg) ? 'broxme_wp-background-'.$totop_bg : 'broxme_wp-background-secondary';
	$totop_class[] = ($totop_bg == 'default' or $totop_bg == 'muted') ? 'broxme_wp-dark' : 'broxme_wp-light';

	?>
	
	<?php if (!is_page_template( 'page-blank.php' ) and !is_404()) : ?>
		<?php get_template_part( 'template-parts/bottom' ); ?>
		<?php get_template_part( 'template-parts/copyright' ); ?>
	<?php endif; ?>
	

	<?php if ($layout == 'boxed') : ?>
		</div><!-- #margin -->
	</div><!-- #tm-page -->
	<?php endif; ?>

	<?php get_template_part( 'template-parts/fixed-left' ); ?>	
	<?php get_template_part( 'template-parts/fixed-right' ); ?>


	<?php if($totop_show and !is_page_template( 'page-blank.php' )): ?>
		<a <?php echo bangla_helper::attrs(['class' => $totop_class]); ?> href="#"  broxme_wp-totop broxme_wp-scroll></a>
	<?php endif; ?>

    <?php if ($cookie_bar) : ?>
		<?php get_template_part('template-parts/cookie-bar'); ?>
	<?php endif ?>

	<?php wp_footer(); ?>

</body>
</html>
