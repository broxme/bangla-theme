<?php 
    wp_reset_postdata();

	if(is_page() || is_page_template('page-blog.php')){
		// Page Sidebar 
		if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar(get_post_meta( get_the_ID(), 'bangla_sidebar', true )) );
	} elseif(is_search()){
		// Search Results Sidebar 
		if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Search Results Widgets') );
	} else {
		// Blog Sidebar && Default Sidebar
		if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Blog Widgets') );
	}

?>
	

