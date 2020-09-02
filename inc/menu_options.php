<?php
// add custom menu fields to menu
add_filter( 'wp_setup_nav_menu_item', 'bangla_add_custom_nav_fields' );

function bangla_add_custom_nav_fields( $menu_item ) {
    $menu_item->icon                = get_post_meta( $menu_item->ID, '_menu_item_icon', true );
    $menu_item->columns             = get_post_meta( $menu_item->ID, '_menu_item_columns', true );
    $menu_item->full_width          = get_post_meta( $menu_item->ID, '_menu_item_full_width', true );
    $menu_item->style_position      = get_post_meta( $menu_item->ID, '_menu_item_style_position', true );
    $menu_item->dropdown_child      = get_post_meta( $menu_item->ID, '_menu_item_dropdown_child', true );
    $menu_item->dropdown_background = get_post_meta( $menu_item->ID, '_menu_item_dropdown_background', true );

    if (is_admin()) wp_enqueue_media();

    return $menu_item;
}

// save menu custom fields
add_action( 'wp_update_nav_menu_item', 'bangla_update_custom_nav_fields', 10, 3 );

function bangla_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
    $check = array('icon', 'columns', 'full_width', 'style_position','dropdown_child','dropdown_background');
    foreach ( $check as $key ) {
        if (!isset($_POST['menu-item-'.$key][$menu_item_db_id])){
            if (!isset($args['menu-item-'.$key])) {
                $value = "";
            }
            else {
                $value = $args['menu-item-'.$key];
            }
        } else {
            $value = $_POST['menu-item-'.$key][$menu_item_db_id];
        }

        if ($value) {
            update_post_meta( $menu_item_db_id, '_menu_item_'.$key, $value );
        }
        else {
            delete_post_meta( $menu_item_db_id, '_menu_item_'.$key );
        }
    }
}

// edit menu walker
add_filter( 'wp_edit_nav_menu_walker', 'bangla_menu_edit_walker', 10, 2 );

function bangla_menu_edit_walker($walker = '', $menu_id = '') {
    $menu_locations = get_nav_menu_locations();
    if($menu_id != isset($menu_locations['primary'])) { 
        return 'Walker_Nav_Menu_Edit';
    } else {
        return 'bangla_Walker_Nav_Menu_Edit'; 
    }
}

// Create HTML list of nav menu input items.
// Extend from Walker_Nav_Menu class
class bangla_Walker_Nav_Menu_Edit extends Walker_Nav_Menu  {
    /**
     * @see Walker_Nav_Menu::start_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     */
    function start_lvl( &$output, $depth = 0, $args = array() ) {
    }

    /**
     * @see Walker_Nav_Menu::end_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     */
    function end_lvl( &$output, $depth = 0, $args = array() ) {
    }

    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param object $args
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $_wp_nav_menu_max_depth;
        $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $item_id = $item->ID;
        $removed_args = array(
            'action',
            'customlink-tab',
            'edit-menu-item',
            'menu-item',
            'page-tab',
            '_wpnonce',
        );
        ob_start();
        $original_title = '';
        if ( 'taxonomy' == $item->type ) {
            $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
            if ( is_wp_error( $original_title ) )
                $original_title = false;
        } elseif ( 'post_type' == $item->type ) {
            $original_object = get_post( $item->object_id );
            if ( $original_object ) {
                $original_title = $original_object->post_title;
            }
        }

        $classes = array(
            'menu-item menu-item-depth-' . $depth,
            'menu-item-' . esc_attr( $item->object ),
            'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && esc_attr($item_id) == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
        );

        $title = $item->title;

