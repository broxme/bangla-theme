<?php
$bangla_main_menu = get_theme_mod('bangla_menu_show', true);

if ($bangla_main_menu) {

	if(has_nav_menu('primary')) {
		$navbar = wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_id'        => 'nav',
			'menu_class'     => 'broxme_wp-navbar-nav',
			'echo'           => false,
			'before'         => '',
			'after'          => '',
			'link_before'    => '',
			'link_after'     => '',
			'depth'          => 0,
			'parent_id'      => 'tmMainMenu',
			)
		);

		$primary_menu = new bangla_nav_dom($navbar);
		echo 	$primary_menu->proccess();
	} else {
		echo '<ul class="no-menu broxme_wp-hidden-small"><li><a href="'.admin_url('/nav-menus.php').'"><strong>NO MENU ASSIGNED</strong> <span>Go To Appearance > Menus and create a Menu</span></a></li></ul>';
	} 
}