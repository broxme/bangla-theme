<?php
/**
* @package   bangla
* @author    BroxMe Technology
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

	$container_media = [];
	$container_image = '';
	// Options
	$layout_c        = get_theme_mod('bangla_header_layout', 'horizontal-left');
	$layout_m        = get_post_meta( get_the_ID(), 'bangla_header_layout', true );
	$layout          = (!empty($layout_m) and $layout_m != 'default') ? $layout_m : $layout_c;
	
	$fullwidth       = get_theme_mod('bangla_header_fullwidth');
	$logo            = get_theme_mod('bangla_logo_default');
	$class           = array_merge(['broxme_wp-header', 'broxme_wp-visible@' . get_theme_mod('bangla_mobile_break_point', 'm')]);
	$search          = get_theme_mod( 'bangla_search_position', 'header');
	
	$transparent_c   = get_theme_mod( 'bangla_header_transparent');
	$transparent_m   = get_post_meta( get_the_ID(), 'bangla_header_transparent', true );
	$transparent     = (!empty($transparent_m)) ? $transparent_m : $transparent_c;
	
	$sticky_m        = get_post_meta( get_the_ID(), 'bangla_header_sticky', true );
	$sticky_c        = get_theme_mod( 'bangla_header_sticky' );
	$sticky          = (!empty($sticky_m)) ? $sticky_m : $sticky_c;;
	$cart            = get_theme_mod('bangla_woocommerce_cart');
	$menu_text       = get_theme_mod('bangla_mobile_menu_text');
	$offcanvas_mode  = get_theme_mod('bangla_mobile_offcanvas_mode', 'push');
	$shadow          = get_theme_mod('bangla_header_shadow', 'small');


	if ($layout_m) {
		$bg_style = get_post_meta( get_the_ID(), 'bangla_header_bg_style', true );
		$bg_style = ( !empty($bg_style) ) ? $bg_style : get_theme_mod( 'bangla_header_bg_style' );
		$width    = get_post_meta( get_the_ID(), 'bangla_header_width', true );
		$padding  = get_post_meta( get_the_ID(), 'bangla_header_padding', true );
		$text     = get_post_meta( get_the_ID(), 'bangla_header_txt_style', true );
		$text     = ( !empty($text) ) ? $text : get_theme_mod( 'bangla_header_txt_style' );
	} else {
	    $bg_style = get_theme_mod( 'bangla_header_bg_style' );
	    $width    = get_theme_mod( 'bangla_header_width' );
	    $padding  = get_theme_mod( 'bangla_header_padding' );
	    $text     = get_theme_mod( 'bangla_header_txt_style' );
	}

	if ($layout_m) {
	    $container_images = rwmb_meta( 'bangla_header_bg_img', "type=image_advanced&size=standard" );
	    foreach ( $container_images as $image ) { 
	        $container_image = esc_url($image["url"]);
	    }
	    $container_bg_img_pos    = get_post_meta( get_the_ID(), 'bangla_header_bg_img_position', true );
	    $container_bg_img_attach = get_post_meta( get_the_ID(), 'bangla_header_bg_img_fixed', true );
	    $container_bg_img_vis    = get_post_meta( get_the_ID(), 'bangla_header_bg_img_visibility', true );
	} else {
	    $container_image         = get_theme_mod( 'bangla_header_bg_img' );
	    $container_bg_img_pos    = get_theme_mod( 'bangla_header_bg_img_position' );
	}

	// Image
	if ($container_image &&  $bg_style == 'media') {
	    $container_media['style'][] = "background-image: url('{$container_image}');";
	    // Settings
	    $container_media['class'][] = 'broxme_wp-background-norepeat';
	    $container_media['class'][] = $container_bg_img_pos ? "broxme_wp-background-{$container_bg_img_pos}" : '';
	}

	// Container
	$container            = ['class' => ['broxme_wp-navbar-container', 'tm-primary-navbar']];
	$container['class'][] = ($bg_style && ($transparent == false or $transparent == 'no')) ? 'navbar-color-'.$bg_style : '';
	$class[]              = ($text) ? 'broxme_wp-'.$text : '';
	$class[]              = ($shadow) ? 'broxme_wp-box-shadow-'.$shadow : '';


	// Transparent
	if ($transparent != false and $transparent != 'no') {
	    $class[] = 'broxme_wp-header-transparent';
	    $container['class'][] = "broxme_wp-navbar-transparent broxme_wp-{$transparent}";
	}

	$navbar_attrs = [ 'class' => 'broxme_wp-navbar' ];

	
	if (is_admin_bar_showing()) {
		$offset = '32';
	} else {
		$offset = 0;
	}

	// Sticky
	if ($sticky != false and $sticky != 'no') {
	    $container['broxme_wp-sticky'] = json_encode(array_filter([
			'media'       => 768,
			'show-on-up'  => $sticky == 'smart',
			'animation'   => $transparent || $sticky == 'smart' ? 'broxme_wp-animation-slide-top' : '',
			'top'         => $transparent ? '!.js-sticky' : 1,
			'offset' 	  => $offset,
			'clsActive'   => 'broxme_wp-active broxme_wp-navbar-sticky',
			'clsInactive' => $transparent ? "broxme_wp-navbar-transparent broxme_wp-{$transparent}" : false,
	    ]));
	}
?>

<?php if ($transparent) : ?>
<div<?php echo bangla_helper::attrs(['class' => 'js-sticky']) ?>>
<?php endif; ?>
	<div<?php echo bangla_helper::attrs(['class' => $class]) ?>>
		<?php if ($layout == 'horizontal-left' or $layout == 'horizontal-center' or $layout == 'horizontal-right') : ?>
		    <div<?php echo bangla_helper::attrs($container, $container_media) ?>>
		        <div class="broxme_wp-container <?php echo ($fullwidth) ? 'broxme_wp-container-expand' : '' ?>">
		            <nav<?php echo bangla_helper::attrs($navbar_attrs) ?>>

		                <div class="broxme_wp-navbar-left">
		                    <?php get_template_part( 'template-parts/logo-default' ); ?>
		                    <?php if ($layout == 'horizontal-left' and has_nav_menu('primary')) : ?>
		                        <?php get_template_part( 'template-parts/menu-primary' ); ?>
		                        <?php if ($search == 'menu' ) : ?>
		                        	<div class="broxme_wp-navbar-item">
		                            	<?php get_template_part( 'template-parts/search' ); ?>
		                            </div>
		                        <?php endif ?>
		                    <?php endif ?>
		                </div>

		                <?php if ($layout == 'horizontal-center' && has_nav_menu('primary')) : ?>
		                <div class="broxme_wp-navbar-center">
		                    <?php get_template_part( 'template-parts/menu-primary' ); ?>
		                    <?php if ($search == 'menu' ) : ?>
		                    	<div class="broxme_wp-navbar-item">
		                        	<?php get_template_part( 'template-parts/search' ); ?>
		                        </div>
		                    <?php endif ?>
		                </div>
		                <?php endif ?>

		                <?php if (is_active_sidebar('headerbar') || $layout == 'horizontal-right' || $search == 'header' || has_nav_menu('primary') || $cart == 'header') : ?>
		                <div class="broxme_wp-navbar-right">
		                    <?php if ($layout == 'horizontal-right' && has_nav_menu('primary')) : ?>
		                        <?php get_template_part( 'template-parts/menu-primary' ); ?>
		                    <?php endif ?> 
							
		                    <?php if ($layout == 'horizontal-right' && $search == 'menu' ) : ?>
		                    	<div class="broxme_wp-navbar-item">
		                        	<?php get_template_part( 'template-parts/search' ); ?>
		                        </div>
		                    <?php endif ?>

							<?php if (is_active_sidebar('headerbar')) : ?>
								<div class="broxme_wp-navbar-item">
			                    	<?php dynamic_sidebar('headerbar') ?>
								</div>
							<?php endif; ?>

							<?php if (($layout == 'horizontal-left' || $layout == 'horizontal-center' || $layout == 'horizontal-right') && $search == 'header' ) : ?>
								<div class="broxme_wp-navbar-item">
							    	<?php get_template_part( 'template-parts/search' ); ?>
							    </div>
							<?php endif ?>

							<?php if (($layout == 'horizontal-left' || $layout == 'horizontal-center' || $layout == 'horizontal-right') && $cart == 'header' ) : ?>
								<div class="broxme_wp-navbar-item">
							    	<?php get_template_part('template-parts/woocommerce-cart'); ?>
							    </div>
							<?php endif ?>
		                </div>
		                <?php endif ?>
		            </nav>
		        </div>
		    </div>
			<?php //endif ?>		
		<?php elseif (in_array($layout, ['stacked-center-a', 'stacked-center-b', 'stacked-center-split'])) : ?>
		    <?php if ($layout != 'stacked-center-split' || $layout == 'stacked-center-a' && is_active_sidebar('headerbar')) : ?>
		    <div class="broxme_wp-headerbar-top">
		        <div class="broxme_wp-container<?php echo ($fullwidth) ? ' broxme_wp-container-expand' : '' ?>">

		            <div class="broxme_wp-text-center broxme_wp-position-relative">
		                <?php get_template_part( 'template-parts/logo-default' ); ?>
		               <?php if ($layout == 'stacked-center-a') : ?> 
		                <div>
		                	<img class="center-logo-art" src="<?php echo get_template_directory_uri(); ?>/images/header-art-01.svg" width="250">
		                </div>
		            	<?php endif; ?>
		            </div>

		            <?php if ($layout == 'stacked-center-a' && is_active_sidebar('headerbar')) : ?>
		            <div class="broxme_wp-headerbar-stacked broxme_wp-grid-medium broxme_wp-child-width-auto broxme_wp-flex-center broxme_wp-flex-middle broxme_wp-margin-medium-top" broxme_wp-grid>
		                <?php dynamic_sidebar('headerbar') ?>
		            </div>
		            <?php endif ?>

		        </div>
		    </div>
		    <?php endif ?>

		    <?php if (has_nav_menu('primary')) : ?>
		    <div<?php echo bangla_helper::attrs($container) ?>>

		        <div class="broxme_wp-container <?php echo ($fullwidth) ? 'broxme_wp-container-expand' : '' ?>">
		            <nav<?php echo bangla_helper::attrs($navbar_attrs) ?>>

		                <div class="broxme_wp-navbar-center">
		                    <?php get_template_part( 'template-parts/menu-primary' ); ?>
		                </div>

		            </nav>
		        </div>

		    </div>
		    <?php endif ?>

		    <?php if (in_array($layout, ['stacked-center-b', 'stacked-center-split']) && is_active_sidebar('headerbar')) : ?>
		    <div class="broxme_wp-headerbar-bottom">
		        <div class="broxme_wp-container <?php echo ($fullwidth) ? 'broxme_wp-container-expand' : '' ?>">
		            <div class="broxme_wp-grid-medium broxme_wp-child-width-auto broxme_wp-flex-center broxme_wp-flex-middle" broxme_wp-grid>
		                <?php dynamic_sidebar('headerbar') ?>
		            </div>
		        </div>
		    </div>
		    <?php endif ?>
		<?php elseif ($layout == 'stacked-left-a' || $layout == 'stacked-left-b') : ?>
		    <?php if ($logo || is_active_sidebar('headerbar')) : ?>
		    <div class="broxme_wp-headerbar-top">
		        <div class="broxme_wp-container <?php echo ($fullwidth) ? 'broxme_wp-container-expand' : '' ?> broxme_wp-flex broxme_wp-flex-middle">

		            <?php get_template_part( 'template-parts/logo-default' ); ?>

		            <?php if (is_active_sidebar('headerbar') or $search) : ?>
		            <div class="broxme_wp-margin-auto-left">
		                <div class="broxme_wp-grid-medium broxme_wp-child-width-auto broxme_wp-flex-middle" broxme_wp-grid>
		                    
							<?php if ($layout == 'stacked-left-a') : ?>
		                    	<?php dynamic_sidebar('headerbar') ?>
		                    <?php endif ?>
		                    

		                    <?php if ($search == 'header' ) : ?>
		                    	<div>
		                        	<?php get_template_part( 'template-parts/search' ); ?>
		                        </div>
		                    <?php endif ?>

	                    	<?php if ($cart == 'header' ) : ?>
								<div>
							    	<?php get_template_part('template-parts/woocommerce-cart'); ?>
							    </div>
							<?php endif ?>
		                </div>
		            </div>
		            <?php endif ?>

		        </div>
		    </div>
		    <?php endif ?>

		    <?php if (has_nav_menu('primary')) : ?>
			    <div<?php echo bangla_helper::attrs($container) ?>>
			        <div class="broxme_wp-container <?php echo ($fullwidth) ? 'broxme_wp-container-expand' : '' ?>">
			            <nav<?php echo bangla_helper::attrs($navbar_attrs) ?>>

			                <?php if ($layout == 'stacked-left-a') : ?>
			                <div class="broxme_wp-navbar-left">
			                    <?php get_template_part( 'template-parts/menu-primary' ); ?>

			                    <?php if ($search == 'menu' ) : ?>
			                    	<div class="broxme_wp-navbar-item">
			                        	<?php get_template_part( 'template-parts/search' ); ?>
			                        </div>
			                    <?php endif ?>
			                </div>
			                <?php endif ?>

			                <?php if ($layout == 'stacked-left-b') : ?>
			                <div class="broxme_wp-navbar-left broxme_wp-flex-auto">
			                    <?php get_template_part( 'template-parts/menu-primary' ); ?>

            					<?php if ($layout == 'stacked-left-b') : ?>
            						<div class="broxme_wp-margin-auto-left broxme_wp-navbar-item">
                                		<?php dynamic_sidebar('headerbar') ?>
                                	</div>
                                <?php endif ?>

			                    <?php if ($search == 'menu' ) : ?>
			                    	<div class="broxme_wp-margin-auto-left broxme_wp-navbar-item">
			                        	<?php get_template_part( 'template-parts/search' ); ?>
			                        </div>
			                    <?php endif ?>
			                </div>
			                <?php endif ?>

			            </nav>
			        </div>
			    </div>
		    <?php endif ?>
		<?php elseif ($layout == 'toggle-offcanvas' || $layout == 'toggle-modal') : ?>
		    <div<?php echo bangla_helper::attrs($container) ?>>
		        <div class="broxme_wp-container <?php echo ($fullwidth) ? 'broxme_wp-container-expand' : '' ?>">
		            <nav<?php echo bangla_helper::attrs($navbar_attrs) ?>>

		            <?php if ($logo) : ?>
		            <div class="broxme_wp-navbar-left">
		                <?php get_template_part( 'template-parts/logo-default' ); ?>
		            </div>
		            <?php endif ?>

		            <?php if (has_nav_menu('primary')) : ?>
		            <div class="broxme_wp-navbar-right">
		                <a class="broxme_wp-navbar-toggle" href="#" broxme_wp-toggle="target: !.broxme_wp-navbar-container + [broxme_wp-offcanvas], [broxme_wp-modal]">
		                    <?php if ($menu_text) : ?>
		                    <span class="broxme_wp-margin-small-right"><?php esc_html_e('Menu', 'bangla') ?></span>
		                    <?php endif ?>
		                    <div broxme_wp-navbar-toggle-icon></div>
		                </a>
		            </div>
		            <?php endif ?>

		            </nav>
		        </div>
		    </div>
			<?php if ($layout == 'toggle-offcanvas' && (has_nav_menu('primary') || is_active_sidebar('headerbar'))) : ?>
			    <div broxme_wp-offcanvas="flip: true" mode="<?php echo esc_html($offcanvas_mode); ?>" overlay>
			        <div class="broxme_wp-offcanvas-bar">

			            <?php
			            	if(has_nav_menu('primary')) {
			            		wp_nav_menu( array(
			            			'theme_location' => 'primary',
			            			'container'      => false,
			            			'items_wrap'     => '<ul id="%1$s" class="%2$s" broxme_wp-nav>%3$s</ul>',
			            			'menu_id'        => 'nav-offcanvas',
			            			'menu_class'     => 'broxme_wp-nav broxme_wp-nav-default broxme_wp-nav-parent-icon',
			            			'echo'           => true,
			            			'before'         => '',
			            			'after'          => '',
			            			'link_before'    => '',
			            			'link_after'     => '',
			            			'depth'          => 0,
			            			)
			            		); 
			            	}
			            ?>

    		            <?php if ($search == 'menu' ) : ?>
    		            	<div class="broxme_wp-margin-auto-left broxme_wp-navbar-item">
    		                	<?php get_template_part( 'template-parts/search' ); ?>
    		                </div>
    		            <?php endif ?>

	                    <?php if (is_active_sidebar('headerbar')) : ?>
	                    <div class="broxme_wp-margin-large-top">
	                        <?php dynamic_sidebar('headerbar') ?>
	                    </div>
	                    <?php endif ?>

	                    <?php if ($search == 'header' ) : ?>
	                    	<div class="broxme_wp-margin-auto-left broxme_wp-navbar-item">
	                        	<?php get_template_part( 'template-parts/search' ); ?>
	                        </div>
	                    <?php endif ?>

			        </div>
			    </div>
		    <?php elseif ($layout == 'toggle-modal' && (has_nav_menu('primary') || is_active_sidebar('headerbar'))) : ?>
			    <div class="broxme_wp-modal-full" broxme_wp-modal>
			        <div class="broxme_wp-modal-dialog broxme_wp-modal-body">
			            <button class="broxme_wp-modal-close-full" type="button" broxme_wp-close></button>
			            <div class="broxme_wp-flex broxme_wp-flex-center broxme_wp-flex-middle broxme_wp-text-center" broxme_wp-height-viewport>
			                <div>

			                    <?php
	        		            	if(has_nav_menu('primary')) {
	        		            		wp_nav_menu( array(
	        		            			'theme_location' => 'primary',
	        		            			'container'      => false,
	        		            			'items_wrap'     => '<ul id="%1$s" class="%2$s" broxme_wp-nav>%3$s</ul>',
	        		            			'menu_id'        => 'nav-offcanvas',
	        		            			'menu_class'     => 'broxme_wp-nav broxme_wp-nav-primary broxme_wp-nav-center broxme_wp-nav-parent-icon',
	        		            			'echo'           => true,
	        		            			'before'         => '',
	        		            			'after'          => '',
	        		            			'link_before'    => '',
	        		            			'link_after'     => '',
	        		            			'depth'          => 0,
	        		            			)
	        		            		); 
	        		            	}
	        		            ?>

	        		            <?php if ($search == 'menu' ) : ?>
	        		            	<div class="broxme_wp-margin-auto-left broxme_wp-navbar-item">
	        		                	<?php get_template_part( 'template-parts/search' ); ?>
	        		                </div>
	        		            <?php endif ?>

			                    <?php if (is_active_sidebar('headerbar')) : ?>
			                    <div class="broxme_wp-margin-large-top">
			                        <?php dynamic_sidebar('headerbar') ?>
			                    </div>
			                    <?php endif ?>

			                    <?php if ($search == 'header' ) : ?>
			                    	<div class="broxme_wp-margin-auto-left broxme_wp-navbar-item">
			                        	<?php get_template_part( 'template-parts/search' ); ?>
			                        </div>
			                    <?php endif ?>

			                </div>
			            </div>
			        </div>
			    </div>
			<?php endif ?>
		
		<?php elseif ($layout == 'side-left' || $layout == 'side-right') : ?>
			<?php 
				$sidebar_position = ($layout == 'side-left') ? ' broxme_wp-position-left' : ' broxme_wp-position-right';
				//$sidebar_class = [];
				$sidebar_class = ['class' => ['broxme_wp-position-fixed', 'broxme_wp-position-z-index', 'broxme_wp-padding', 'broxme_wp-width-medium']];
				$sidebar_class['class'][] = $sidebar_position;
				$sidebar_class['class'][] = ($bg_style) ? 'broxme_wp-background-'.$bg_style : '';
				$sidebar_class['class'][] = ($shadow) ? 'broxme_wp-box-shadow-'.$shadow : '';
			?>
		    <div<?php echo bangla_helper::attrs($sidebar_class, $container_media) ?>>
		        <div class="">
					
					<div class="">
			        	<?php if ($logo) : ?>
			        	<div class="broxme_wp-text-center">
			        	    <?php get_template_part( 'template-parts/logo-default' ); ?>
			        	</div>
			        	<?php endif ?>

			            <?php
			            	if(has_nav_menu('primary')) {
			            		wp_nav_menu( array(
			            			'theme_location' => 'primary',
			            			'container'      => false,
			            			'items_wrap'     => '<ul id="%1$s" class="%2$s" broxme_wp-nav>%3$s</ul>',
			            			'menu_id'        => 'nav-offcanvas',
			            			'menu_class'     => 'broxme_wp-nav broxme_wp-nav-default broxme_wp-nav-parent-icon broxme_wp-margin-medium-top',
			            			'echo'           => true,
			            			'before'         => '',
			            			'after'          => '',
			            			'link_before'    => '',
			            			'link_after'     => '',
			            			'depth'          => 0,
			            			)
			            		); 
			            	}
			            ?>

			            <?php if ($search == 'menu' ) : ?>
			            	<div class="broxme_wp-margin-auto-left broxme_wp-margin-medium-top">
			                	<?php get_template_part( 'template-parts/search' ); ?>
			                </div>
			            <?php endif ?>

	                </div>



                    <?php //if ($search == 'header' ) : ?>
                    	<div class="broxme_wp-side-bottom broxme_wp-text-uppercase broxme_wp-text-small broxme_wp-margin-large-top">

                    		<?php if (is_active_sidebar('headerbar')) : ?>
                    		<div class="broxme_wp-margin-medium-bottom">
                    		    <?php dynamic_sidebar('headerbar') ?>
                    		</div>
                    		<?php endif ?>
							
							<div class="broxme_wp-margin-small-bottom broxme_wp-grid-divider broxme_wp-grid-small" broxme_wp-grid>
	                    		<?php if ($cart == 'header' ) : ?>
	                    			<div class="tm-wpml">
	                    		    	<?php get_template_part('template-parts/toolbars/wpml'); ?>
	                    		    </div>
	                    		<?php endif ?>

	                    		<?php if ($cart == 'header' ) : ?>
	                    			<div class="">
	                    		    	<?php get_template_part('template-parts/woocommerce-cart'); ?>
	                    		    </div>
	                    		<?php endif ?>

			                    <?php if ($search == 'header' ) : ?>
			                    	<div class="">
			                        	<?php get_template_part( 'template-parts/search' ); ?>
			                        </div>
			                    <?php endif ?>
							</div>

                        	<?php if(get_theme_mod('bangla_copyright_text_custom_show')) : ?>
								<div class="copyright-txt"><?php echo wp_kses_post(get_theme_mod('bangla_copyright_text_custom')); ?></div>
							<?php else : ?>								
								<div class="copyright-txt">&copy; <?php esc_html_e('Copyright', 'bangla') ?> <?php echo esc_html(date("Y ")); ?> <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo( 'name' );?>"> <?php echo esc_html(bloginfo('name')); ?> </a></div>
							<?php endif; ?>
                        </div>
                    <?php //endif ?>



		        </div>
		    </div>
		<?php endif ?>

		<?php if ($shadow == 'special') : ?>
			<div class="broxme_wp-header-shadow">
				<div></div>
			</div>
		<?php endif; ?>
	</div>
<?php if ($transparent) : ?>
</div>
<?php endif; ?>