        if ( ! empty( $item->_invalid ) ) {
            $classes[] = 'menu-item-invalid';
            /* translators: %s: title of menu item which is invalid */
            $title = sprintf( esc_html__( '%s (Invalid)', 'bangla' ), $item->title );
        } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
            $classes[] = 'pending';
            /* translators: %s: title of menu item in draft status */
            $title = sprintf( esc_html__('%s (Pending)', 'bangla'), $item->title );
        }

        $title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

        ?>
    <li id="menu-item-<?php echo esc_attr($item_id); ?>" class="<?php echo implode( ' ', $classes ); ?>">
    <dl class="menu-item-bar">
        <dt class="menu-item-handle">
            <span class="item-title"><?php echo esc_html( $title ); ?></span>
            <span class="item-controls">
                <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
                <span class="item-order hide-if-js">
                    <a href="<?php
                        echo wp_nonce_url(
                            esc_url( add_query_arg(
                                array(
                                    'action' => 'move-up-menu-item',
                                    'menu-item' => esc_attr($item_id),
                                ),
                                esc_url( remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) ) )
                            ) ),
                            'move-menu_item'
                        );
                        ?>" class="item-move-up"><abbr title="Move up">&#8593;</abbr></a>
                    |
                    <a href="<?php
                        echo wp_nonce_url(
                            esc_url( add_query_arg(
                                array(
                                    'action' => 'move-down-menu-item',
                                    'menu-item' => esc_attr($item_id),
                                ),
                                esc_url( remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) ) )
                            ) ),
                            'move-menu_item'
                        );
                        ?>" class="item-move-down"><abbr title="Move down">&#8595;</abbr></a>
                </span>
                <a class="item-edit" id="edit-<?php echo esc_attr($item_id); ?>" title="Edit Menu Item" href="<?php
                    echo ( isset( $_GET['edit-menu-item'] ) && esc_attr($item_id) == $_GET['edit-menu-item'] )
                        ? admin_url( 'nav-menus.php' )
                        : esc_url( add_query_arg( 'edit-menu-item', esc_attr($item_id),
                            esc_url( remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . esc_attr($item_id) ) ) ) ) );
                    ?>"><?php echo 'Edit Menu Item'; ?></a>
            </span>
        </dt>
    </dl>

    <div class="menu-item-settings" id="menu-item-settings-<?php echo esc_attr($item_id); ?>">
        <?php if ( 'custom' == $item->type ) : ?>
            <p class="field-url description description-wide">
                <label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
                    <?php echo esc_html_x( 'URL', 'backend', 'bangla' ); ?><br />
                    <input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
                </label>
            </p>
        <?php endif; ?>
        <p class="description description-wide">
            <label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
                <?php echo esc_html_x( 'Navigation Label', 'backend', 'bangla' ); ?><br />
                <input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
            </label>
        </p>
        <p class="field-title-attribute field-attr-title description description-wide">
            <label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
                <?php echo esc_html_x( 'Title Attribute', 'backend', 'bangla' ); ?><br />
                <input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
            </label>
        </p>

        <p class="field-link-target description">
            <label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
                <input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
                <?php echo esc_html_x( 'Open link in a new tab', 'backend', 'bangla' ); ?>
            </label>
        </p>

        <p class="field-css-classes description description-thin">
            <label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
                <?php echo esc_html_x( 'CSS Classes (optional)', 'backend', 'bangla' ); ?><br />
                <input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
            </label>
        </p>
        <p class="field-xfn description description-thin">
            <label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
                <?php echo esc_html_x( 'Link Relationship (XFN)', 'backend', 'bangla' ); ?><br />
                <input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
            </label>
        </p>

        <?php
        /* New fields insertion starts here */
        ?>
        <p class="description description-wide bangla-menu-setting-icon">
            <label for="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>">
                <?php echo esc_html_x( 'UiKit Icon', 'backend', 'bangla' ); ?><br />
                <input type="text" id="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-icon"
                        name="menu-item-icon[<?php echo esc_attr($item_id); ?>]"
                       data-name="menu-item-icon[<?php echo esc_attr($item_id); ?>]"
                       value="<?php echo esc_attr( $item->icon ); ?>" placeholder="home" />
                <span><?php printf(esc_html_x('Input uikit icon class to show this menu icon. You can see %s here. For example: home, cart, mail', 'backend', 'bangla' ), '<a target="_blank" href="https://getuikit.com/docs/icon#library">Uikt Icon</a>') ?></span>
            </label>
        </p>
       
        <p class="description description-thin bangla-menu-setting-columns">
            <label for="edit-menu-item-columns-<?php echo esc_attr($item_id); ?>">
                <?php echo esc_html_x( 'Dropdown Columns', 'backend', 'bangla' ); ?><br />
                <select id="edit-menu-item-columns<?php echo esc_attr($item_id); ?>"
                             name="menu-item-columns[<?php echo esc_attr($item_id); ?>]"
                         data-name="menu-item-columns[<?php echo esc_attr($item_id); ?>]"
                        class="widefat">
                        <option value="1" <?php selected($item->columns, '1');?>>1</option>
                        <option value="2" <?php selected($item->columns, '2');?>>2</option>
                        <option value="3" <?php selected($item->columns, '3');?>>3</option>
                        <option value="4" <?php selected($item->columns, '4');?>>4</option>
                        <option value="5" <?php selected($item->columns, '5');?>>5</option>
                        <option value="6" <?php selected($item->columns, '6');?>>6</option>
                    </select>
            </label>
        </p>
        <p class="description description-thin bangla-menu-setting-fullwidth">
            <label for="edit-menu-item-full_width-<?php echo esc_attr($item_id); ?>">
                <?php echo esc_html_x( 'Dropdown Style', 'backend', 'bangla' ); ?><br />             
            <select id="edit-menu-item-full_width<?php echo esc_attr($item_id); ?>"
                             name="menu-item-full_width[<?php echo esc_attr($item_id); ?>]"
                         data-name="menu-item-full_width[<?php echo esc_attr($item_id); ?>]"
                        class="widefat">
                        <option value="" <?php if(!esc_attr($item->full_width)){echo 'selected="selected"';} ?>>Classic</option>
                        <option value="1" <?php if(esc_attr($item->full_width) == 1){echo 'selected="selected"';} ?>>Justify</option>
                        <option value="2" <?php if(esc_attr($item->full_width) == 2){echo 'selected="selected"';} ?>>Fullwidth</option>
                    </select>
            </label>
        </p>
        <p class="description description-thin bangla-menu-setting-alignment">
            <label for="edit-menu-item-style_position-<?php echo esc_attr($item_id); ?>">
                <?php echo esc_html_x( 'Dropdown Alignment', 'backend', 'bangla' ); ?><br />             
                <select id="edit-menu-item-style_position<?php echo esc_attr($item_id); ?>"
                                 name="menu-item-style_position[<?php echo esc_attr($item_id); ?>]"
                             data-name="menu-item-style_position[<?php echo esc_attr($item_id); ?>]"
                            class="widefat">
                    <option value="bottom-left" <?php if(esc_attr($item->style_position) == "bottom-left"){echo 'selected="selected"';} ?>>Left</option>
                    <option value="bottom-center" <?php if(esc_attr($item->style_position) == "bottom-center"){echo 'selected="selected"';} ?>>Center</option>
                    <option value="bottom-right" <?php if(esc_attr($item->style_position) == "bottom-right"){echo 'selected="selected"';} ?>>Right</option>

                </select>
            </label>
        </p>
        <br/>
        <div class="description description-wide bangla-menu-setting-background">
            <label for="edit-menu-item-dropdown_background-<?php echo esc_attr($item_id); ?>">
                <?php echo esc_html_x( 'Dropdown Menu Background', 'backend', 'bangla' ); ?><br />  
            </label>

            <div class="attachment-media-view">
                <div class="placeholder" id="image_preview_<?php echo esc_attr($item_id); ?>">
                    <?php if (isset($item->dropdown_background) && $item->dropdown_background != ''): ?>
                        <a href="" class="remove_image" data-id="<?php echo esc_attr($item_id); ?>"><span class="dashicons dashicons-dismiss"></span></a>
                        <img  style="max-width: 388px" src="<?php echo esc_url($item->dropdown_background) ?>" />
                    <?php else : ?>
                    <?php esc_html_e('No image selected', 'bangla'); ?>
                    <?php endif; ?> 
                </div>
                <div class="actions">
                    <input type="hidden" name="menu-item-dropdown_background[<?php echo esc_attr($item_id); ?>]" value="<?php echo ($item->dropdown_background) ? esc_url($item->dropdown_background) : '' ?>" id="image_link_<?php echo esc_attr($item_id); ?>" />
                    <button type="button" class="button upload-button background_dropdown_menu" data-id="<?php echo esc_attr($item_id); ?>">Select Background</button>
                    <div style="clear:both"></div>
                </div>
            </div>

            
        </div>
        <p class="description description-thin bangla-menu-setting-dropdown-classic">
            <label for="edit-menu-item-dropdown_child-<?php echo esc_attr($item_id); ?>">
                <?php echo esc_html_x( 'Dropdown Classic', 'backend', 'bangla' ); ?><br />             
            <select id="edit-menu-item-dropdown_child<?php echo esc_attr($item_id); ?>"
                             name="menu-item-dropdown_child[<?php echo esc_attr($item_id); ?>]"
                         data-name="menu-item-dropdown_child[<?php echo esc_attr($item_id); ?>]"
                        class="widefat">
                        <option value="" <?php if(!esc_attr($item->dropdown_child)){echo 'selected="selected"';} ?>>No</option>
                        <option value="1" <?php if(esc_attr($item->dropdown_child)){echo 'selected="selected"';} ?>>Yes</option>
                    </select>
            </label>
        </p>
        <br>
        <p class="field-move hide-if-no-js description description-wide">
            <label>
                <span><?php echo esc_html_x( 'Move', 'backend', 'bangla' ); ?></span>
                <a href="#" class="menus-move menus-move-up" data-dir="up"><?php echo esc_html_x( 'Up one', 'backend', 'bangla' ); ?></a>
                <a href="#" class="menus-move menus-move-down" data-dir="down"><?php echo esc_html_x( 'Down one', 'backend', 'bangla' ); ?></a>
                <a href="#" class="menus-move menus-move-left" data-dir="left"></a>
                <a href="#" class="menus-move menus-move-right" data-dir="right"></a>
                <a href="#" class="menus-move menus-move-top" data-dir="top"><?php echo esc_html_x( 'To the top', 'backend', 'bangla' ); ?></a>
            </label>
        </p>
         
        <br/>
        <?php
        /* New fields insertion ends here */
        ?>
        <div style="clear:both;"></div>
       
        <?php
        // Add this directly after the description paragraph in the start_el() method
        do_action( 'wp_nav_menu_item_custom_fields', esc_attr($item_id), $item, $depth, $args );
        // end added section
        ?>

        <div class="menu-item-actions description-wide submitbox">
            <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
            <p class="link-to-original">
                <?php printf( 'Original: %s', '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
            </p>
            <?php endif; ?>
            <a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
                echo wp_nonce_url(
                    esc_url( add_query_arg(
                        array(
                            'action' => 'delete-menu-item',
                            'menu-item' => esc_attr($item_id),
                        ),
                        esc_url( remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) ) )
                    ) ),
                    'delete-menu_item_' . esc_attr($item_id)
                ); ?>"><?php echo 'Remove'; ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr($item_id); ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => esc_attr($item_id), 'cancel' => time()), esc_url( remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) ) );
            ?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php echo 'Cancel'; ?></a>
        </div>

        <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item_id); ?>" />
        <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
        <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
        <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
        <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
        <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
        <div class="clear"></div>
    </div><!-- .menu-item-settings-->
    <ul class="menu-item-transport"></ul>
    </li>
    <?php
        $output .= ob_get_clean();
    }
}