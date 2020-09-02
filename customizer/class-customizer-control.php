<?php

function bangla_customize_register( $wp_customize ) {
    if( class_exists( 'WP_Customize_Control' ) ):

        // alert custom control
        class bangla_Customize_Alert_Control extends WP_Customize_Control {
            public $type = 'alert';
            public $text = '';
            public $alert_type = '';
            public function render_content() {
            ?>
            <label>
                <span class="bangla-alert <?php echo esc_html( $this->alert_type ); ?>"><?php echo esc_html( $this->text ); ?></span>
            </label>
            <?php
            }
        } 

        // Textarea custom control
        class bangla_Customize_Textarea_Control extends WP_Customize_Control {
            public $type = 'textarea';
     
            public function render_content() {
                ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
            </label>
            <?php
            }
        }

        // Select custom control with default option
        class bangla_Customize_Select_Control extends WP_Customize_Control {
            public $type = 'select';

            public function render_content() {
                ?>
                    <label>
                      <span><?php echo esc_html( $this->label ); ?></span>
                      <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                      <select>
                        <option value="0" <?php if(!$this->value): ?>selected="selected"<?php endif; ?>><?php esc_attr_e('Default', 'bangla'); ?></option>
                      </select>
                    </label>
                <?php
            }
        }

        // Layout custom control for select sidebar
        class bangla_Customize_Layout_Control extends WP_Customize_Control {

            public $type = 'layout';
            public function render_content() { ?>

                <div class="bangla-layout-select">

                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>

                <ul>
                <?php 
                    $name = '_customize-radio-' . $this->id; 

                    foreach ( $this->choices as $value => $label ) : ?>
                        <li>
                            <label for="<?php echo esc_attr($this->id); ?>[<?php echo esc_attr( $value ); ?>]" title="<?php echo esc_attr( $label ); ?>">
                                <input type="radio" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($this->id); ?>[<?php echo esc_attr( $value ); ?>]" value="<?php echo esc_attr( $value ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
                                <img src="<?php echo get_template_directory_uri() . '/admin/images/'.esc_attr( $value ).'.png';  ?>" alt="Left Sidebar" />
                            </label>
                        </li>
                   
                    <?php endforeach; ?>

                    </ul>
                </div>
                <?php
            }
        }

        // Header custom control for select sidebar
        class bangla_Customize_Header_Layout_Control extends WP_Customize_Control {

            public $type = 'layout_header';
            public function render_content() { ?>

                <div class="bangla-layout-select">

                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>

                <ul>
                <?php 
                    $name = '_customize-radio-' . $this->id; 

                    foreach ( $this->choices as $value => $label ) : ?>
                        <li>
                            <label for="<?php echo esc_attr($this->id); ?>[<?php echo esc_attr( $value ); ?>]" title="<?php echo esc_attr( $label ); ?>">
                                <input type="radio" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($this->id); ?>[<?php echo esc_attr( $value ); ?>]" value="<?php echo esc_attr( $value ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
                                <img src="<?php echo get_template_directory_uri() . '/admin/images/header-'.esc_attr( $value ).'.png';  ?>" alt="Left Sidebar" />
                            </label>
                        </li>
                   
                    <?php endforeach; ?>

                    </ul>
                </div>
                <?php
            }
        }


        // Social custom control for select sidebar
        class bangla_Customize_Social_Control extends WP_Customize_Control {
            private static $firstLoad = true;
            public $type = 'social';
            public function render_content() { 
                // the saved value is an array. convert it to csv
                if ( is_array( $this->value() ) ) {
                    $savedValueCSV = implode( ',', $this->value() );
                    $values = $this->value();
                } else {
                    $savedValueCSV = $this->value();
                    $values = explode( ',', $this->value() );
                }
                if ( self::$firstLoad ) {
                    self::$firstLoad = false;
                    ?>
                    <script>
                    jQuery(document).ready( function($) {
                        "use strict";
                        $( 'input.broxme_wp-social-link' ).change( function(event) {
                            event.preventDefault();
                            var csv = '';
                            $( this ).parents( 'li:eq(0)' ).find( 'input[type=text]' ).each( function() {
                                if ($( this ).val()) {
                                    csv += $( this ).attr( 'value' ) + ',';
                                }
                            } );
                            csv = csv.replace(/,+$/, "");
                            $( this ).parents( 'li:eq(0)' ).find( 'input[type=hidden]' ).val(csv)
                            // we need to trigger the field afterwards to enable the save button
                            .trigger( 'change' );
                            return true;
                        } );
                    } );
                    </script>
                    <?php
                } ?>


                <div class="bangla-social-link">
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                    <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>              
                        <?php
                        for ( $links = 0; $links <= 4; $links++ ) : ?>
                            <label for="<?php echo esc_html($this->id.$links); ?>">
                                <input type="text" class="broxme_wp-social-link" id="<?php echo esc_html($this->id.$links); ?>" value="<?php echo isset($values[$links]) ? esc_html($values[$links]) : ''; ?>" placeholder="<?php echo esc_html_x('http://', 'backend', 'bangla') ?>" />
                            </label>
                        <?php endfor; ?>
                    
                    <input type="hidden" value="<?php echo esc_html( $savedValueCSV ); ?>" <?php $this->link(); ?> />
                </div><?php
            }
        }

        /**
         * Multiple check customize control class.
         * @since 1.0.0
         */
        class bangla_Customize_Multicheck_Control extends WP_Customize_Control {
            public $description = '';
            public $subtitle = '';
            private static $firstLoad = true;
            // Since theme_mod cannot handle multichecks, we will do it with some JS
            public function render_content() {
                // the saved value is an array. convert it to csv
                if ( is_array( $this->value() ) ) {
                    $savedValueCSV = implode( ',', $this->value() );
                    $values = $this->value();
                } else {
                    $savedValueCSV = $this->value();
                    $values = explode( ',', $this->value() );
                }
                if ( self::$firstLoad ) {
                    self::$firstLoad = false;
                    ?>
                    <script>
                    jQuery(document).ready( function($) {
                        "use strict";
                        $( 'input.broxme_wp-multicheck' ).change( function(event) {
                            event.preventDefault();
                            var csv = '';
                            $( this ).parents( 'li:eq(0)' ).find( 'input[type=checkbox]' ).each( function() {
                                if ($( this ).is( ':checked' )) {
                                    csv += $( this ).attr( 'value' ) + ',';
                                }
                            } );
                            csv = csv.replace(/,+$/, "");
                            $( this ).parents( 'li:eq(0)' ).find( 'input[type=hidden]' ).val(csv)
                            // we need to trigger the field afterwards to enable the save button
                            .trigger( 'change' );
                            return true;
                        } );
                    } );
                    </script>
                    <?php
                } ?>
                <label class='broxme_wp-multicheck-container'>
                    <span class="customize-control-title">
                        <?php echo esc_html( $this->label ); ?>
                        <?php if ( isset( $this->description ) && '' != $this->description ) { ?>
                            <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $this->description ) ); ?>">?</a>
                        <?php } ?>
                    </span>
                    <?php if ( '' != $this->subtitle ) : ?>
                        <div class="customizer-subtitle"><?php echo esc_html($this->subtitle); ?></div>
                    <?php endif; ?>
                    <?php
                    foreach ( $this->choices as $value => $label ) {
                        printf( '<label for="%s"><input class="broxme_wp-multicheck" id="%s" type="checkbox" value="%s" %s/> %s</label><br>',
                            $this->id . $value,
                            $this->id . $value,
                            esc_attr( $value ),
                            checked( in_array( $value, $values ), true, false ),
                            $label
                        );
                    }
                    ?>
                    <input type="hidden" value="<?php echo esc_attr( $savedValueCSV ); ?>" <?php $this->link(); ?> />
                </label>
                <?php
            }
        }

    endif;

}
add_action( 'customize_register', 'bangla_customize_register' );

