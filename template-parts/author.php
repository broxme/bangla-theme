<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package bangla
 */

$author_desc = get_the_author_meta('description');
$archive_page = is_archive();

?>

<?php if(get_theme_mod('bangla_author_info', 1) && !empty($author_desc)) { ?>
	
	<?php if (!$archive_page) : ?>
		<hr class="broxme_wp-margin-large-top broxme_wp-margin-large-top broxme_wp-margin-large-bottom">
	<?php endif; ?>

	<div id="author-info" class="broxme_wp-clearfix">
	    <div class="author-image broxme_wp-float-left broxme_wp-margin-right">
		    	<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta( 'ID' ))); ?>"><?php echo get_avatar( esc_attr(get_the_author_meta('user_email')), '80', '' ); ?></a>
		    </div>   
		    <div class="author-bio">
		       <h4 class="broxme_wp-margin-small-bottom"><?php esc_html_e('About', 'bangla'); ?> <?php the_author(); ?></h4>
		        <?php the_author_meta('description'); ?>
		    </div>
	</div>

	<hr class="broxme_wp-margin-large-top broxme_wp-margin-large-bottom">

<?php } ?>