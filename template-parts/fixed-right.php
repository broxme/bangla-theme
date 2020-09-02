<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bangla
 */

?>

<?php if (is_active_sidebar('fixed-right')) : ?>
<div id="broxme_wpFixedRight" class="broxme_wp-position-center-right">
	<div class="broxme_wp-fixed-r-wrapper">
		<?php dynamic_sidebar('fixed-right'); ?>
	</div>
</div>
<?php endif; ?>