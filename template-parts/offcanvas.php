<?php
	$offcanvas_style = get_theme_mod( 'bangla_mobile_offcanvas_style');
	if ( $offcanvas_style == 'modal') {
		$menu_class = 'broxme_wp-nav broxme_wp-nav-primary broxme_wp-nav-center broxme_wp-nav-parent-icon';
	} elseif ($offcanvas_style == 'offcanvas') {
		$menu_class = 'broxme_wp-nav broxme_wp-nav-default broxme_wp-nav-parent-icon';
	} else {
		$menu_class = 'broxme_wp-nav broxme_wp-nav-parent-icon';
	}

	if (get_theme_mod('bangla_offcanvas_search', 1) and $offcanvas_style != 'modal') {
		echo '<div class="broxme_wp-panel offcanvas-search"><div class="panel-content">';
			get_search_form();
			echo '<hr>';
		echo '</div></div>';
	}
?>

<?php 
	if(has_nav_menu('primary') and !has_nav_menu('offcanvas')) {
		$navbar = wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'items_wrap'     => '<ul id="%1$s" class="%2$s" broxme_wp-nav>%3$s</ul>',
			'menu_id'        => 'nav-offcanvas',
			'menu_class'     => $menu_class,
			'echo'           => true,
			'before'         => '',
			'after'          => '',
			'link_before'    => '',
			'link_after'     => '',
			'depth'          => 0,
			)
		);
		$primary_menu = new bangla_nav_dom($navbar);
		echo 	$primary_menu->proccess();
	}
	elseif(has_nav_menu('offcanvas')) {
		$navbar = wp_nav_menu( array(
			'theme_location' => 'offcanvas',
			'container'      => false,
			'items_wrap'     => '<ul id="%1$s" class="%2$s" broxme_wp-nav>%3$s</ul>',
			'menu_id'        => 'nav-offcanvas',
			'menu_class'     => $menu_class,
			'echo'           => true,
			'before'         => '',
			'after'          => '',
			'link_before'    => '',
			'link_after'     => '',
			'depth'          => 0,
			)
		);
		$primary_menu = new bangla_nav_dom($navbar);
		echo 	$primary_menu->proccess();
	}
	else {
		echo '<div class="broxme_wp-panel"><div class="panel-content"><div class="broxme_wp-alert broxme_wp-alert-warning broxme_wp-margin-remove-bottom"><strong>NO MENU ASSIGNED</strong> <span>Go To Appearance > <a class="broxme_wp-link" href="'.admin_url('/nav-menus.php').'">Menus</a> and create a Menu</span></div></div></div>';
	}



	if (is_active_sidebar('offcanvas') and $offcanvas_style != 'modal') {
		echo '<hr>';
		echo '<div class="offcanvas-widgets">';
		dynamic_sidebar('offcanvas');
		echo '</div>';
	}

?>