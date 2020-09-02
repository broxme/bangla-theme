<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package bangla
 */

get_header(); ?>



<div<?php echo bangla_helper::section(); ?>>
	<div<?php echo bangla_helper::container(); ?>>
		<div<?php echo bangla_helper::grid('broxme_wp-flex broxme_wp-flex-middle'); ?>>
			
			<div class="broxme_wp-width-expand">
				<main class="broxme_wp-content">

					<section class="error-404-section not-found">


						<div class="broxme_wp-vertical-align-middle broxme_wp-margin-large-bottom broxme_wp-margin-large-top broxme_wp-background-default broxme_wp-padding-large broxme_wp-margin-auto">

							<h1><?php esc_html_e("404", "bangla") ?></h1>
							<h3><?php esc_html_e("Page Doesn't Exists", "bangla") ?></h3>

							<p class="broxme_wp-margin-medium-top"><?php 
								$err_history_link = '<a href="javascript:history.go(-1)">'.esc_html__("Go back", "bangla").'</a>';
								$err_home_link = '<a href="'.home_url('/').'">'.get_bloginfo('name').'</a>';

								printf(esc_html__("The Page you are looking for doesn't exist or an other error occurred. %s or head over to %s %s homepage to choose a new direction.", "bangla"), $err_history_link , '<br class="broxme_wp-visible@l">' , $err_home_link ); ?></p>

						</div>

					</section><!-- .error-404 -->

				</main><!-- #main -->
			</div><!-- #primary -->
		</div>
	</div>
</div>

<?php
get_footer();
