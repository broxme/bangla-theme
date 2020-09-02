<?php
/**
 * The template for displaying comments.
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) { return; }

?>
<div id="comments" class="broxme_wp-container broxme_wp-container-small">

    <?php if (have_comments()) : // Check if there are any comments. ?>

        <h3 class="broxme_wp-heading-bullet broxme_wp-margin-bottom">
            <?php printf(_n('Comment', 'Comments (%s)', get_comments_number(), 'bangla'), number_format_i18n(get_comments_number())) ?>
        </h3>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through? ?>
        <nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
            <h4 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'bangla') ?></h4>
            <ul class="broxme_wp-pagination broxme_wp-flex-between">
                <li class="nav-previous"><span broxme_wp-pagination-previous></span><?php previous_comments_link(esc_html__('Older Comments', 'bangla')) ?></li>
                <li class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', 'bangla')) ?> <span broxme_wp-pagination-next></span></li>
            </ul>
        </nav>
        <?php endif; // Check for comment navigation. ?>

        <ul class="broxme_wp-comment-list">
            <?php wp_list_comments('type=comment&callback=bangla_comment') ?>
        </ul>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through? ?>
        <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
            <h4 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'bangla') ?></h4>
            <ul class="broxme_wp-pagination broxme_wp-flex-between">
                <li class="nav-previous"><span broxme_wp-pagination-previous></span> <?php previous_comments_link(esc_html__('Older Comments', 'bangla')) ?></li>
                <li class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', 'bangla')) ?> <span broxme_wp-pagination-next></span></li>
            </ul>
        </nav>
        <?php endif; // Check for comment navigation. ?>

        <hr class="broxme_wp-margin-large-top broxme_wp-margin-large-bottom">
    <?php endif; // Check for have_comments(). ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : // If comments are closed and there are comments, let's leave a little note, shall we? ?>
        <p class="broxme_wp-margin-medium broxme_wp-text-danger"><?php esc_html_e('Comments are closed.', 'bangla') ?></p>
    <?php endif ?>
    
    <?php

    $commenter     = wp_get_current_commenter();
    $req           = get_option('require_name_email');
    $aria_req      = ( $req ? " aria-required='true'" : '' );
    $required_text = '';

    $fields = array(

        'author' =>
            '<p class="comment-form-author"><label class="broxme_wp-form-label" for="author">' . esc_html__( 'Name', 'bangla' ) . '</label> ' .
            ( $req ? '<span class="required">*</span>' : '' ) .
            '<input class="broxme_wp-input" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
            '" size="30"' . $aria_req . ' /></p>',

        'email' =>
            '<p class="comment-form-email"><label class="broxme_wp-form-label" for="email">' . esc_html__( 'Email', 'bangla' ) . '</label> ' .
            ( $req ? '<span class="required">*</span>' : '' ) .
            '<input class="broxme_wp-input" id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
            '" size="30"' . $aria_req . ' /></p>',

        'url' =>
            '<p class="comment-form-url"><label class="broxme_wp-form-label" for="url">' . esc_html__( 'Website', 'bangla' ) . '</label>' .
            '<input class="broxme_wp-input" id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
            '" size="30" /></p>',
    );

    $args = array(
		'id_form'           => 'commentform',
		'class_form'        => 'comment-form',
		'id_submit'         => 'submit',
		'class_submit'      => 'broxme_wp-button broxme_wp-button-primary broxme_wp-margin-top submit broxme_wp-border-rounded',
		'name_submit'       => 'submit',
        'title_reply_before'=> '<h3 id="reply-title" class="comment-reply-title broxme_wp-heading-bullet broxme_wp-margin-bottom">',
		'title_reply'       => esc_html__( 'Leave a Reply', 'bangla' ),
		'title_reply_to'    => esc_html__( 'Leave a Reply to %s', 'bangla' ),
		'cancel_reply_link' => esc_html__( 'Cancel Reply', 'bangla' ),
		'label_submit'      => esc_html__( 'Post Comment', 'bangla' ),
		'format'            => 'xhtml',

        'comment_field' =>  '<p class="comment-form-comment"><label class="broxme_wp-form-label" for="comment">' . _x( 'Comment', 'noun', 'bangla' ) .
        '</label><textarea class="broxme_wp-textarea" id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
        '</textarea></p>',

        'must_log_in' => '<p class="must-log-in">' .
        sprintf(
          __( 'You must be <a href="%s">logged in</a> to post a comment.', 'bangla' ),
          wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
        ) . '</p>',

        'logged_in_as' => '<p class="logged-in-as">' .
        sprintf(
        	__( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'bangla' ),
          admin_url( 'profile.php' ),
          $user_identity,
          wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
        ) . '</p>',

        'comment_notes_before' => '<p class="comment-notes">' .
        esc_html__( 'Your email address will not be published.', 'bangla' ) . ( $req ? $required_text : '' ) .
        '</p>',

        // 'comment_notes_after' => '<p class="form-allowed-tags">' .
        // sprintf(
        //   esc_html__( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'bangla' ),
        //   ' <code>' . allowed_tags() . '</code>'
        // ) . '</p>',

        'fields' => apply_filters( 'comment_form_default_fields', $fields ),
    );

    comment_form($args); ?>
</div>
