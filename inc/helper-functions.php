<?php
/**
 * helper functions class
 */
class bangla_helper {

    static $selfClosing = ['input'];


    /**
     * Renders a tag.
     *
     * @param  string $name
     * @param  array  $attrs
     * @param  string $text
     * @return string
     */
    public static function tag($name, array $attrs = [], $text = null) {
        $attrs = self::attrs($attrs);
        return "<{$name}{ $attrs }" . (in_array($name, self::$selfClosing) ? '/>' : ">$text</{$name}>");
    }

    /**
     * Renders a form tag.
     *
     * @param  array $tags
     * @param  array $attrs
     * @return string
     */
    public static function form($tags, array $attrs = []) {
        $attrs = self::attrs($attrs);
        return "<form{$attrs}>\n" . implode("\n", array_map(function($tag) {
            $output = self::tag($tag['tag'], array_diff_key($tag, ['tag' => null]));
            return $output;
        }, $tags)) . "\n</form>";
    }

    /**
     * Renders an image tag.
     *
     * @param  array|string $url
     * @param  array        $attrs
     * @return string
     */
    public static function image($url, array $attrs = []) {
        $url = (array) $url;
        $path = array_shift($url);
        $params = $url ? '?'.http_build_query(array_map(function ($value) {
            return is_array($value) ? implode(',', $value) : $value;
        }, $url)) : '';

        if (!isset($attrs['alt']) || empty($attrs['alt'])) {
            $attrs['alt'] = true;
        }

        $output = self::attrs(['src' => $path.$params], $attrs);

        return "<img{$output}>";
    }
    
    /**
     * Renders tag attributes.
     * @param  array $attrs
     * @return string
     */
    public static function attrs(array $attrs) {
        $output = [];

        if (count($args = func_get_args()) > 1) {
            $attrs = call_user_func_array('array_merge_recursive', $args);
        }

        foreach ($attrs as $key => $value) {

            if (is_array($value)) { $value = implode(' ', array_filter($value)); }
            if (empty($value) && !is_numeric($value)) { continue; }

            if (is_numeric($key)) {
               $output[] = $value;
            } elseif ($value === true) {
               $output[] = $key;
            } elseif ($value !== '') {
               $output[] = sprintf('%s="%s"', $key, htmlspecialchars($value, ENT_COMPAT, 'UTF-8', false));
            }
        }

        return $output ? ' '.implode(' ', $output) : '';
    }

    /**
     * automated section class, id, attributes generator based on var.
     * @param  string $name  section name here
     * @param  string $id    section id here
     * @param  string $class section extra class here
     * @return [type]        [description]
     */
    public static function section($name = '', $id = '', $class='') {
        $id             = ($id) ? 'broxme_wp-'.$id : false;
        $prefix         = 'bangla_';
        $section        = ($name) ? $prefix.$name : $prefix.'section';
        $name           = ($name) ? 'broxme_wp-'.$name : 'broxme_wp-section';
        $section_media  = [];
        $section_image  = '';
        $layout         = get_post_meta( get_the_ID(), $section.'_layout', true );
        $metabox_layout = (!empty($layout) and $layout != 'default') ? true : false;
        $position       = (get_post_meta( get_the_ID(), 'bangla_page_layout', true )) ? get_post_meta( get_the_ID(), 'bangla_page_layout', true ) : get_theme_mod( 'bangla_page_layout', 'sidebar-right' );

        if ($metabox_layout) {
            $bg_style = get_post_meta( get_the_ID(), $section.'_bg_style', true );
            $bg_style = ( !empty($bg_style) ) ? $bg_style : get_theme_mod( $section.'_bg_style' );
            $width    = get_post_meta( get_the_ID(), $section.'_width', true );
            $padding  = get_post_meta( get_the_ID(), $section.'_padding', true );
            $text     = get_post_meta( get_the_ID(), $section.'_txt_style', true );
        } else {
            $bg_style = get_theme_mod( $section.'_bg_style' );
            $width    = get_theme_mod( $section.'_width', 'default' );
            $padding  = get_theme_mod( $section.'_padding', 'medium' );
            $text     = get_theme_mod( $section.'_txt_style' );
        }

        if (is_array($class)) {
            $class = implode(' ', array_filter($class));
        }

            
        if ($metabox_layout) {
            $section_images = rwmb_meta( $section.'_bg_img', "type=image_advanced&size=standard" );
            foreach ( $section_images as $image ) { 
                $section_image = esc_url($image["url"]);
            }
            $section_bg_img_pos    = get_post_meta( get_the_ID(), $section.'_bg_img_position', true );
            $section_bg_img_attach = get_post_meta( get_the_ID(), $section.'_bg_img_fixed', true );
            $section_bg_img_vis    = get_post_meta( get_the_ID(), $section.'_bg_img_visibility', true );
        } else {
            $section_image         = get_theme_mod( $section.'_bg_img' );
            $section_bg_img_pos    = get_theme_mod( $section.'_bg_img_position' );
            $section_bg_img_attach = get_theme_mod( $section.'_bg_img_fixed' );
            $section_bg_img_vis    = get_theme_mod( $section.'_bg_img_visibility' );
        }

        // Image
        if ($section_image &&  $bg_style == 'media') {
            $section_media['style'][] = "background-image: url('{$section_image}');";
            // Settings
            $section_media['class'][] = 'broxme_wp-background-norepeat';
            $section_media['class'][] = $section_bg_img_pos ? "broxme_wp-background-{$section_bg_img_pos}" : '';
            $section_media['class'][] = $section_bg_img_attach ? "broxme_wp-background-fixed" : '';
            $section_media['class'][] = $section_bg_img_vis ? "broxme_wp-background-image@{$section_bg_img_vis}" : '';
        }

        $class   = [$name, 'broxme_wp-section', $class];
        $class[] = ($position == 'full' and $name == 'broxme_wp-main') ? 'broxme_wp-padding-remove-vertical' : ''; // section spacific override
        

        $class[] = ($bg_style) ? 'broxme_wp-section-'.$bg_style : '';
        $class[] = ($text) ? 'broxme_wp-'.$text : '';
        if ($padding != 'none') {
            $class[]       = ($padding) ? 'broxme_wp-section-'.$padding : '';
        } elseif ($padding == 'none') {
            $class[]       = ($padding) ? 'broxme_wp-padding-remove-vertical' : '';
        }

        $output = self::attrs(['id' => $id, 'class' => $class], $section_media);

        return $output;
    }

