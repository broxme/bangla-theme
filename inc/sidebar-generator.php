<?php
/*
Plugin Name: Sidebar Generator
Plugin URI: http://www.getson.info
Description: This plugin generates as many sidebars as you need. Then allows you to place them on any page you wish. Version 1.1 now supports themes with multiple sidebars. 
Version: 1.1.0
Author: Kyle Getson
Author URI: http://www.kylegetson.com
Copyright (C) 2009 Kyle Robert Getson
*/

/*
Copyright (C) 2009 Kyle Robert Getson, kylegetson.com and getson.info

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class bangla_sidebar_generator {
	
	public function __construct(){
		add_action('init',array('bangla_sidebar_generator','init'));
		add_action('admin_menu',array('bangla_sidebar_generator','admin_menu'));
		add_action('admin_print_scripts', array('bangla_sidebar_generator','admin_print_scripts'));
		if ( current_user_can('manage_options') ){
			add_action('wp_ajax_add_sidebar', array('bangla_sidebar_generator','add_sidebar') );
			add_action('wp_ajax_remove_sidebar', array('bangla_sidebar_generator','remove_sidebar') );
		}
	}
	
	public static function init(){
		//go through each sidebar and register it
	    $sidebars = bangla_sidebar_generator::get_sidebars();
	    
	    if(is_array($sidebars)){
			foreach($sidebars as $sidebar){
				$sidebar_class = bangla_sidebar_generator::name_to_class($sidebar);
				register_sidebar(array(
					'name'          => sanitize_title($sidebar),
					'id'            => sanitize_title($sidebar),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h3>',
					'after_title'   => '</h3>',
		    	));
			}
		}
	}
	
	public static function admin_print_scripts(){
		wp_print_scripts( array( 'sack' ));
                $nonce = wp_create_nonce( 'manage_sidebar' );
		?>
			<script type="text/javascript">
				function add_sidebar( sidebar_name ) {
					
					var mysack = new sack("<?php echo site_url(); ?>/wp-admin/admin-ajax.php" );    
				
				  	mysack.execute = 1;
				  	mysack.method = 'POST';
                                        mysack.setVar( 'sidebar_generator_nonce', '<?php echo esc_html($nonce); ?>' );
				  	mysack.setVar( "action", "add_sidebar" );
				  	mysack.setVar( "sidebar_name", sidebar_name );
				  	//mysack.encVar( "cookie", document.cookie, false );
				  	mysack.onError = function() { alert('Ajax error. Cannot add sidebar' )};
				  	mysack.runAJAX();
					return true;
				}
				
				function remove_sidebar( sidebar_name ) {
					
					var mysack = new sack("<?php echo site_url(); ?>/wp-admin/admin-ajax.php" );    
				
				  	mysack.execute = 1;
				  	mysack.method = 'POST';
                                        mysack.setVar( 'sidebar_generator_nonce', '<?php echo esc_html($nonce); ?>' );
				  	mysack.setVar( "action", "remove_sidebar" );
				  	mysack.setVar( "sidebar_name", sidebar_name );
				  	//mysack.setVar( "row_number", num );
				  	//mysack.encVar( "cookie", document.cookie, false );
				  	mysack.onError = function() { alert('Ajax error. Cannot add sidebar' )};
				  	mysack.runAJAX();
					//alert('hi!:::'+sidebar_name);
					return true;
				}
			</script>
		<?php
	}
	
	public static function add_sidebar(){
		 if(check_admin_referer( 'manage_sidebar', 'sidebar_generator_nonce' ) != 1){
                    die("");
                }
		$sidebars = bangla_sidebar_generator::get_sidebars();
		$name     = str_replace(array("\n","\r","\t"),'',$_POST['sidebar_name']);
        preg_match("/[^a-zA-Z0-9\s]+/", $name, $output_array);
        if($output_array) 
            die("alert('".esc_html_x('Please give a suitable name for sidebar.', 'backend', 'bangla')."')");
                
		$id       = bangla_sidebar_generator::name_to_class($name);
		
                
		if ($name == null) {
			die("alert('".esc_html_x('Please give a suitable name for sidebar.', 'backend', 'bangla')."')");
		}
		if(isset($sidebars[$id])){
			die("alert('".esc_html_x('Sidebar already exists, please use a different name.', 'backend', 'bangla')."')");
		}
		
		$sidebars[$id] = $name;
		bangla_sidebar_generator::update_sidebars($sidebars);
		
		$js = "
			var tbl = document.getElementById('sbg_table');
			var lastRow = tbl.rows.length;
			// if there's no header row in the table, then iteration = lastRow + 1
			var iteration = lastRow;
			var row = tbl.insertRow(lastRow);
                        row.setAttribute('id','$id');

			// left cell
			var cellLeft = row.insertCell(0);
			var textNode = document.createTextNode('$name');
			cellLeft.appendChild(textNode);
			
			//middle cell
			var cellLeft = row.insertCell(1);
			var textNode = document.createTextNode('$id');
			cellLeft.appendChild(textNode);
			
			var cellLeft = row.insertCell(2);
			removeLink = document.createElement('a');
      		linkText = document.createTextNode('remove');
                        removeLink.setAttribute('href', 'javascript:void(0);');
                        removeLink.setAttribute('onclick', 'return remove_sidebar_link(\'$name\');');
			
                        removeLink.setAttribute('class','submitdelete deletion');
      		removeLink.appendChild(linkText);
      		cellLeft.appendChild(removeLink);
                
                deleteRow('no_sidebar');
		";
		
		die( "$js");
	}
	
	public static function remove_sidebar(){
                if(check_admin_referer( 'manage_sidebar', 'sidebar_generator_nonce' ) != 1){
                    die("");
                }		$sidebars = bangla_sidebar_generator::get_sidebars();
		$name     = str_replace(array("\n","\r","\t"),'',$_POST['sidebar_name']);
		$id       = bangla_sidebar_generator::name_to_class($name);
		if(!isset($sidebars[$id])){
			die("alert('".esc_html_x('Sidebar does not exist.', 'backend', 'bangla')."')");
		}
//		$row_number = $_POST['row_number'];
		unset($sidebars[$id]);
		bangla_sidebar_generator::update_sidebars($sidebars);
		$js = "deleteRow('$id');"
                ."if(document.getElementById('sbg_table').rows.length <= 1){"
                    . "var tbl = document.getElementById('sbg_table');
					var lastRow = tbl.rows.length;
					// if there's no header row in the table, then iteration = lastRow + 1
					var iteration = lastRow;
					var row = tbl.insertRow(lastRow);
		                        row.setAttribute('id','no_sidebar');

					// left cell
					var cellLeft = row.insertCell(0);
					var textNode = document.createTextNode('". esc_html_x('No custom sidebars generated', 'backend', 'bangla')."');
					cellLeft.appendChild(textNode);
                    cellLeft.setAttribute('colspan','3');"
                
                . '}';
                
		die($js);
	}
	
	public static function admin_menu(){
		add_theme_page(esc_html_x('Sidebars', 'backend', 'bangla'), esc_html_x('Sidebar Generator', 'backend', 'bangla'), 'manage_options', 'bangla-sidebar-generator', array('bangla_sidebar_generator','admin_page'));
	}
	
	public static function admin_page(){
		?>
		<script>
            function deleteRow(rowid)  
                {   
                    var row = document.getElementById(rowid);
                    if(row) row.parentNode.removeChild(row);
                }
			function remove_sidebar_link(name){
				answer = confirm("<?php echo esc_html_x('Are you sure you want to remove', 'backend', 'bangla'); ?> " + name + "?\n<?php echo esc_html_x('This will remove any widgets you have assigned to this sidebar.', 'backend', 'bangla'); ?>");
				if(answer){
					//alert('AJAX REMOVE');
					remove_sidebar(name);
				} else {
					return false;
				}
			}
			function add_sidebar_link(){
                var sidebar_name = prompt("<?php echo esc_html_x('Sidebar Name:', 'backend', 'bangla'); ?>","");
               
                if(sidebar_name){
                    sidebar_name = sidebar_name.trim();
                    var regrex = /[^a-zA-Z0-9\s]+/; 
                    if(sidebar_name && !regrex.test(sidebar_name)){
                        add_sidebar(sidebar_name);
                    }else{
                        alert('<?php echo esc_html_x('Please give a suitable name for sidebar.', 'backend', 'bangla');?>');
                    }
                     
                }
			}
		</script>
		<div class="wrap">
			<h2><?php echo esc_html_x('Sidebar Generator', 'backend', 'bangla'); ?> <a href="javascript:void(0);" onclick="return add_sidebar_link()" class="add-new-h2" title="<?php echo esc_html_x('Add a sidebar', 'backend', 'bangla'); ?>"><?php echo esc_html_x('Add Sidebar', 'backend', 'bangla'); ?></a></h2>
			<p>
				<?php echo esc_html_x('The sidebar name is for your use only. It will not be visible to any of your visitors. 
				A CSS class is assigned to each of your sidebar, use this styling to customize the sidebars.', 'backend', 'bangla'); ?>
			</p>
			<br />
			<table class="wp-list-table widefat fixed posts" id="sbg_table">
			<thead>
				<tr>
					<th><?php echo esc_html_x('Name', 'backend', 'bangla'); ?></th>
					<th><?php echo esc_html_x('CSS class', 'backend', 'bangla'); ?></th>
					<th><?php echo esc_html_x('Remove', 'backend', 'bangla'); ?></th>
				</tr>
			</thead>
				<?php
				$sidebars = bangla_sidebar_generator::get_sidebars();
				//$sidebars = array('bob','john','mike','asdf');
				if(is_array($sidebars) && !empty($sidebars)){
					$cnt=0;
					foreach($sidebars as $sidebar) {
						$alt = ($cnt%2 == 0 ? 'alternate' : '');
                        $id  = bangla_sidebar_generator::name_to_class($sidebar);
						?>
						<tr class="<?php echo esc_attr($alt); ?>" id="<?php echo esc_attr($id) ?>">
							<td><?php echo esc_html($sidebar); ?></td>
							<td><?php echo bangla_sidebar_generator::name_to_class($sidebar); ?></td>
							<td><a href="javascript:void(0);" onclick="return remove_sidebar_link('<?php echo esc_html($sidebar); ?>');" title="<?php echo esc_html_x('Remove this sidebar', 'backend', 'bangla'); ?>" class="submitdelete deletion"><?php echo esc_html_x('remove', 'backend', 'bangla'); ?></a></td>
						</tr><?php
						
						$cnt++;
					}
				}else{ ?>
                    <tr id="no_sidebar">
                        <td colspan="3"><?php echo esc_html_x('No custom sidebars generated', 'backend', 'bangla'); ?></td>
                    </tr>
					<?php
				}
				?>
			</table>
		</div>
		<?php
	}
	
	/**
	 * replaces array of sidebar names
	*/
	public static function update_sidebars($sidebar_array){
		$sidebars = update_option('sbg_sidebars',$sidebar_array);
	}	
	
	/**
	 * gets the generated sidebars
	*/
	public static function get_sidebars(){
		$sidebars = get_option('sbg_sidebars');
		return $sidebars;
	}
	public static function name_to_class($name){
		$class = str_replace(array(' ',',','.','"',"'",'/',"\\",'+','=',')','(','*','&','^','%','$','#','@','!','~','`','<','>','?','[',']','{','}','|',':',),'',$name);
		return $class;
	}
	
}
$sbg = new bangla_sidebar_generator;

?>