/* custom sanitization */
function bangla_sanitize_textarea($string) {
    return htmlspecialchars_decode(esc_textarea( $string));
}

// global_layout activate check for customizer option visible 
function bangla_global_layout_check() {

    if ( get_theme_mod('bangla_global_layout' == 'boxed') ) {
        return true;
    } else {
        return false;
    }
}


// toolbar activate check for customizer option visible 
function bangla_toolbar_check() {

    if ( get_theme_mod('bangla_toolbar') ) {
        return true;
    } else {
        return false;
    }
}

// toolbar activate check for customizer option visible 
function bangla_toolbar_left_custom_check() {

    if ( get_theme_mod('bangla_toolbar') == 1 and get_theme_mod('bangla_toolbar_left') == 'custom-left') {
        return true;
    } else {
        return false;
    }
}

function bangla_toolbar_right_custom_check() {

    if ( get_theme_mod('bangla_toolbar') == 1 and get_theme_mod('bangla_toolbar_right') == 'custom-right' ) {
        return true;
    } else {
        return false;
    }
}

// custom sanitize
function bangla_sanitize_choices( $input, $setting ) {
    global $wp_customize;
 
    $control = $wp_customize->get_control( $setting->id );
 
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}

function bangla_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return 0;
    }
}

if ( ! function_exists( 'bangla_sanitize_color_scheme' ) ) :

