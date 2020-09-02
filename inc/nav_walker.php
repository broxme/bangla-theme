<?php
/*
 *  Custom Menu Walker.
 */
class bangla_menu_walker extends Walker_Nav_Menu {
    var $has_child = false;
    public function start_lvl(&$output, $depth = 0, $args = array()) {      
        $output .= '<ul>';
    }

    public function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= '</ul>';
        if($this->has_child  && $depth == 0){
            $this->has_child = FALSE;
            $output .= '</div></div></div>';
        }
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $data    = array();
        $class   = '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        if($classes)
            $class = trim(preg_replace('/menu-item(.+)/', '', implode(' ', $classes)));
        //new class
        $classes = array();
         $data['style'] = '';
        if($depth == 0 && $args->walker->has_children){
            $classes[] ='broxme_wp-parent';
            $data['data-menu-columns'] = $item->columns ? $item->columns : 1;
            $classes[] = 'has_columns';
           if($item->dropdown_background){
               $data['data-style'] = 'background-image: url(\''.$item->dropdown_background.'\')';
           }
        }
       
        if($item->current || $item->current_item_parent || $item->current_item_ancestor) {
            $classes[] = ' broxme_wp-active';
        }
        if($item->dropdown_child && $depth > 0) {
            $classes[] = ' broxme_wp-parent sub-dropdown';
        }
        // set id
        $data['data-id'] = $item->ID;

        // is current item ?
        if (in_array('current-menu-item', $classes) || in_array('current_page_item', $classes)) {
            $data['data-menu-active'] = 2;

        // home/fronpage item
        } elseif (preg_replace('/#(.+)$/', '', $item->url) == 'index.php' && (is_home() || is_front_page())) {
            $data['data-menu-active'] = 2;
        }      
        if($item->full_width){
              $data['full_width'] = $item->full_width;
        } elseif($item->style_position) {
            if ($item->style_position == 'bottom-left') {
                $data_uk_dropdown = (is_rtl()) ? 'bottom-right' : 'bottom-left';  
            } elseif ($item->style_position == 'bottom-right') {
                $data_uk_dropdown = (is_rtl()) ? 'bottom-left' : 'bottom-right';  
            } else {
                $data_uk_dropdown = $item->style_position;
            }
            $data['dropdown_style'] =  ($data_uk_dropdown);     
        }
        $attributes = '';
        foreach ($data as $name => $value) {      
            $attributes .= sprintf(' %s="%s"', $name, esc_attr($value));
        }
        
        // create item output
        $id = apply_filters('nav_menu_item_id', '', $item, $args);
       
        if($classes) {
            $class .= implode(' ', $classes);                    
        }
        if($class) {
           $class = ' class="'.$class.'"';
        } else {
            $class = '';
        }  

        $output .= '<li'.(strlen($id) ? sprintf(' id="%s"', esc_attr($id)) : '').$attributes . $class.'>';

        // set link attributes
        $attributes = '';
        foreach (array('attr_title' => 'title', 'target' => 'target', 'xfn' => 'rel', 'url' => 'href') as $var => $attr) {
            if (!empty($item->$var)) {
                $attributes .= sprintf(' %s="%s"', $attr, esc_attr($item->$var));
            }
        }

        $classes     = trim(preg_replace('/menu-item(.+)/', '', implode(' ', $classes)));
        if (preg_match('/\.(gif|png|jpg|svg)$/i', $item->icon)) {
            $icon = "<img class=\"broxme_wp-responsive-height broxme_wp-margin-small-right\" src=\"{$item->icon}\" alt=\"{$item->title}\" />";
        } elseif ($item->icon) {
            $icon = "<span class=\"broxme_wp-margin-small-right\" broxme_wp-icon=\"icon: {$item->icon}\"></span>";
        } else {
            $icon = '';
        }
        // is separator ?
        if ($item->url == '#') {
            $isline = preg_match("/^\s*\-+\s*$/", $item->title);

            $type = "header";
            if ($isline) {
                $type = 'separator-line';
            } elseif ($item->hasChildren) {
                $type = 'separator-text';
            }

            $format     = '%s<a href="#" %s>%s</a>%s';
            $classes    = ' seperator';
            $attributes = ' class="'.$classes.'" data-type="'.$type.'"';
        } else {
            $format = '%s<a%s>%s</a>%s';
        }

        // create link output
        $item_output = sprintf($format, $args->before, $attributes, $icon.$args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after, $args->after);

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    public function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $output .= '</li>';
    }

    function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
        // attach to element so that it's available in start_el()
        $element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]);
        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}