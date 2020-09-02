<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bangla
 */

?>

<?php if (is_active_sidebar('fixed-left')) : ?>
<div id="broxme_wpFixedLeft" class="broxme_wp-position-center-left">
	<div class="broxme_wp-fixed-l-wrapper">
		<?php dynamic_sidebar('fixed-left'); ?>
	</div>
</div>
<?php endif; ?>