function bangla_sanitize_color_scheme( $value ) {
    $color_schemes = bangla_get_color_scheme_choices();

    if ( ! array_key_exists( $value, $color_schemes ) ) {
        return 'default';
    }
    return $value;
}
endif; // bangla_sanitize_color_scheme

function bangla_toolbar_social_check() {
    if ( get_theme_mod('bangla_toolbar_left') == 'social' || get_theme_mod('bangla_toolbar_right') == 'social' ) {
        return true;
    } else {
        return false;
    }
}

function bangla_header_layout_check() {
    if ( get_theme_mod('bangla_header_layout') == 'side-left' || get_theme_mod('bangla_header_layout') == 'side-right' ) {
        return false;
    } else {
        return true;
    }
}




function bangla_bottom_gutter_collapse_check() {
    if ( get_theme_mod('bangla_bottom_gutter') != 'collapse' ) {
        return true;
    } else {
        return false;
    }
}

function bangla_copyright_text_custom_show_check() {
    if ( get_theme_mod('bangla_copyright_text_custom_show') ) {
        return true;
    } else {
        return false;
    }
}

function bangla_titlebar_bg_check() {
    if ( get_theme_mod('bangla_titlebar_bg_style') == 'media' ) {
        return true;
    } else {
        return false;
    }
}

function bangla_main_bg_check() {
    if ( get_theme_mod('bangla_main_bg_style') == 'media' ) {
        return true;
    } else {
        return false;
    }
}

function bangla_header_transparent_check() {
    if ( get_theme_mod('bangla_header_transparent') == false ) {
        return true;
    } else {
        return false;
    }
}

function bangla_header_bg_style_check() {
    if ( get_theme_mod('bangla_header_bg_style') == 'media' ) {
        return true;
    } else {
        return false;
    }
}

function bangla_header_bg_img_check() {
    if ( get_theme_mod('bangla_header_bg_img')) {
        return true;
    } else {
        return false;
    }
}

function bangla_bottom_bg_custom_color_check() {
    if ( get_theme_mod('bangla_bottom_bg_style') == 'custom' ) {
        return true;
    } else {
        return false;
    }
}

function bangla_bottom_txt_custom_color_check() {
    if ( get_theme_mod('bangla_bottom_txt_style') == 'custom' ) {
        return true;
    } else {
        return false;
    }
}

function bangla_bottom_bg_style_check() {
    if ( get_theme_mod('bangla_bottom_bg_style') == 'media' ) {
        return true;
    } else {
        return false;
    }
}

function bangla_bottom_bg_img_check() {
    if ( get_theme_mod('bangla_bottom_bg_img')) {
        return true;
    } else {
        return false;
    }
}

function bangla_header_fixed_check() {
    if ( get_theme_mod('bangla_header_sticky') == 'fixed' ) {
        return true;
    } else {
        return false;
    }
}

function bangla_offcanvas_mode_check() {
    if ( get_theme_mod('bangla_mobile_offcanvas_style') == 'offcanvas' ) {
        return true;
    } else {
        return false;
    }
}

function bangla_homepage_check() {
    if ( is_front_page() || is_home() || is_single()) {
        return false;
    } else {
        return true;
    }
}

function bangla_titlebar_check() {
    if ( is_front_page()) {
        return false;
    } else {
        return true;
    }
}

function bangla_cookie_policy_button_check() {
    if ( get_theme_mod('bangla_cookie_policy_button') ) {
        return true;
    } else {
        return false;
    }
}

// Preloader callback checking

function bangla_preloader_logo_check() {
    if (get_theme_mod('bangla_preloader_logo') == 'custom') {
        return true;
    } else {
        return false;
    }
}

function bangla_preloader_text_check() {
    if (get_theme_mod('bangla_preloader_text') == 'custom') {
        return true;
    } else {
        return false;
    }
}

function bangla_preloader_animation_check() {
    if (get_theme_mod('bangla_preloader_animation')) {
        return true;
    } else {
        return false;
    }
}
function bangla_copyright_bg_custom_color_check() {
    if ( get_theme_mod('bangla_copyright_bg_style') == 'custom' ) {
        return true;
    } else {
        return false;
    }
}
function bangla_copyright_bg_style_check() {
    if ( get_theme_mod('bangla_copyright_bg_style') == 'media' ) {
        return true;
    } else {
        return false;
    }
}

function bangla_copyright_bg_img_check() {
    if ( get_theme_mod('bangla_copyright_bg_img')) {
        return true;
    } else {
        return false;
    }
}

function bangla_totop_check() {
    if ( get_theme_mod('bangla_totop_show')) {
        return true;
    } else {
        return false;
    }
}