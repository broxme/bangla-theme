<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bangla
 */


$bangla_show_rev_slider = get_post_meta( get_the_ID(), 'bangla_show_rev_slider', true );
$bangla_rev_slider = get_post_meta( get_the_ID(), 'bangla_rev_slider', true );

if(shortcode_exists("rev_slider") && ($bangla_show_rev_slider == 'yes') && !is_search()) : ?>

<div class="slider-wrapper" id="broxme_wpSlider">
	<div>
		<section class="broxme_wp-slider broxme_wp-child-width-expand@s" broxme_wp-grid>
			<div>
				<?php echo(do_shortcode('[rev_slider '.$bangla_rev_slider.']')); ?>
			</div>
		</section>
	</div>
</div>

<?php endif; ?>
