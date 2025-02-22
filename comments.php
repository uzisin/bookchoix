<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments and the comment
 * form. The actual display of comments is handled by a callback to
 * acmthemes_comment() which is located at functions/comments-callback.php
 *
 * @package BookChoix WordPress theme
 */

// Return if password is required
if ( post_password_required() ) {
	return;
}

// Add classes to the comments main wrapper
$classes = 'comments-area clr';

if ( get_comments_number() != 0 ) {
	$classes .= ' has-comments';
}

if ( ! comments_open() && get_comments_number() < 1 ) {
	$classes .= ' empty-closed-comments';
	return;
}

if ( 'full-screen' == acmthemes_post_layout() ) {
	$classes .= ' container';
} ?>

<div id="comments" class="<?php echo esc_attr( $classes ); ?>">

    <?php if ( have_comments() ) : ?>
      <h3 class="comments-title">
        <?php
          printf( _nx( '1 comment', '%1$s comments', get_comments_number(), 'comments title', 'bookchoix' ),
                number_format_i18n( get_comments_number() ), get_the_title() );
        ?>
      </h3>

      <ol class="comment-list">
          <?php
						if( function_exists( 'bookchoix_list_blog_comments' ) ) {
              bookchoix_list_blog_comments();
						}
          ?>
      </ol><!-- .comment-list -->

      <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
          <nav class="comment-navigation" role="navigation">
                  <h1 class="screen-reader-text"><?php esc_html_e ( 'Comment navigation', 'bookchoix' ); ?></h1>
                  <div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'bookchoix' ) ); ?></div>
                  <div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'bookchoix' ) ); ?></div>
          </nav><!-- #comment-navigation -->
      <?php endif;  ?>

    <?php endif; // have_comments() ?>

    <?php
            // If comments are closed and there are comments, let's leave a little note, shall we?
            if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
    ?>
            <p class="no-comments"><?php esc_html_e ( 'Comments are closed.', 'bookchoix' ); ?></p>
    <?php endif; ?>

            <div class="clr">
                <?php

                $commenter = wp_get_current_commenter();
                $req = get_option( 'require_name_email' );
                $aria_req = ( $req ? " aria-required='true'" : '' );

                $fields  =  array(

              'author' =>
                '<div class="comment-form-author acm-col-xs-12 acm-col-sm-12 acm-col-md-6"><label for="author">' . esc_html__( 'Name', 'bookchoix' ) . ( $req ? '<span class="required"> *</span>' : '' ) . '</label> ' .

                '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
                '" size="30"' . $aria_req . ' placeholder="' . esc_html__('enter your name...', 'bookchoix') . '" /></div>',

              'email' =>
                '<div class="comment-form-email acm-col-xs-12 acm-col-sm-12 acm-col-md-6"><label for="email">' . esc_html__( 'Email', 'bookchoix' ) . ( $req ? '<span class="required"> *</span>' : '' ) . '</label> ' .
                '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
                '" size="30"' . $aria_req . ' placeholder="' . esc_html__('enter your e-mail address...', 'bookchoix') . '" /></div>',

              'url' =>
                '<div class="comment-form-url acm-col-xs-12"><label for="url">' . esc_html__( 'Website URL', 'bookchoix' ) . '</label>' .
                '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
                '" size="30" placeholder="' . esc_html__('enter your website URL...', 'bookchoix') . '" /></div>',
            );
                $args = array(
                    'comment_field' => '<div class="comment-textarea comment-form-comment col-xs-12"><label for="comment">' . esc_html__( 'Comment', 'bookchoix' ) .
                    '</label><textarea id="comment" name="comment" rows="20" col="10" aria-required="true" placeholder="' . esc_html__('Comment here...', 'bookchoix') . '"></textarea></div>',
                    'fields' => apply_filters( 'comment_form_default_fields', $fields ),
                    'label_submit'      => esc_html__( 'Post Comment', 'bookchoix' ),
                    'class_submit'      => 'btn btn-primary',
                );
                comment_form($args);

                ?>
            </div><!-- .clearfix -->
</div><!-- .comments-area -->
