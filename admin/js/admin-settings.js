jQuery(document).ready(function ($) {
    "use strict";
    var $elMessage = $('#compile_message');
    $elMessage.hide();
    $("#compile_less").on('click', function (e) {
        $elMessage.html('');
        $elMessage.hide();
        var compile_text = $(this).html();
        var that = this;

        $(this).html(adminSettingMessage.compilingLess);
        $.when($.ajax({
            url: ajaxurl,
            type: "post",
            data: {action: "bangla_get_styles"},
        })).then(function (data, textStatus, jqXHR) {
            if (jqXHR.status == 200) {
                //promiss to compile csscompile_message
                var parser = new (less.Parser);

                var s = new Array();
                var i = 0;
                //all stles
                for (var j in data.styles) {
                    var style = data.styles[j];
                    try {
                        parser.parse(style.less_content, function (err, tree) {
                            if (err) {
                                alert(err);
                                return;
                            }
                            style = {file: style.css_file, 'source': tree.toCSS(), less_file: style.less_file};
                            s[i++] = style;

                        })
                    } catch (error) {
                        $elMessage.show();
                        $elMessage.removeClass();
                        $elMessage.append('<div class="notice-warning settings-error notice is-dismissible"><p><b>' + style.less_file + ' ERROR:<b> ' + error.message + '</p></div>');
                    }
                }

                $.ajax({
                    url: ajaxurl,
                    type: "post",
                    data: {action: "bangla_save_styles", styles: JSON.stringify(s)},
                    success: function (data) {
                        $elMessage.show();
                        $elMessage.removeClass();
                        var  notice_class = '';
                        for (var message in data) {
                            if (data[message].result == true) {
                                notice_class = 'notice-success';
                            } else {
                                notice_class = 'notice-error';
                            }
                            $elMessage.append('<div class="notice ' + notice_class + '"><p>' + data[message].message + '</p></div>');
                        }
                        $(that).html(compile_text);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $elMessage.show();
                        $elMessage.addClass('notice notice-error');
                        $elMessage.html(adminSettingMessage.compilingLessError);
                        $(that).html(compile_text);
                    }
                })

            }
        });
    });
});

/**
 * select menu background
 */
jQuery(document).ready(function ($) {
    var tgm_media_frame;
    var id = 0;
    jQuery('body').on('click', '.background_dropdown_menu', function () {
        id = jQuery(this).data('id');
        if (tgm_media_frame) {
            tgm_media_frame.open();
            return;
        }
        tgm_media_frame = wp.media.frames.tgm_media_frame = wp.media({
            multiple: true,
            library: {
                type: 'image'
            },
        });
        tgm_media_frame.on('select', function () {
            att = tgm_media_frame.state().get('selection').first().toJSON();
            jQuery('#image_link_' + id).val(att.url);
            jQuery("#image_preview_" + id).html('<a href="" class="remove_image" data-id="' + id + '"><span class="dashicons dashicons-dismiss"></span></a>'
                    + '<img  style="max-width: 200px" src="' + att.url + '" />');

        });
        tgm_media_frame.open();

    });

    jQuery('body').on('click', '.remove_image', function (event) {
        event.preventDefault();
        var id = jQuery(this).data('id');
        jQuery("#image_preview_" + id).html('No image selected');
        jQuery('#image_link_' + id).val('');

    });
});