    /**
     * Auto container class
     * @param  string $class [description]
     * @return [type]        [description]
     */
    public static function container($class='') {
        
        $container_class    = ['broxme_wp-container', $class];
        
        $output = self::attrs(['class' => $container_class]);

        return $output;
    }

    /**
     * Auto div grid class system
     * @param  string $class [description]
     * @return [type]        [description]
     */
    public static function grid($class='') {
        
        $column_divider = get_theme_mod( 'bangla_sidebar_divider' );
        $gutter         = get_theme_mod( 'bangla_sidebar_gutter' );
        
        $grid_class     = ['broxme_wp-grid', $class];
        $grid_class[]   = ($gutter) ? 'broxme_wp-grid-'.$gutter : '';
        $grid_class[]   = ($column_divider && $gutter != 'collapse') ? 'broxme_wp-grid-divider' : '';

        $data_grid = '';
        
        $output = self::attrs(['class' => $grid_class, 'broxme_wp-grid' => true]);

        return $output;
    }

    /**
     * Sidebar class automization
     * @param  string $position [description]
     * @param  string $class    [description]
     * @return [type]           [description]
     */
    public static function sidebar($position = 'sidebar-right', $class = '', $width = '') {
        
        $position = ($position) ? $position : 'sidebar-right';

        $width      = ($width) ? $width : get_theme_mod( 'bangla_sidebar_width', '1-4' );
        $breakpoint = get_theme_mod( 'bangla_sidebar_breakpoint', 'm' );

        $class = ['broxme_wp-sidebar', $class];
        $class[] = ($width) ? 'broxme_wp-width-'.$width.'@'.$breakpoint.' broxme_wp-first-column' : 'broxme_wp-width-1-4@'.$breakpoint.' broxme_wp-first-column';
        $class[] = ($position == 'sidebar-left') ? 'broxme_wp-flex-first@'.$breakpoint.' broxme_wp-first-column' : '';
        
        $output = self::attrs(['class' => $class]);

        return $output;
    }

    /**
     * social icon generator from link
     * @param  [type] $link [description]
     * @return [type]       [description]
     */
    public static function icon($link) {
        static $icons;
        $icons = self::social_icons();

        if (strpos($link, 'mailto:') === 0) {
            return 'mail';
        } elseif (strpos($link, 'tel:') === 0) {
            return 'phone';
        }

        $icon = parse_url($link, PHP_URL_HOST);
        $icon = preg_replace('/.*?(plus\.google|[^\.]+)\.[^\.]+$/i', '$1', $icon);
        $icon = str_replace('plus.google', 'google-plus', $icon);

        if (!in_array($icon, $icons)) {
            $icon = 'social';
        }

        return $icon;
    }

    public static function social_icons() {
        $icons = ['behance', 'dribbble', 'facebook', 'flickr', 'foursquare', 'github', 'github-alt', 'google', 'google-plus', 'instagram', 'joomla', 'linkedin', 'pagekit', 'pinterest', 'soundcloud', 'tripadvisor', 'tumblr', 'twitter', 'uikit', 'vimeo', 'whatsapp', 'wordpress', 'xing', 'yelp', 'youtube'];

        return $icons;
    }

    /**
     * Returns url of no image
     * @param string $size
     * @return string
     */
    public static function no_image_url($size = "") {
        switch ($size) {
            case "150":
                return get_template_directory_uri() . '/images/no-image-150x150.jpg';
                break;
            case "300":
                return get_template_directory_uri() . '/images/no-image-300x300.jpg';
                break;
            default:
                return get_template_directory_uri() . '/images/no-image-450x450.jpg';
                break;
        }
    }

}